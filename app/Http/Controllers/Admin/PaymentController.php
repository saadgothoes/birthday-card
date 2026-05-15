<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')->latest()->get();
        $totalPayments = $clients->sum('subscription_fee');
        return view('admin.payments.index', compact('clients', 'totalPayments'));
    }
}
