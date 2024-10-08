<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Publication;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use GeminiAPI\Laravel\Facades\Gemini;
use Exception;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $user_id = $faker->numberBetween(1, 10);
            $publication_id = $faker->numberBetween(1, 10);

            $publication = Publication::find($publication_id);

            if ($publication) {
                $commentPrompt = "Generate a {$faker->randomElement(['positive', 'negative'])} comment with no markdown text decoration like a real social media comment for the following publication: '{$publication->content}'. The comment should be engaging and relevant to the publication's content: '{$publication->content}'.";

                try {
                    // Generate the comment using the Gemini API
                    $generatedComment = Gemini::generateText($commentPrompt);

                    // Check if the generated comment is valid (non-empty)
                    if (!empty($generatedComment)) {
                        $cleanedComment = preg_replace('/^"|"$/m', '', $generatedComment);

                        if (!empty(trim($cleanedComment))) {
                            Comment::create([
                                'user_id' => $user_id,
                                'publication_id' => $publication_id,
                                'content' => trim($cleanedComment),
                            ]);
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } catch (Exception $error) {
                    // Check if the error message contains the 429 status code
                    if (strpos($error->getMessage(), '429') !== false) {
                        // Exit the script on 429 error
                        exit('Gemini API: Too many requests - 429 error. Stopping seeder.');
                    } else {
                        continue;
                    }
                }
            }

            // update classification score after adding a comment
            $publication->updateClassificationScore();
        }
    }
}
