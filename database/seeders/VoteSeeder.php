<?php

namespace Database\Seeders;

use App\Models\Publication;
use App\Models\User;
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

        $users = User::all();
        $publications = Publication::all();

        Vote::truncate();

        foreach ($publications as $publication) {
            $number_of_votes = $faker->numberBetween(10, 100);

            $votedUsers = [];

            for ($j = 0; $j < $number_of_votes; $j++) {
                do {
                    $userId = $faker->randomElement($users)->id;
                } while (in_array($userId, $votedUsers));

                $votedUsers[] = $userId;

                Vote::create([
                    'user_id' => $userId,
                    'publication_id' => $publication->id,
                    'vote' => $faker->randomElement(['real', 'fake']),
                ]);
            }
        }
    }
}
