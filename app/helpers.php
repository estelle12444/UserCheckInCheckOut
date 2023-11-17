<?php

namespace App;

use App\Enums\Entry;
use App\Models\HistoryEntry;
use Carbon\Carbon;
use DateInterval;

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

            $heures += Carbon::parse($pointage->day_at_in." ". $pointage->time_at_in)->diffInHours(Carbon::parse($pointage->day_at_out." ".$pointage->time_at_out));
        }
        return $heures;
    }

    public static function getNbreEmployesEntrantsParSite(string $site, string $date): int
    {
        $nombre = 0;

        $pointages = HistoryEntry::where('localisation_id', $site)
            ->whereDate('day_at_in', $date)
            ->whereNotNull('time_at_in')
            ->get();

        foreach ($pointages as $pointage) {
            $nombre++;
        }

        return $nombre;
    }


}
