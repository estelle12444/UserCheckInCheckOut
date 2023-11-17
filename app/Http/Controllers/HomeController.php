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
        $date= now();
        $pointages = HistoryEntry::where('day_at_in', $date)->get();
        $employees = Employee::whereIn('id', $pointages->pluck('employee_id')->unique())->get();

        $nombres = $pointages->groupBy('localisation_id')->map(function ($entries) {
            return $entries->groupBy('day_at_in')->map(fn($el) => $el->unique('employee_id')->count());
        });

        return view('index', compact('pointages', 'employees','nombres'));
    }



    public function siteEmployees($id)
    {
        $history_entries = HistoryEntry::where('localisation_id', $id)->get();

        $site = Helper::searchByNameAndId('localisation', $id);
        $employees = Employee::whereIn('id', $history_entries->pluck('employee_id')->unique())->get();

        $employeeCount = $employees->count();

        return view('pages.siteEmployees', compact('history_entries', 'site', 'employees', 'employeeCount'));
    }

    public function employeeDetail(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        if (!$employee) {
            abort(404);
        }
        $result = [];
        $jours= $employee->historyEntries->pluck('day_at_in')->unique()->toArray();
        $weekdays = [];
        foreach ($jours as $jour){
            $temp = Helper::getHeuresEmployesParJour($employee->id, $jour);
            $date = Carbon::parse($jour);
            array_push($weekdays, ucfirst($date->dayName));
            $result[$date->isoWeekday()] = $temp;
        }

        return view('pages.employeeDetail', compact('employee','jour', 'result', 'weekdays'));
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
