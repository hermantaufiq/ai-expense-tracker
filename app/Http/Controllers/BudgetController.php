<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $now = Carbon::now();

        $budgets = Budget::with('category')
            ->where('user_id', $userId)
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->get()
            ->map(function ($budget) {
                $budget->spent = $budget->spent;
                $budget->percentage = $budget->percentage;
                $budget->remaining = $budget->remaining;
                return $budget;
            });

        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', $userId)
            ->where('type', 'expense')
            ->get();

        // Exclude categories that already have a budget this month
        $budgetedCategoryIds = $budgets->pluck('category_id')->toArray();
        $availableCategories = $categories->whereNotIn('id', $budgetedCategoryIds)->values();

        return view('budgets.index', compact('budgets', 'availableCategories', 'now'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:1000',
        ]);

        $now = Carbon::now();

        Budget::updateOrCreate(
            [
                'user_id'     => auth()->id(),
                'category_id' => $request->category_id,
                'month'       => $now->month,
                'year'        => $now->year,
            ],
            ['amount' => $request->amount]
        );

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil disimpan!');
    }

    public function update(Request $request, Budget $budget)
    {
        if ($budget->user_id !== auth()->id()) abort(403);

        $request->validate(['amount' => 'required|numeric|min:1000']);
        $budget->update(['amount' => $request->amount]);

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil diperbarui!');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== auth()->id()) abort(403);
        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil dihapus.');
    }
}
