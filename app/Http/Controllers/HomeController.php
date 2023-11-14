<?php

namespace App\Http\Controllers;

use App\Enums\Entry;
use App\Models\HistoryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper;
use App\Models\Employee;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Exception;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $history_entries = HistoryEntry::all();
        $employees = Employee::whereIn('id', $history_entries->pluck('employee_id')->unique())->get();
        

    return view('index',compact('history_entries','employees'));
    }



    public function tableSite($id)
    {
        $history_entries = HistoryEntry::where('localisation_id', $id)->get();

        $site = Helper::searchByNameAndId('localisation', $id);
        $employees = Employee::whereIn('id', $history_entries->pluck('employee_id')->unique())->get();

        $employeeCount = $employees->count();

        return view('pages.table-site', compact('history_entries', 'site','employees','employeeCount'));
    }



    public function Userlist()
    {
        return view('pages.user-list');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return view('auth.login');
    }

    public function getEmail()
    {
        if (!Auth::check()) {

            return view('auth.login');
        } else {
            $user = Auth::user();
            $email = $user->email;

            return $email;
        }
    }
    public function geUsername()
    {
        if (!Auth::check()) {

            return view('auth.login');
        } else {
            $user = Auth::user();
            $name = $user->name;

            return $name;
        }
    }
}
