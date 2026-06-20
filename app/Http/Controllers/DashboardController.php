<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Calculate Totals
        $totalIncome = Transaction::where('user_id', $userId)
            ->whereHas('category', function ($query) {
                $query->where('type', 'income');
            })->sum('amount');

        $totalExpense = Transaction::where('user_id', $userId)
            ->whereHas('category', function ($query) {
                $query->where('type', 'expense');
            })->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // Recent Transactions
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Chart Data: Monthly Expenses (Last 6 months)
        $monthlyExpenses = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sum = Transaction::where('user_id', $userId)
                ->whereHas('category', function ($query) {
                    $query->where('type', 'expense');
                })
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->sum('amount');
            
            $monthlyExpenses['labels'][] = $month->format('M');
            $monthlyExpenses['data'][] = $sum;
        }

        // Chart Data: Category Distribution
        $categoryDistribution = Transaction::selectRaw('category_id, sum(amount) as total')
            ->with('category')
            ->where('user_id', $userId)
            ->whereHas('category', function ($query) {
                $query->where('type', 'expense');
            })
            ->groupBy('category_id')
            ->get();

        $pieChart = [
            'labels' => $categoryDistribution->pluck('category.name')->toArray(),
            'data' => $categoryDistribution->pluck('total')->toArray(),
            'colors' => $categoryDistribution->pluck('category.color_code')->toArray()
        ];

        // Format to handle empty colors gracefully
        foreach ($pieChart['colors'] as $key => $color) {
            if (!$color) {
                $pieChart['colors'][$key] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            }
        }

        return view('dashboard', compact(
            'totalIncome', 
            'totalExpense', 
            'balance', 
            'recentTransactions',
            'monthlyExpenses',
            'pieChart'
        ));
    }
}
