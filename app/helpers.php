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
        return (Object) $data[$key];
    }

    public static function calculateTimeDifference($timeIn, $timeOut)
    {
        if ($timeIn !== null && $timeOut !== null) {
            $timeIn = Carbon::parse($timeIn);
            $timeOut = Carbon::parse($timeOut);
            $difference = Carbon::parse($timeOut->diff($timeIn)->format('%H:%I:%S'))->subHour(1);
            return $difference->format('H:m:i');
        }

        return null;
    }

    public static function calculateTimeSupp($timeIn, $timeOut)
    {
        if ($timeIn !== null && $timeOut !== null) {
            $timeIn = Carbon::parse($timeIn);
            $timeOut = Carbon::parse($timeOut);
            $supp = Carbon::parse($timeOut->diff($timeIn)->format('%H:%I:%S'))->subHour(9);
            return $supp->format('H:m:i');
        }
        return null;
    }


}
