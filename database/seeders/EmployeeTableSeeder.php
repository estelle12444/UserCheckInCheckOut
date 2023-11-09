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
        $matricules = [
            '00001',
            '00002',
            '00225',
            '01130',
            '01131',
            '01132',
            '01133',
            '01134',
            '01135',
            '01136',
            '01137',
            '01138',
        ];

        foreach ($matricules as $value) {
            Employee::create([
                'name' => fake()->name(),
                'matricule' =>  $value,
                'designation' => fake()->jobTitle(),
                'department_id' => rand(1, 10),
                'user_id'=> rand(1, 2)
            ]);
        }
    }
}
