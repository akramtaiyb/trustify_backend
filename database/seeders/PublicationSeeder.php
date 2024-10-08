<?php

namespace Database\Seeders;

use App\Models\Publication;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use GeminiAPI\Laravel\Facades\Gemini;
use Http\Discovery\Exception;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            // Use GeminiAPI to generate social media-like content
            $prompt = "Generate a very unique social media post on any trending topic or event in a json format with the following fields: title (a short phrase to describe the publication's title) and content field (the publication's content).
            N.B: Avoid topics like LGBTQ+;
            ";

            do {
                try {
                    $response = Gemini::generateText($prompt);
                } catch (Exception $error) {
                    dump($error);
                }

                // Remove backticks or unwanted formatting
                $generatedPost = preg_replace('/^```json|```$/m', '', $response);
            } while (!json_decode($generatedPost));

            $title = json_decode($generatedPost, true)['title'];
            $content = json_decode($generatedPost, true)['content'];

            Publication::create([
                'user_id' => $faker->numberBetween(1, 10),
                'title' => $title,
                'content' => $content,
                'type' => $faker->randomElement(['article', 'image', 'video', 'link']), // Random type
            ]);
        }
    }
}
