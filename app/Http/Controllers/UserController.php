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
        return view('pages.user-list', ['users' => $users]);
    }
}
