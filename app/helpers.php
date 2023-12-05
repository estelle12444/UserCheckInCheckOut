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

    private static function calculateTotalSeconds($start, $end, $id)
    {
        return DB::table('history_entries')
            ->select('employee_id', 'day_at_in')
            ->selectRaw('SUM(TIME_TO_SEC(TIMEDIFF(CONCAT(day_at_out, " ", time_at_out), CONCAT(day_at_in, " ", time_at_in)))) AS total_seconds')
            ->when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween('day_at_in', [$start, $end]);
            })
            ->where('employee_id', $id)
            ->whereNotNull(['time_at_in', 'time_at_out'])
            ->groupBy('employee_id', 'day_at_in')
            ->get()
            ->pluck('total_seconds');
    }

    /// Fonctions affichantes les élements par jour /////////////

    ///// Panier de flexibilité journalier
    public static function getTimeFlexParJour($id, $day)
    {
        $results=self::calculateTotalSeconds($day, $day, $id)->map(function ($history) {
            return ceil(($history - 32400) / 3600);
        });
        return $results->sum().'h';
    }
    // Heure Total journalier

    public static function getHeuresEmployesParJour(int $employeId, string $jour)
    {
        $regularHeure = 1;
        $overtime = 0;

        $pointages = HistoryEntry::where('employee_id', $employeId)
            ->whereDate('day_at_in', $jour)->get();

        $overtime = $pointages->sum(function ($pointage) {
            return Carbon::parse($pointage->day_at_in . " " . $pointage->time_at_in)
                ->diffInHours(Carbon::parse($pointage->day_at_out . " " . $pointage->time_at_out));
        }) - $regularHeure;

        return $overtime .'h';
    }
    public static function calculateTimeDifferenceAndOvertime($start, $end)
    {
        $difference = Carbon::parse($start)->diffInHours(Carbon::parse($end));

        $regularHeure = 8;
        $overtime = ceil($difference - $regularHeure);

        return [
            'difference' => $difference ,
            'overtime' => $overtime ,
        ];
    }


    /// Fonctions affichantes les élements pour une periode /////////////

    // Heure Total Pour une periode donnée

    public static function getTimeDifferenceParPeriode($start, $end, $id)
    {
        $results = self::calculateTotalSeconds($start, $end, $id)->map(function ($history) {
            return ceil(($history - 3600) / 3600);
        });

        return $results->sum().'h';
    }
// Heure Total
    public static function getTimeDifferenceTotal( $id)
    {
        $results = self::calculateTotalSeconds(null,null, $id)->map(function ($history) {
            return ceil(($history - 3600) / 3600);
        });

        return $results->sum().'h';
    }

    ///// Panier de flexibilité journalier
    public static function getTimeFlexParPeriode($start, $end, $id)
    {
         $results =self::calculateTotalSeconds($start, $end, $id)->map(function ($history) {
            return ceil(($history - 32400) / 3600);
        });
        return $results->sum().'h';
    }

    ///// Cumul du Panier de flexibilite
    public static function totalHeureFlex($id)
    {
        $results = self::calculateTotalSeconds(null, null, $id)->map(function ($history) {
            return ceil(($history - 32400) / 3600);
        });

        return $results->sum().'h';
    }

    public static function totalHeureFlexPeride($start, $end,$id)
    {
        $results = self::calculateTotalSeconds($start, $end, $id)->map(function ($history) {
            return ceil(($history - 32400) / 3600);
        });

        return $results->sum().'h';
    }

    ///// Nombre d'employée sur chaque site pour une periode
    public static function getNombres($periodeDate)
    {
        return $periodeDate->groupBy('localisation_id')->map(function ($entries) {
            return $entries->groupBy('day_at_in')->map(fn ($el) => $el->unique('employee_id')->count());
        });
    }

    /// Duree moyenne des heures des employée pour une periode

    public static function getAverageHoursDuration($start, $end)
    {
        $totalSeconds = DB::table('history_entries')
            ->select('employee_id', DB::raw('SUM(TIMESTAMPDIFF(SECOND, CONCAT(day_at_in, " ", time_at_in), CONCAT(day_at_out, " ", time_at_out))) AS total_seconds'))
            ->whereBetween('day_at_in', [$start, $end])
            ->whereNotNull(['time_at_in', 'time_at_out'])
            ->groupBy('employee_id')
            ->get()
            ->avg('total_seconds');

        return ceil($totalSeconds / 3600) .'h';
    }
}
