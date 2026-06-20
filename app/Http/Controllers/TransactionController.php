<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Services\CategorizationService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $categorizationService;

    public function __construct(CategorizationService $categorizationService)
    {
        $this->categorizationService = $categorizationService;
    }

    public function index(Request $request)
    {
        $query = Transaction::with('category')->where('user_id', auth()->id());

        // Filtering
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('transaction_date', '<=', $request->end_date);
        }
        if ($request->has('search') && $request->search != '') {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(10);
        $categories = Category::whereNull('user_id')->orWhere('user_id', auth()->id())->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->is_premium) {
            $currentMonthCount = Transaction::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            if ($currentMonthCount >= 30) {
                return redirect()->back()->withErrors(['limit' => 'Limit transaksi bulanan tercapai (Maksimal 30). Silakan Upgrade ke Premium!']);
            }
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $categoryId = $request->category_id;

        if (!$categoryId) {
            $categoryId = $this->categorizationService->detectCategory($request->description);
        }

        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $categoryId,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully.');
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
