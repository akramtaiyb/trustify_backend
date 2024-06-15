<?php

namespace Database\Seeders;

use App\Models\Vote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $userCount = 10;
        $publicationCount = 10;

        for ($i = 0; $i < 100; $i++) {
            $number_of_votes = $faker->numberBetween(10, 100);

            for ($j = 0; $j < $number_of_votes; $j++) {
                Vote::create([
                    'user_id' => $faker->numberBetween(1, $userCount),
                    'publication_id' => $faker->numberBetween(1, $publicationCount),
                    'vote' => $faker->randomElement(['real', 'fake']),
                ]);
            }
        }
    }
}
