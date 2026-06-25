<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $premiumUsers = User::where('role', 'user')->where('is_premium', true)->count();
        $totalTransactions = Transaction::count();

        return \Inertia\Inertia::render('Admin/Dashboard', [
            'totalUsers' => $totalUsers,
            'premiumUsers' => $premiumUsers,
            'totalTransactions' => $totalTransactions
        ]);
    }
}
