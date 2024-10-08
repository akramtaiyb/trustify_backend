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
            'name' => 'Test Account',
            'username' => 'test_account',
            'email' => 'test_account@trustify.com',
            'password' => Hash::make('password'),
            'reputation' => 2000,
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
