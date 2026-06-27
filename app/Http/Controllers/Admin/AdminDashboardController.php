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
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');

        // Data for charts
        $usersPerMonth = User::where('role', 'user')
            ->selectRaw('count(id) as count, strftime("%m", created_at) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $transactionsPerMonth = Transaction::selectRaw('sum(amount) as total, strftime("%m", date) as month')
            ->where('type', 'expense')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return \Inertia\Inertia::render('Admin/Dashboard', [
            'totalUsers' => $totalUsers,
            'premiumUsers' => $premiumUsers,
            'totalTransactions' => $totalTransactions,
            'totalExpense' => $totalExpense,
            'chartData' => [
                'users' => $usersPerMonth,
                'expenses' => $transactionsPerMonth
            ]
        ]);
    }
}
