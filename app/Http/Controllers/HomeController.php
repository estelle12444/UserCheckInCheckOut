<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Exports\HistoryEntryExport;
use App\Models\HistoryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Maatwebsite\Excel\Facades\Excel;
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

        return view('index', compact('dateRange','employeeCountBySite', 'lastentriesAndExits', 'nombres', 'weeklyEntries', 'nombreSites', 'countEntries', 'averageHoursDuration'));
    }



    public function siteEmployees($id, Request $request)
    {
        $dateRange = $this->dateRangeFromRequest($request->selectedDates);

        $history_entries = HistoryEntry::where('localisation_id', $id)
            ->whereBetween('day_at_in', [$dateRange['start'],  $dateRange['end']])
            ->orderBy('day_at_in', 'desc')
            ->get();

        $site = Helper::searchByNameAndId('localisation', $id);

        $filtreEmployees = Employee::whereHas('historyEntries', function ($query) use ($id, $dateRange) {
            $query->where('localisation_id', $id)->whereBetween('day_at_in', [$dateRange['start'],  $dateRange['end']]);
        })->count();

        return view('pages.siteEmployees', compact('history_entries', 'filtreEmployees', 'site','dateRange', 'id'));
    }


    public function exportSite($id, Request $request)
    {
        $dateRange = parse_url(url()->previous(), PHP_URL_QUERY);

        preg_match('/.*(\d{4}-\d{2}-\d{2}).*(\d{4}-\d{2}-\d{2})/', $dateRange, $matches,PREG_UNMATCHED_AS_NULL);

        if(empty($matches)){
            $dateRange = $this->dateRangeFromRequest([]);
        }else{
            $dateRange =  [
                'start' => Carbon::parse($matches[1]),
                'end' => Carbon::parse($matches[2])
            ];
        }

        $site = Helper::searchByNameAndId('localisation', $id);

        return Excel::download(new HistoryEntryExport($id,$dateRange), "employee_data_$site->name.xlsx");
    }



    public function exportEmployee($id, Request $request)
    {
        $dateRange = parse_url(url()->previous(), PHP_URL_QUERY);

        preg_match('/.*(\d{4}-\d{2}-\d{2}).*(\d{4}-\d{2}-\d{2})/', $dateRange, $matches,PREG_UNMATCHED_AS_NULL);

        if(empty($matches)){
            $dateRange = $this->dateRangeFromRequest([]);
        }else{
            $dateRange =  [
                'start' => Carbon::parse($matches[1]),
                'end' => Carbon::parse($matches[2])
            ];
        }
        $site = Helper::searchByNameAndId('localisation', $id);

        return Excel::download(new EmployeesExport($id,$dateRange), "employee_data_$site->name.xlsx");
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
        $statData = [];
        $jours = $employee->historyEntries->whereBetween('day_at_in', [$dateRange['start'], $dateRange['end']])
            ->pluck('day_at_in')->unique()->toArray();

        foreach ($jours as $jour) {
            $statData[$jour] = Helper::getHeuresEmployesParJour($employee->id, $jour);
        }

        return view('pages.employeeDetail', compact('employee', 'statData', 'groupedHistoryEntries','dateRange'));
    }



    public function Userlist()
    {
        return view('pages.user-list');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
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
