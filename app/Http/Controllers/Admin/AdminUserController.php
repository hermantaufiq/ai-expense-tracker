<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function toggleBan(User $user)
    {
        $user->is_banned = !$user->is_banned;
        $user->save();
        
        $status = $user->is_banned ? 'diblokir' : 'dibuka blokirnya';
        return back()->with('success', "User berhasil $status.");
    }

    public function togglePremium(User $user)
    {
        $user->is_premium = !$user->is_premium;
        $user->save();
        
        $status = $user->is_premium ? 'diupgrade ke Premium' : 'didowngrade ke Free';
        return back()->with('success', "User berhasil $status.");
    }
}
