<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Groups user owns or belongs to
        $groups = $user->groups()->withCount('members')->get();
        
        return view('groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $group = Group::create([
                'name' => $request->name,
                'owner_id' => auth()->id(),
            ]);

            // Add owner as admin member
            $group->members()->attach(auth()->id(), ['role' => 'admin']);
        });

        return back()->with('success', 'Grup berhasil dibuat!');
    }

    public function show(Group $group)
    {
        // Ensure user is member
        if (!$group->members()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        $group->load('members');
        
        $transactions = $group->transactions()->with(['user', 'category'])->latest('transaction_date')->get();
        
        // Simple Summary
        $totalExpense = $transactions->where('category.type', 'expense')->sum('amount');
        $totalIncome = $transactions->where('category.type', 'income')->sum('amount');

        $categories = Category::all();

        return view('groups.show', compact('group', 'transactions', 'totalExpense', 'totalIncome', 'categories'));
    }

    public function invite(Request $request, Group $group)
    {
        if ($group->owner_id !== auth()->id()) {
            abort(403, 'Hanya pembuat grup yang bisa mengundang.');
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        $userToInvite = User::where('email', $request->email)->first();

        if (!$userToInvite) {
            return back()->with('error', 'User dengan email tersebut belum terdaftar di aplikasi.');
        }

        if ($group->members()->where('user_id', $userToInvite->id)->exists()) {
            return back()->with('error', 'User tersebut sudah ada di dalam grup.');
        }

        $group->members()->attach($userToInvite->id, ['role' => 'member']);

        return back()->with('success', $userToInvite->name . ' berhasil ditambahkan ke grup!');
    }

    public function storeTransaction(Request $request, Group $group)
    {
        if (!$group->members()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'group_id' => $group->id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return back()->with('success', 'Transaksi bersama berhasil ditambahkan!');
    }
}
