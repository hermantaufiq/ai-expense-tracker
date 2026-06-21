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
                'name'     => $request->name,
                'owner_id' => auth()->id(),
            ]);

            // Add owner as admin member
            $group->members()->attach(auth()->id(), ['role' => 'admin']);
        });

        return back()->with('success', 'Grup "' . $request->name . '" berhasil dibuat!');
    }

    public function show(Group $group)
    {
        // Ensure user is member
        if (!$group->members()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Anda bukan anggota grup ini.');
        }

        $group->load('members');

        $transactions = $group->transactions()
            ->with(['user', 'category'])
            ->latest('transaction_date')
            ->paginate(20);

        // Calculate summaries from all group transactions
        $allTransactions = $group->transactions()->with('category')->get();
        $totalExpense = $allTransactions->filter(fn($t) => optional($t->category)->type === 'expense')->sum('amount');
        $totalIncome  = $allTransactions->filter(fn($t) => optional($t->category)->type === 'income')->sum('amount');

        $categories = Category::where('user_id', null)->orWhere('user_id', auth()->id())->get();

        return view('groups.show', compact('group', 'transactions', 'totalExpense', 'totalIncome', 'categories'));
    }

    public function invite(Request $request, Group $group)
    {
        if ($group->owner_id !== auth()->id()) {
            abort(403, 'Hanya pemilik grup yang bisa mengundang.');
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        $userToInvite = User::where('email', $request->email)->first();

        if (!$userToInvite) {
            return back()->with('error', 'Pengguna dengan email tersebut belum terdaftar di aplikasi.');
        }

        if ($group->members()->where('user_id', $userToInvite->id)->exists()) {
            return back()->with('error', $userToInvite->name . ' sudah ada di dalam grup ini.');
        }

        $group->members()->attach($userToInvite->id, ['role' => 'member']);

        return back()->with('success', $userToInvite->name . ' berhasil ditambahkan ke grup!');
    }

    public function removeMember(Group $group, User $user)
    {
        // Only owner can remove, and cannot remove themselves
        if ($group->owner_id !== auth()->id()) {
            abort(403, 'Hanya pemilik grup yang bisa mengeluarkan anggota.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa mengeluarkan diri sendiri. Gunakan fitur keluar dari grup.');
        }

        $group->members()->detach($user->id);

        return back()->with('success', $user->name . ' berhasil dikeluarkan dari grup.');
    }

    public function leave(Group $group)
    {
        $userId = auth()->id();

        if (!$group->members()->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Anda bukan anggota grup ini.');
        }

        if ($group->owner_id === $userId) {
            return back()->with('error', 'Pemilik grup tidak bisa keluar. Hapus grup atau transfer kepemilikan terlebih dahulu.');
        }

        $group->members()->detach($userId);

        return redirect()->route('groups.index')->with('success', 'Anda berhasil keluar dari grup "' . $group->name . '".');
    }

    public function destroy(Group $group)
    {
        if ($group->owner_id !== auth()->id()) {
            abort(403, 'Hanya pemilik grup yang bisa menghapus grup.');
        }

        DB::transaction(function () use ($group) {
            // Nullify group_id on related transactions (not delete them)
            Transaction::where('group_id', $group->id)->update(['group_id' => null]);
            // Detach all members
            $group->members()->detach();
            // Delete the group
            $group->delete();
        });

        return redirect()->route('groups.index')->with('success', 'Grup berhasil dihapus.');
    }

    public function storeTransaction(Request $request, Group $group)
    {
        if (!$group->members()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'amount'           => 'required|numeric|min:1',
            'description'      => 'required|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        Transaction::create([
            'user_id'          => auth()->id(),
            'group_id'         => $group->id,
            'category_id'      => $request->category_id,
            'amount'           => $request->amount,
            'description'      => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return back()->with('success', 'Transaksi bersama berhasil ditambahkan!');
    }
}
