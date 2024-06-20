<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Akram Taiyb',
            'username' => 'akramtaiyb',
            'email' => 'akram@trustify.com',
            'password' => Hash::make('password'), // Use a constant password for simplicity
            'reputation' => 300,
        ]);

        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            $firstname = $faker->firstName;
            $lastname = $faker->lastName;
            User::create([
                'name' => $faker->name . ' ' . $lastname,
                'username' => $firstname . $lastname,
                'email' => $firstname . $lastname . '@trustify.com',
                'password' => Hash::make('password'), // Use a constant password for simplicity
                'reputation' => $faker->numberBetween(0, 500),
            ]);
        }
    }
}
