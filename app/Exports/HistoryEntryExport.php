<?php

namespace App\Exports;
use App\Helper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\HistoryEntry;
use Maatwebsite\Excel\Concerns\FromCollection;

class HistoryEntryExport implements FromQuery, WithMapping, WithHeadings
{
    private $localisationId;
    private $dateRange;

    public function __construct ($localisationId, $dateRange)
    {
        $this->localisationId = (int) $localisationId;
        $this->dateRange = $dateRange;
    }

    public function query()
    {
        return HistoryEntry::where('localisation_id', $this->localisationId)
            ->whereBetween('day_at_in', [$this->dateRange['start'], $this->dateRange['end']])
            ->orderBy('time_at_in', 'desc');

    }
    public function map($historyEntry): array
    {
        $department= Helper::searchByNameAndId('department', $historyEntry->employee->department_id)->name ?? '';
        $heureSortie=  $historyEntry->day_at_out && $historyEntry->time_at_out ? $historyEntry->time_at_out : 'Pas encore sorti';
        $dureeTravail= Helper::getHeuresEmployesParJour($historyEntry->employee->id, $historyEntry->day_at_in);
        $flexibilite= Helper::getTimeFlexParJour($historyEntry->employee->id, $historyEntry->day_at_in);

        return [
            $historyEntry->employee->name,
            $department,
            $historyEntry->day_at_in,
            $historyEntry->time_at_in,
            $heureSortie,
            $dureeTravail,
            $flexibilite
        ];
    }
    public function Headings():array
    {
        return[
            'Nom',
            'Departement',
            'Date',
            'Entrée',
            'Sortie',
            'Heure de travail',
            'flexibilite'
        ];

    }

}
