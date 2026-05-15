<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientWelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ClientController extends Controller
{
    // Client list
    public function index()
    {
        $clients = User::where('role', 'client')->latest()->get();
        return view('admin.clients.index', compact('clients'));
    }

    // Create form
    public function create()
    {
        return view('admin.clients.create');
    }

    // Store client
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'city'  => 'required|string|max:100',
            'age'   => 'required|integer|min:1|max:120',
        ]);

        // System generated password
        $plainPassword = Str::random(10);

        $client = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'city'           => $request->city,
            'age'            => $request->age,
            'password'       => $plainPassword, // auto hashed via cast
            'plain_password' => $plainPassword, // store for reference
            'password_changed' => false, // new clients haven't changed password yet
            'role'           => 'client',
            'status'         => 'active',
            'subscription_start_date' => now(),
            'subscription_fee' => auth()->user()->default_subscription_fee ?? 0,
        ]);

        $flashType = 'success';
        $flashMessage = "Client created! Credentials sent to {$client->email}";

        try {
            Mail::to($client->email)->send(
                new ClientWelcomeMail($client->name, $client->email, $plainPassword)
            );
        } catch (TransportExceptionInterface $exception) {
            report($exception);

            $flashType = 'warning';
            $flashMessage = "Client created, but the welcome email could not be sent. Check your mail configuration and try again.";
        }

        return redirect()->route('admin.clients.index')
            ->with($flashType, $flashMessage);
    }

    // Toggle client status
    public function toggleStatus($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->status = $client->status === 'active' ? 'disabled' : 'active';
        $client->save();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client status updated successfully.');
    }
}
