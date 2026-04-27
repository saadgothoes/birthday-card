<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientWelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
            'role'           => 'client',
        ]);

        // Email bhejo
        Mail::to($client->email)->send(
            new ClientWelcomeMail($client->name, $client->email, $plainPassword)
        );

        return redirect()->route('admin.clients.index')
            ->with('success', "Client created! Credentials sent to {$client->email}");
    }
}