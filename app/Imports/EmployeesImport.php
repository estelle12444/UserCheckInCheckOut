<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithBatchInserts
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        return new Employee([
            'matricule' => $row[0],
            'name' => $row[1],
            'designation' => $row[2],
            'department_id' => $row[3],
        ]);
    }

    const STRING_RULE = 'required|string|max:255';

    public function rules(): array
    {
        return [
            'matricule' => self::STRING_RULE,
            'name' => self::STRING_RULE,
            'designation' => self::STRING_RULE,
            'department_id' => 'required|int',
        ];
    }
    public function batchSize(): int
    {
        return 1000;
    }
}
