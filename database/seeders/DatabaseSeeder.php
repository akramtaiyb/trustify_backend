<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Publication;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ExpertSeeder::class,
            PublicationSeeder::class,
            VoteSeeder::class,
            CommentSeeder::class,
        ]);

        foreach (Publication::all() as $publication) {
            $publication->updateClassificationScore();
        }
    }
}
