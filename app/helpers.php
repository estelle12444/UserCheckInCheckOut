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

    public static function calculateTimeDifference($startOfWeek, $endOfWeek, $id)
    {
        $result = DB::table('history_entries')
            ->select('employee_id', 'day_at_in')
            ->selectRaw('SUM(TIME_TO_SEC(TIMEDIFF( CONCAT(day_at_out, " ", time_at_out),CONCAT(day_at_in, " ", time_at_in)))) AS total_seconds')
            ->whereBetween('day_at_in', [$startOfWeek, $endOfWeek])
            ->where('employee_id', $id)
            ->whereNotNull(['time_at_in', 'time_at_out'])
            ->groupBy('employee_id', 'day_at_in')
            ->get();

        $results = $result->map(fn ($history) => $history->total_seconds - 3600);

        $totalSeconds = 0;

        foreach ($results as $entry) {
            $totalSeconds += $entry;
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return $hours . 'h:' . $minutes . 'm:' . $seconds . 's';
    }

    public static function calculateTimeSupp($startOfWeek, $endOfWeek, $id)
    {
        $result = DB::table('history_entries')
            ->select('employee_id', 'day_at_in')
            ->selectRaw('SUM(TIME_TO_SEC(TIMEDIFF( CONCAT(day_at_out, " ", time_at_out),CONCAT(day_at_in, " ", time_at_in)))) AS total_seconds')
            ->whereBetween('day_at_in', [$startOfWeek, $endOfWeek])
            ->where('employee_id', $id)
            ->whereNotNull(['time_at_in', 'time_at_out'])
            ->groupBy('employee_id', 'day_at_in')
            ->get();

        $totalSeconds = 0;
        $overtime = 0;
        $regularSecond =32400 ;
        $results = $result->map(fn ($history) => $history->total_seconds - 3600);

        foreach ($results as $entry) {
            $totalSeconds += $entry;
            $overtime = max($totalSeconds - $regularSecond, 0);
        }
        $hours =floor( $overtime / 3600);
        $minutes = floor(($overtime  % 3600) / 60);
        $seconds = $overtime  % 60;

        return $hours . 'h:' . $minutes . 'm:' . $seconds . 's';
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


        $totalSeconds = $totalHeures->avg('total_seconds');


        $hours = floor($totalSeconds / 3600);

        $minutes = floor(($totalSeconds % 3600) / 60);

        $seconds = $totalSeconds % 60;

        return $hours . 'h:' . $minutes . 'm:' . $seconds;
    }
}
