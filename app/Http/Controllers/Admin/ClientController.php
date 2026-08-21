<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayCard;
use App\Models\SubscriptionRequest;
use App\Models\User;
use App\Support\SubscriptionPlans;
use Illuminate\Http\Request;

/**
 * Super Admin's view of the client base.
 *
 * Clients create their own accounts now, so there is no create/store here any
 * more — this controller reports on accounts and controls their access.
 */
class ClientController extends Controller
{
    /** Client list, with the plan and usage figures on every row. */
    public function index()
    {
        $clients = User::where('role', 'client')
            ->withCount('birthdayCards')
            ->latest()
            ->get();

        // One grouped query instead of a session lookup per row.
        $sessionCounts = collect();
        if (config('session.driver') === 'database') {
            $sessionCounts = \Illuminate\Support\Facades\DB::table(config('session.table', 'sessions'))
                ->whereNotNull('user_id')
                ->selectRaw('user_id, COUNT(*) as total')
                ->groupBy('user_id')
                ->pluck('total', 'user_id');
        }

        $pendingCounts = SubscriptionRequest::where('status', SubscriptionRequest::PENDING)
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        return view('admin.clients.index', [
            'clients' => $clients,
            'sessionCounts' => $sessionCounts,
            'pendingCounts' => $pendingCounts,
        ]);
    }

    /** Everything the system knows about one client. */
    public function show(int $id)
    {
        $client = User::where('role', 'client')
            ->withCount('birthdayCards')
            ->findOrFail($id);

        return view('admin.clients.show', [
            'client' => $client,
            'cards' => BirthdayCard::where('user_id', $client->id)
                ->orderByDesc('updated_at')
                ->get(),
            'sessions' => $client->activeSessions(),
            'requests' => $client->subscriptionRequests()->with('reviewer')->get(),
            'plans' => SubscriptionPlans::all(),
        ]);
    }

    /** Enable/disable a client — unchanged behaviour. */
    public function toggleStatus($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->status = $client->status === 'active' ? 'disabled' : 'active';
        $client->save();

        return back()->with('success', 'Client status updated successfully.');
    }
}
