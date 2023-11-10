<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Création d'un utilisateur administrateur
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);

        // Création de plusieurs utilisateurs fictifs
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role_id' => 2,
                'password' => Hash::make('password'),
            ]);
        }

    }
}
