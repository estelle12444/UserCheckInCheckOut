<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Userlist()
    {
        $users = User::all();
        $userCount = $users->count();
        return view('pages.user-list', compact('users', 'userCount'));
    }

    public function deactivateUser(User $user)
    {
        $user->activated = false;
        $user->save();
        return redirect()->back()->with('success', 'Utilisateur désactivé avec succès.');
    }

    public function activateUser(User $user)
    {
        $user->activated = true;
        $user->save();
        return redirect()->back()->with('success', 'Utilisateur activé avec succès.');
    }
}
