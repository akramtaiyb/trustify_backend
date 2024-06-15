<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Comment::create([
                'user_id' => $faker->numberBetween(1, 10),
                'publication_id' => $faker->numberBetween(1, 10),
                'content' => $faker->sentence,
            ]);
        }
    }
}
