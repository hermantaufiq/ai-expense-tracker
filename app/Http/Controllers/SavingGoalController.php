<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use Illuminate\Http\Request;

class SavingGoalController extends Controller
{
    public function index()
    {
        $goals = SavingGoal::where('user_id', auth()->id())
            ->orderBy('is_completed')
            ->orderBy('deadline')
            ->get();

        return view('saving-goals.index', compact('goals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'icon'          => 'nullable|string|max:10',
            'target_amount' => 'required|numeric|min:1000',
            'deadline'      => 'nullable|date|after:today',
        ]);

        SavingGoal::create([
            'user_id'        => auth()->id(),
            'name'           => $request->name,
            'icon'           => $request->icon ?: '🎯',
            'target_amount'  => $request->target_amount,
            'current_amount' => 0,
            'deadline'       => $request->deadline,
        ]);

        return redirect()->route('saving-goals.index')->with('success', 'Target menabung berhasil dibuat!');
    }

    public function addFunds(Request $request, SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== auth()->id()) abort(403);

        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $newAmount = $savingGoal->current_amount + $request->amount;
        $isCompleted = $newAmount >= $savingGoal->target_amount;

        $savingGoal->update([
            'current_amount' => min($newAmount, $savingGoal->target_amount),
            'is_completed'   => $isCompleted,
        ]);

        $msg = $isCompleted
            ? '🎉 Selamat! Target tabungan "' . $savingGoal->name . '" sudah tercapai!'
            : 'Dana berhasil ditambahkan ke target "' . $savingGoal->name . '"!';

        return redirect()->route('saving-goals.index')->with('success', $msg);
    }

    public function destroy(SavingGoal $savingGoal)
    {
        if ($savingGoal->user_id !== auth()->id()) abort(403);
        $savingGoal->delete();

        return redirect()->route('saving-goals.index')->with('success', 'Target menabung dihapus.');
    }
}
