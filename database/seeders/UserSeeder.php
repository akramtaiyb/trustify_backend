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
            'name' => 'Craig Silverman',
            'username' => 'craig_silverman',
            'email' => 'craigsilverman@trustify.com',
            'password' => Hash::make('letsfightfakenews2024'),
            'reputation' => 1000,
            'is_expert' => true,
        ]);

        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            $firstname = $faker->firstName;
            $lastname = $faker->lastName;
            $reputation = $faker->numberBetween(0, 1500);
            User::create([
                'name' => $firstname . ' ' . $lastname,
                'username' => $firstname . $lastname,
                'email' => $firstname . $lastname . '@trustify.com',
                'password' => Hash::make('password'),
                'reputation' => $reputation,
                'is_expert' => $reputation >= 1000,
            ]);
        }
    }
}
