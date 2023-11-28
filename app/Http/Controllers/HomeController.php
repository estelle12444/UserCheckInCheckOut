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

    public function index(Request $request)
    {
        if (!empty($request->selectedDates)) {
            $date_array = explode("to", $request->selectedDates);

            if (count($date_array) !== 2) {
                return redirect()->back()->with('Invalid date format in selectedDates');
            }

            try {
                $start = Carbon::parse(trim($date_array[0]));
                $end = Carbon::parse(trim($date_array[1]));
            } catch (\Exception $e) {

                throw new InvalidArgumentException("Error parsing dates: " . $e->getMessage());
            }
        } else {
            try {
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
            } catch (\Exception $e) {

                throw new RuntimeException("Error generating default dates: " . $e->getMessage());
            }
        }

        $periodeDate = HistoryEntry::whereBetween('day_at_in', [$start, $end])->get();
        $countEntries = $periodeDate->count();

        $weeklyEntries = $periodeDate->groupBy(fn ($entry) => Carbon::parse($entry->day_at_in)->startOfWeek()->format('W'));

        $nombres = Helper::getNombres($periodeDate);

        $nombreSites = count(Config::get('localisation'));


        $averageHoursDuration = Helper::getAverageHoursDuration($start, $end);


        $lastentriesAndExits = HistoryEntry::with('employee')
            ->whereBetween('day_at_in', [$start, $end])
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
        if (!empty($request->selectedDates)) {
            $date_array = explode("to", $request->selectedDates);

            if (count($date_array) !== 2) {
                return redirect()->back()->with('Invalid date format in selectedDates');
            }

            try {
                $start = Carbon::parse(trim($date_array[0]));
                $end = Carbon::parse(trim($date_array[1]));
            } catch (\Exception $e) {

                throw new InvalidArgumentException("Error parsing dates: " . $e->getMessage());
            }
        } else {
            try {
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
            } catch (\Exception $e) {

                throw new RuntimeException("Error generating default dates: " . $e->getMessage());
            }
        }

        $history_entries = HistoryEntry::where('localisation_id', $id)
            ->whereBetween('day_at_in', [$start,  $end])
            ->orderBy('time_at_in', 'desc')
            ->get();

        $site = Helper::searchByNameAndId('localisation', $id);

        $filtreEmployees = Employee::whereHas('historyEntries', function ($query) use ($id, $start,  $end) {
            $query->where('localisation_id', $id)->whereBetween('day_at_in', [$start,  $end]);
        })->count();

        return view('pages.siteEmployees', compact('history_entries', 'filtreEmployees', 'site'));
    }


    public function employeeDetail(Request $request, $id)
    {
        if (!empty($request->selectedDates)) {
            $date_array = explode("to", $request->selectedDates);

            if (count($date_array) !== 2) {
                return redirect()->back()->with('Invalid date format in selectedDates');
            }

            try {
                $start = Carbon::parse(trim($date_array[0]));
                $end = Carbon::parse(trim($date_array[1]));
            } catch (\Exception $e) {

                throw new InvalidArgumentException("Error parsing dates: " . $e->getMessage());
            }
        } else {
            try {
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
            } catch (\Exception $e) {

                throw new RuntimeException("Error generating default dates: " . $e->getMessage());
            }
        }

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
