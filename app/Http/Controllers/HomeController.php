<?php

namespace App\Http\Controllers;

use App\Models\HistoryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper;
use App\Models\Employee;
use Carbon\Carbon;

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

    public function index(Request $request)
    {
        if (!empty($request->selectedDates)) {

            $date_array = explode("to", $request->selectedDates);
            $startOfWeek = Carbon::parse(trim($date_array[0]));
            $endOfWeek = Carbon::parse(trim($date_array[1]));
        } else {
            $startOfWeek =  Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
        }
        $weeklyData = Helper::getWeeklyData($startOfWeek, $endOfWeek);
        $nombres = Helper::getNombres($weeklyData);
        $weeklyEntries = Helper::getWeeklyEntries($weeklyData);

        $countEntries = Helper::getCountEntries($startOfWeek, $endOfWeek);
        $nombreSites = Helper::getNombreSites('localisation');
        $totalHeures = Helper::getTotalHeures($startOfWeek, $endOfWeek);

        $entriesAndExits = HistoryEntry::with('employee')
            ->orderBy('day_at_in', 'desc')
            ->orderBy('time_at_in', 'desc')
            ->take(10)
            ->get();


        $day_nombres = HistoryEntry::whereBetween('day_at_in', [$startOfWeek, $endOfWeek])
            ->get(['localisation_id', 'day_at_in', 'employee_id'])
            ->unique(['localisation_id', "day_at_in", "employee_id"])
            ->groupBy('localisation_id');



        return view('index', compact('day_nombres', 'entriesAndExits', 'nombres', 'weeklyEntries', 'nombreSites', 'countEntries', 'totalHeures'));
    }



    public function siteEmployees($id, Request $request)
    {
        if (!empty($request->selectedDates)) {
            $date_array = explode("to", $request->selectedDates);
            $startOfWeek = Carbon::parse(trim($date_array[0]));
            $endOfWeek = Carbon::parse(trim($date_array[1]));

            if (isset($endOfWeek) || isset($endOfWeek)) {
                $startOfWeek =  Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
            }
        } else {
            $startOfWeek =  Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
        }

        $history_entries = HistoryEntry::where('localisation_id', $id)->whereBetween('day_at_in', [$startOfWeek,  $endOfWeek])->orderBy('time_at_in', 'desc')->get();

        $site = Helper::searchByNameAndId('localisation', $id);

        $filtreEmployees = Employee::whereHas('historyEntries', function ($query) use ($id, $startOfWeek,  $endOfWeek) {
            $query->where('localisation_id', $id)->whereBetween('day_at_in', [$startOfWeek,  $endOfWeek]);
        })->count();

        return view('pages.siteEmployees', compact('history_entries', 'filtreEmployees', 'site'));
    }

    public function employeeDetail(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        if (!$employee) {
            abort(404);
        }
        $result = [];
        $jours = $employee->historyEntries->pluck('day_at_in')->unique()->toArray();
        $weekdays = [];
        foreach ($jours as $jour) {
            $temp = Helper::getHeuresEmployesParJour($employee->id, $jour);
            $date = Carbon::parse($jour);
            array_push($weekdays, ucfirst($date->dayName));
            $result[$date->isoWeekday()] = $temp;
        }

        return view('pages.employeeDetail', compact('employee', 'result', 'weekdays'));
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
    public function getPhoto()
    {
        $user = Auth::user();

        if ($user->employee_id) {
            $employee = $user->employee;
            $photoPath = $employee->image_path;

            if ($photoPath) {
                return asset($photoPath);
            }
        }


        return asset('images/default.png');
    }
}
