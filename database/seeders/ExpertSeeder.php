<?php

namespace Database\Seeders;

use App\Models\Expert;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ExpertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $domains = ['Technology', 'Health', 'Science', 'Education', 'Finance'];

        for ($i = 0; $i < 5; $i++) {
            Expert::create([
                'user_id' => $i + 1,
                'domain_expertise' => $faker->randomElement($domains),
            ]);
        }
    }
}
