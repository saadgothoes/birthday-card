<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\SupportContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * The Super Admin's payment settings: the accounts clients send money to, and
 * the channels they can reach support on when a transfer goes wrong.
 *
 * Both live on one screen because they are always edited together — a new
 * account number usually arrives with a new number to complain to.
 */
class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('admin.payment-methods.index', [
            'methods' => PaymentMethod::orderBy('sort_order')->orderBy('id')->get(),
            'contacts' => SupportContact::orderBy('sort_order')->orderBy('id')->get(),
            'types' => PaymentMethod::TYPES,
            'channels' => SupportContact::CHANNELS,
        ]);
    }

    // ─── Payment methods ─────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $this->validateMethod($request);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['qr_image_path'] = $this->storeQr($request);

        PaymentMethod::create($data);

        return back()->with('success', 'Payment method added — clients will see it on the plan screen.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $data = $this->validateMethod($request);

        $data['is_active'] = $request->boolean('is_active', true);

        // A new upload replaces the old file; leaving the field empty keeps
        // whatever image is already on the method.
        if ($path = $this->storeQr($request)) {
            $this->deleteQr($paymentMethod);
            $data['qr_image_path'] = $path;
        }

        $paymentMethod->update($data);

        return back()->with('success', 'Payment method updated.');
    }

    /** Hide a method from clients without losing the requests attached to it. */
    public function toggle(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => ! $paymentMethod->is_active]);

        return back()->with('success', $paymentMethod->is_active
            ? 'Payment method is now visible to clients.'
            : 'Payment method hidden from clients.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $this->deleteQr($paymentMethod);
        $paymentMethod->delete();

        return back()->with('success', 'Payment method deleted.');
    }

    // ─── Support contacts ────────────────────────────────────────

    public function storeContact(Request $request)
    {
        $data = $this->validateContact($request);
        $data['is_active'] = $request->boolean('is_active', true);

        SupportContact::create($data);

        return back()->with('success', 'Support contact added.');
    }

    public function updateContact(Request $request, SupportContact $supportContact)
    {
        $data = $this->validateContact($request);
        $data['is_active'] = $request->boolean('is_active', true);

        $supportContact->update($data);

        return back()->with('success', 'Support contact updated.');
    }

    public function toggleContact(SupportContact $supportContact)
    {
        $supportContact->update(['is_active' => ! $supportContact->is_active]);

        return back()->with('success', $supportContact->is_active
            ? 'Contact is now visible to clients.'
            : 'Contact hidden from clients.');
    }

    public function destroyContact(SupportContact $supportContact)
    {
        $supportContact->delete();

        return back()->with('success', 'Support contact deleted.');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    private function validateMethod(Request $request): array
    {
        $data = $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys(PaymentMethod::TYPES)),
            'label' => 'required|string|max:120',
            'account_name' => 'required|string|max:120',
            'account_number' => 'required|string|max:120',
            'bank_name' => 'nullable|string|max:120',
            'branch_code' => 'nullable|string|max:40',
            'instructions' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // The upload itself is not a column, and the sort column is not
        // nullable — an empty box has to fall back to 0, not to null.
        unset($data['qr_image']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    private function validateContact(Request $request): array
    {
        $data = $request->validate([
            'channel' => 'required|string|in:' . implode(',', array_keys(SupportContact::CHANNELS)),
            'label' => 'required|string|max:120',
            'value' => 'required|string|max:190',
            'note' => 'nullable|string|max:190',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    private function storeQr(Request $request): ?string
    {
        return $request->hasFile('qr_image')
            ? $request->file('qr_image')->store('payment-methods', 'public')
            : null;
    }

    private function deleteQr(PaymentMethod $method): void
    {
        if ($method->qr_image_path) {
            Storage::disk('public')->delete($method->qr_image_path);
        }
    }
}
