<?php

namespace Database\Seeders;

use App\Models\Publication;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $newPublication = new Publication([
                'user_id' => $faker->numberBetween(1, 10),
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'type' => $faker->randomElement(['article', 'image', 'video', 'link']),
            ]);
            $newPublication->save();
        }
    }
}
