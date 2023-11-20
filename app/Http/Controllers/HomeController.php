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
    public function index()
    {
        $today = now()->today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyData = Helper::getWeeklyData($startOfWeek, $endOfWeek);
        $nombres = Helper::getNombres($weeklyData);
        $weeklyEntries = Helper::getWeeklyEntries($weeklyData);

        $countEntries = Helper::getCountEntries($startOfWeek, $endOfWeek);
        $nombreSites = Helper::getNombreSites('localisation');
        $totalHeures = Helper::getTotalHeures($startOfWeek, $endOfWeek);
        $moyenneHeuresEntree = Helper::getMoyenneHeuresEntree($startOfWeek, $endOfWeek);



        return view('index', compact('nombres', 'weeklyEntries', 'nombreSites', 'countEntries', 'totalHeures', 'moyenneHeuresEntree'));
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

        // Si l'ID de l'employé est vide ou s'il n'y a pas de chemin d'accès à l'image, renvoyer le chemin de l'image par défaut
        return asset('images/default.jpg');
    }
}
