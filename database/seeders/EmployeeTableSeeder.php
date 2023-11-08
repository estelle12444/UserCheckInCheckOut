<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'name' => ' kossonou',
            'matricule' =>  uniqid(),
            'designation' => ' Developpeur web',
            'department_id' => 1
        ]);


        Employee::create([
            'name' => ' Bandama',
            'matricule' => uniqid(),
            'designation' => ' gestion de projet',
            'department_id' => 2
        ]);
    }
}
