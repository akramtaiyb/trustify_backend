<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\CommentObserver;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use GeminiAPI\Laravel\Facades\Gemini;

#[ObservedBy([CommentObserver::class])]
class Comment extends Model
{
    use HasFactory;

    // Ensure the sentiment field is fillable
    protected $fillable = ["user_id", "publication_id", "content", "sentiment"];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Sentiment Analysis method
    public function analyzeCommentSentiment(bool $seeding = True)
    {
        if ($seeding) {
            $prompt = "Analyze the sentiment of the following comment in plain text (no markdown) and respond with only one word: 'positive', 'negative', or 'neutral'. No explanations needed, just the sentiment.\n\nHere’s the comment: {$this->content}";

            $response = null;
            $attempts = 0;
            $maxAttempts = 3;

            do {
                try {
                    // Make API request to Gemini
                    $response = Gemini::generateText($prompt);
                } catch (Exception $error) {
                    $attempts++;
                    if ($attempts >= $maxAttempts) {
                        throw new Exception("Failed to get a valid response after {$maxAttempts} attempts: " . $error->getMessage());
                    }
                }
            } while (!$response && $attempts < $maxAttempts);

            if ($response) {
                $cleanedResponse = trim($response);

                return match (strtolower($cleanedResponse)) {
                    'positive' => 1,
                    'negative' => -1,
                    'neutral' => 0,
                    default => 0,
                };
            }

            return 0;
        } else {
            // call to api
            return 0;
        }
    }
}
