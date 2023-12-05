<?php

namespace App\Http\Controllers;

use App\Models\HistoryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use RuntimeException;

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


    public function dateRangeFromRequest($selectedDates)
    {
        if (!empty($selectedDates)) {
            if (str_contains($selectedDates, "to")) {
                $dateArray = explode("to", $selectedDates);

                $start = Carbon::parse(trim($dateArray[0]));
                $end = Carbon::parse(trim($dateArray[1]));
            } else {
                $start = Carbon::parse($selectedDates)->startOfWeek();
                $end = Carbon::parse($selectedDates)->endOfWeek();
            }
        } else {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        }

        return compact('start', 'end');
    }


    public function index(Request $request)
    {
        $dateRange = $this->dateRangeFromRequest($request->selectedDates);

        $periodeDate = HistoryEntry::whereBetween('day_at_in', [$dateRange['start'], $dateRange['end']])->get();
        $countEntries = $periodeDate->count();

        $weeklyEntries = $periodeDate->groupBy(fn ($entry) => Carbon::parse($entry->day_at_in)->startOfWeek()->format('W'));

        $nombres = Helper::getNombres($periodeDate);

        $nombreSites = count(Config::get('localisation'));


        $averageHoursDuration = Helper::getAverageHoursDuration($dateRange['start'], $dateRange['end']);


        $lastentriesAndExits = HistoryEntry::with('employee')
            ->whereBetween('day_at_in', [$dateRange['start'], $dateRange['end']])
            ->latest('day_at_in')
            ->take(10)
            ->get();

        // Nombre d'employée sur chaque site pour le jour actuel

        $employeeCountBySite = HistoryEntry::whereDate('day_at_in', Carbon::now())
            ->get(['localisation_id', 'day_at_in', 'employee_id'])
            ->groupBy('localisation_id');

        return view('index', compact('employeeCountBySite', 'lastentriesAndExits', 'nombres', 'weeklyEntries', 'nombreSites', 'countEntries', 'averageHoursDuration'));
    }



    public function siteEmployees($id, Request $request)
    {
        $dateRange = $this->dateRangeFromRequest($request->selectedDates);

        $history_entries = HistoryEntry::where('localisation_id', $id)
            ->whereBetween('day_at_in', [$dateRange['start'],  $dateRange['end']])
            ->orderBy('time_at_in', 'desc')
            ->get();

        $site = Helper::searchByNameAndId('localisation', $id);

        $filtreEmployees = Employee::whereHas('historyEntries', function ($query) use ($id, $dateRange) {
            $query->where('localisation_id', $id)->whereBetween('day_at_in', [$dateRange['start'],  $dateRange['end']]);
        })->count();

        return view('pages.siteEmployees', compact('history_entries', 'filtreEmployees', 'site'));
    }


    public function employeeDetail(Request $request, $id)
    {
        $dateRange = $this->dateRangeFromRequest($request->selectedDates);

        $employee = Employee::findOrFail($id);
        $groupedHistoryEntries = HistoryEntry::where('employee_id', $id)
            ->whereBetween('day_at_in', [$dateRange['start'], $dateRange['end']])
            ->orderByDesc('day_at_in')
            ->paginate(4)
            ->withQueryString();

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

        return view('pages.employeeDetail', compact('employee', 'result', 'weekdays', 'groupedHistoryEntries', 'dateRange'));
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
