<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FullReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Filter year (default: current year)
        $year = $request->get('year', now()->year);

        // Monthly summary for the selected year (12 months)
        $monthlySummary = [];
        for ($m = 1; $m <= 12; $m++) {
            $income = Transaction::where('user_id', $userId)
                ->whereHas('category', fn($q) => $q->where('type', 'income'))
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $m)
                ->sum('amount');

            $expense = Transaction::where('user_id', $userId)
                ->whereHas('category', fn($q) => $q->where('type', 'expense'))
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $m)
                ->sum('amount');

            $monthlySummary[] = [
                'month'   => Carbon::create($year, $m, 1)->format('M'),
                'income'  => $income,
                'expense' => $expense,
                'net'     => $income - $expense,
            ];
        }

        // Compare this month vs last month
        $thisMonth  = Carbon::now();
        $lastMonth  = Carbon::now()->subMonth();

        $thisExpense = Transaction::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->whereYear('transaction_date', $thisMonth->year)
            ->whereMonth('transaction_date', $thisMonth->month)
            ->sum('amount');

        $lastExpense = Transaction::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->whereYear('transaction_date', $lastMonth->year)
            ->whereMonth('transaction_date', $lastMonth->month)
            ->sum('amount');

        $thisIncome = Transaction::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->whereYear('transaction_date', $thisMonth->year)
            ->whereMonth('transaction_date', $thisMonth->month)
            ->sum('amount');

        $lastIncome = Transaction::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->whereYear('transaction_date', $lastMonth->year)
            ->whereMonth('transaction_date', $lastMonth->month)
            ->sum('amount');

        // Top 5 categories by expense (all time)
        $topCategories = Transaction::selectRaw('category_id, sum(amount) as total')
            ->with('category')
            ->where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->whereYear('transaction_date', $year)
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $availableYears = Transaction::where('user_id', $userId)
            ->selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        return view('reports.full', compact(
            'monthlySummary',
            'thisExpense', 'lastExpense',
            'thisIncome', 'lastIncome',
            'topCategories',
            'year',
            'availableYears'
        ));
    }
}
