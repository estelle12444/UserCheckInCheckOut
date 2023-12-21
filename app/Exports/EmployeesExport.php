<?php

namespace App\Exports;

use App\Helper;
use App\Models\Employee;
use App\Models\HistoryEntry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromQuery
{
    private $id;
    private $dateRange;

    public function __construct($id, $dateRange)
    {
        $this->id = (int) $id;
        $this->dateRange = $dateRange;
    }

    public function query()
    {
        return HistoryEntry::where('employee_id', $this->id)
            ->whereBetween('day_at_in', [$this->dateRange['start'], $this->dateRange['end']])
            ->orderByDesc('day_at_in');
    }
    public function map($entry): array
    {
        dd($entry->groupBy('day_at_in'));
        return  [];
    }
    // public function Headings(): array
    // {
    //     return [
    //         'Nom',
    //         'Departement',
    //         'Date',
    //         'Entrée',
    //         'Sortie',
    //         'Heure de travail',
    //         'flexibilite'
    //     ];
    // }
}
