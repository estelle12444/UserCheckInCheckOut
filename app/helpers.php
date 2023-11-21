<?php

namespace App;

use App\Enums\Entry;
use App\Models\HistoryEntry;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function searchByNameAndId(string $name, int $id)
    {
        $data = config($name);
        $key = array_search($id, array_column($data, 'id'));
        return (object) $data[$key];
    }

    public static function calculateTimeDifference($timeIn, $timeOut)
    {
        if ($timeIn !== null && $timeOut !== null) {
            $timeIn = Carbon::parse($timeIn);
            $timeOut = Carbon::parse($timeOut);
            $difference = Carbon::create($timeIn->diff($timeOut)->format('%H:%i:%s'));


            return $difference->format('H:i:s');
        }

        return null;
    }

    public static function calculateTimeSupp($timeIn, $timeOut)
    {
        if ($timeIn !== null && $timeOut !== null) {
            $timeIn = Carbon::parse($timeIn);
            $timeOut = Carbon::parse($timeOut);
            $supp = Carbon::parse($timeOut->diff($timeIn)->format('%H:%i:%s'))->subHour(9);
            return $supp->format('H:m:i');
        }
        return null;
    }

    public static function getHeuresEmployesParJour(int $employeId, string $jour): float
    {
        $heures = 0;

        $pointages = HistoryEntry::where('employee_id', $employeId)
            ->whereDate('day_at_in', $jour)->get();

        foreach ($pointages as $key => $pointage) {

            $heures += Carbon::parse($pointage->day_at_in . " " . $pointage->time_at_in)->diffInHours(Carbon::parse($pointage->day_at_out . " " . $pointage->time_at_out));
        }
        return $heures;
    }




    public static function getWeeklyData($startOfWeek, $endOfWeek)
    {
        $weeklyData = HistoryEntry::where('day_at_in', '>=', $startOfWeek)
            ->where('day_at_in', '<=', $endOfWeek)
            ->get();

        return $weeklyData;
    }

    public static function getNombres($weeklyData)
    {
        $nombres = $weeklyData->groupBy('localisation_id')->map(function ($entries) {
            return $entries->groupBy('day_at_in')->map(fn ($el) => $el->unique('employee_id')->count());
        });

        return $nombres;
    }

    public static function getWeeklyEntries($weeklyData)
    {
        $weeklyEntries =  $weeklyData->groupBy(function ($entry) {
            $timestamp = Carbon::parse($entry->day_at_in);
            return $timestamp->startOfWeek()->format('W');
        });

        return $weeklyEntries;
    }

    public static function getCountEntries($startOfWeek, $endOfWeek)
    {
        $countEntries = HistoryEntry::whereBetween('day_at_in', [$startOfWeek, $endOfWeek])
            ->whereNotNull('time_at_in')
            ->count();

        return $countEntries;
    }

    public static function getNombreSites(string $name)
    {
        $nombreSites = count(Config::get($name));

        return $nombreSites;
    }

    public static function getTotalHeures($startOfWeek, $endOfWeek)
    {
        $totalHeures = DB::table('history_entries')
            ->select('employee_id', DB::raw('SUM(TIMESTAMPDIFF(SECOND, CONCAT(day_at_in, " ", time_at_in), CONCAT(day_at_out, " ", time_at_out))) AS total_seconds'))
            ->whereBetween('day_at_in', [$startOfWeek, $endOfWeek])
            ->whereNotNull('time_at_in')
            ->whereNotNull('time_at_out')
            ->groupBy('employee_id')
            ->get();

        $totalHeures = $totalHeures->avg('total_seconds') / 3600;

        $hours = gmdate('H', $totalHeures);
        $minutes = gmdate('i', $totalHeures);
        $seconds = gmdate('s', $totalHeures);

        return $hours . 'h:' . $minutes . 'm:' . $seconds;
    }

    public static function getMoyenneHeuresEntree($startOfWeek, $endOfWeek)
    {
        $result = DB::table('history_entries')
            ->selectRaw('AVG(TIME_TO_SEC(time_at_in) / 3600) AS moyenne_heure_entree')
            ->whereBetween('day_at_in', [$startOfWeek, $endOfWeek])
            ->whereNotNull('time_at_in')
            ->first();

        if (!is_null($result->moyenne_heure_entree)) {
            $moyenneHeuresEntree = $result->moyenne_heure_entree;


            $hours = gmdate('H', $moyenneHeuresEntree);
            $minutes = gmdate('i', $moyenneHeuresEntree);
            $seconds = gmdate('s', $moyenneHeuresEntree);

            return $hours . 'h:' . $minutes . 'm:' . $seconds;
        }else{
            return null;
        }

    }
}
