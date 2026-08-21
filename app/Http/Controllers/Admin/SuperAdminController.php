<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use App\Models\SubscriptionRequest;
use App\Models\User;
use App\Support\CardInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $approved = SubscriptionRequest::where('status', SubscriptionRequest::APPROVED);

        return view('admin.dashboard', [
            'totalPayments' => (float) (clone $approved)->sum('plan_amount'),
            'dailyPayments' => (float) (clone $approved)->whereDate('reviewed_at', today())->sum('plan_amount'),
            'weeklyPayments' => (float) (clone $approved)->where('reviewed_at', '>=', now()->startOfWeek())->sum('plan_amount'),
        ]);
    }

    // Login Page
    public function loginPage()
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // Login Logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->isSuperAdmin()) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'You are not authorized as Super Admin.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Update settings
    public function updateSettings(Request $request)
    {
        $request->validate([
            'default_subscription_fee' => 'required|numeric|min:0',
        ]);

        Auth::user()->update([
            'default_subscription_fee' => $request->default_subscription_fee,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Settings updated successfully.');
    }

    // BG Owner Page
    public function bgOwner()
    {
        // Only the shell is rendered here. The client content itself is
        // fetched separately, after the PIN is accepted, so uploads and lock
        // codes are never sitting in the page source of an unverified view.
        return view('admin.bg-owner', [
            'clientCount' => User::where('role', 'client')->count(),
        ]);
    }

    /**
     * Everything every client has put into the system — their details, every
     * uploaded image and clip, everything they typed, and every share link
     * they generated.
     *
     * PIN-gated: the session flag set by verifyBgOwnerPin() is required, so
     * this cannot be called straight from the URL bar to bypass the prompt.
     */
    public function bgOwnerData(Request $request)
    {
        abort_unless($request->session()->get('bg_owner_verified'), 403, 'BG Owner PIN not verified.');

        $clients = User::where('role', 'client')
            ->with(['birthdayCards' => fn ($q) => $q->orderByDesc('updated_at')])
            ->orderBy('name')
            ->get();

        $totals = ['images' => 0, 'videos' => 0, 'links' => 0, 'cards' => 0, 'bytes' => 0];

        $payload = $clients->map(function (User $client) use (&$totals) {
            $cards = $client->birthdayCards->map(function (BirthdayCard $card) use (&$totals) {
                $images = CardInventory::images($card);
                $videos = CardInventory::videos($card);
                $bytes = CardInventory::storageBytes($card);
                $link = CardInventory::shareUrl($card);

                $totals['images'] += count($images);
                $totals['videos'] += count($videos);
                $totals['bytes'] += $bytes;
                $totals['cards']++;
                if ($link) {
                    $totals['links']++;
                }

                return [
                    'id' => $card->id,
                    'title' => $card->displayTitle(),
                    'theme' => $card->theme,
                    'variant' => $card->variant,
                    'step' => min(10, max(1, (int) $card->current_step)),
                    'published' => (bool) $card->is_published,
                    'link' => $link,
                    'lock_code' => $card->lock_code,
                    'created_at' => $card->created_at?->format('d M Y, g:i A'),
                    'updated_at' => $card->updated_at?->diffForHumans(),
                    'storage' => CardInventory::humanBytes($bytes),
                    'images' => $images,
                    'videos' => $videos,
                    'texts' => CardInventory::texts($card),
                ];
            })->values();

            return [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'city' => $client->city,
                'age' => $client->age,
                'status' => $client->status,
                'plan' => $client->planLabel(),
                'subscription' => $client->subscription_status,
                'joined' => $client->created_at?->format('d M Y'),
                'devices' => $client->activeSessionCount(),
                'card_count' => $cards->count(),
                'image_count' => $cards->sum(fn ($c) => count($c['images'])),
                'video_count' => $cards->sum(fn ($c) => count($c['videos'])),
                'link_count' => $cards->filter(fn ($c) => $c['link'])->count(),
                'cards' => $cards,
            ];
        })->values();

        $totals['storage'] = CardInventory::humanBytes($totals['bytes']);

        return response()->json([
            'success' => true,
            'totals' => $totals,
            'clients' => $payload,
        ]);
    }

    /**
     * Every share link generated across the system, with the client who
     * generated it. Answers "who generated how many links, and which".
     */
    public function links()
    {
        $cards = BirthdayCard::with('user')
            ->whereNotNull('slug')
            ->where('is_published', true)
            ->orderByDesc('updated_at')
            ->get();

        // Per-client tally, so the page leads with who is generating links.
        $perClient = $cards->groupBy('user_id')->map(fn ($group) => [
            'user' => $group->first()->user,
            'total' => $group->count(),
            'generated' => $group->where('is_published', true)->count(),
        ])->sortByDesc('total')->values();

        return view('admin.links.index', [
            'cards' => $cards,
            'perClient' => $perClient,
        ]);
    }

    public function toggleCardLink(int $card)
    {
        $model = BirthdayCard::where('is_published', true)->findOrFail($card);

        if ($model->linkIsDisabled()) {
            if ($model->linkIsExpired()) {
                return back()->with('error', 'This link expired after 15 days and cannot be enabled again.');
            }

            $model->forceFill(['link_disabled_at' => null])->save();
            return back()->with('success', 'Card link enabled.');
        }

        $model->forceFill(['link_disabled_at' => now()])->save();
        return back()->with('success', 'Card link disabled.');
    }

    // Verify BG Owner PIN
    public function verifyBgOwnerPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
        ]);

        if (Auth::user()->bg_owner_pin === $request->pin) {
            // Store in session that PIN is verified
            session(['bg_owner_verified' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid PIN']);
    }
}
