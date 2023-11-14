<?php

namespace App\Http\Controllers;

use App\Models\HistoryEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $sortedEntries= HistoryEntry::all();
        $entryHoursByEmployee = $this->getEntryHoursByEmployee($sortedEntries);

        // Récupérer les heures de sortie par employé
        $exitHoursByEmployee = $this->getExitHoursByEmployee($sortedEntries);
        return view('index',compact('entryHoursByEmployee', 'exitHoursByEmployee'));
    }

    private function getEntryHoursByEmployee($sortedEntries)
    {
        $entryHoursByEmployee = [];

        foreach ($sortedEntries as $entry) {
            if ($entry->in_out == 1) {
                $employeeId = $entry->employee_id;
                $entryTime = Carbon::parse($entry->created_at)->format('Y-m-d H:i:s');
                $entryHoursByEmployee[$employeeId][] = $entryTime;
            }
        }

        return $entryHoursByEmployee;
    }


    private function getExitHoursByEmployee($sortedEntries)
    {
        $exitHoursByEmployee = [];

        foreach ($sortedEntries as $entry) {
            if ($entry->in_out == 0) {
                $employeeId = $entry->employee_id;
                $exitTime = Carbon::parse($entry->created_at)->format('Y-m-d H:i:s');
                $exitHoursByEmployee[$employeeId][] = $exitTime;
            }
        }

        return $exitHoursByEmployee;
    }
}
