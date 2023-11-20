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

        $name = [
            ' Bill Gates',
            'Elon Musk ',
            'Alhassan Ouattera ',
            ' Sali Ouattera',
            'Yaya Sylla',
            'Imtiaz Naseem',
            'Nadeem Khan',
            'Hanif Khan',
            'Moaz Anjum ',
            'Christ Marie Brou',
            'Muhammad Awais Amin',
            'Abdul Samad Bin Shahid '

        ];

        $designation = [
            'President Microsoft',
            'CEO, Tesla',
            'President CI',
            ' Dy CEO',
            'CEO, SaH',
            'Solution Architect',
            'BI Expert',
            'Lead Data Engineer',
            'Head of Infra',
            'Jr Data Scientist',
            'Data Scientist',
            'BI Engineer'
        ];


        foreach ($matricules as $key => $matricule) {
            Employee::create([
                'name' => $name[$key],
                'matricule' => $matricule,
                'designation' => $designation[$key],
                'department_id' => rand(1, 10),
                'user_id' => rand(1, 2),
                'image_path' => 'photos/' . $matricule . '.jpg'
            ]);
        }
    }
}
