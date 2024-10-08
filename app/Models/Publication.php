<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "title", "content", "created_at", "updated_at", "classification_score"];

    protected $appends = ["has_upvoted", "has_downvoted", "has_commented", "user_vote", "classification"];

    // Consts
    const MINIMUM_VOTES = 50; // Total votes count required to classify a publication

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }

    // Votes
    public function getHasUpvotedAttribute()
    {
        $userId = \auth()->id();

        if (!$userId) {
            return false;
        } else {
            return Vote::query()->where('publication_id', $this->id)
                ->where('user_id', $userId)
                ->where('vote', 'real')
                ->exists();
        }
    }

    public function getHasDownvotedAttribute()
    {
        $userId = \auth()->id();

        if (!$userId) {
            return false;
        } else {
            return Vote::query()->where('publication_id', $this->id)
                ->where('user_id', $userId)
                ->where('vote', 'fake')
                ->exists();
        }
    }

    public function getHasCommentedAttribute()
    {
        $userId = \auth()->id();

        if (!$userId) {
            return false;
        } else {
            return Comment::query()->where('publication_id', $this->id)
                ->where('user_id', $userId)
                ->exists();
        }
    }

    public function getUserVoteAttribute()
    {
        return $this->votes()->where('user_id', auth()->id())->value('id');
    }

    // Classification based on score and thresholds
    public function getClassificationAttribute()
    {
        $this->loadMissing('votes');

        $score = $this->classification_score;

        $real_threshold = 100;  // Adjusted based on score calculations
        $fake_threshold = -100;

        if ($score > $real_threshold) {
            return "real";
        } elseif ($score < $fake_threshold) {
            return "fake";
        } else {
            return "neutral";
        }
    }

    // FIXME
    // Update the classification score based on votes and comments
    public function updateClassificationScore()
    {
        $this->loadMissing('votes', 'comments');

        // Total votes count
        $total_votes_count = count($this->votes);

        // Weights for comments and votes
        $users_comment_weight = 1;
        $experts_comment_weight = 3;
        $regular_vote_weight = 5;
        $expert_vote_weight = 20;

        // Initialize scores
        $comment_score = 0;
        $vote_score = 0;
        $max_comment_score = 0;
        $max_vote_score = 0;

        if ($total_votes_count > self::MINIMUM_VOTES) {
            // Calculate sentiment-based comment score
            foreach ($this->comments as $comment) {
                $comment_weight = $comment->user->is_expert ? $experts_comment_weight : $users_comment_weight;
                $sentiment = $comment->analyzeCommentSentiment();
                $comment_score += $comment_weight * $sentiment;
                $max_comment_score += $comment_weight * 1; // Max sentiment is +1
            }

            // Normalize comment score
            if ($max_comment_score > 0) {
                $comment_score = ($comment_score + $max_comment_score) / (2 * $max_comment_score);
            } else {
                $comment_score = 0; // No comments case
            }

            // Calculate vote score
            foreach ($this->votes as $vote) {
                $vote_weight = $vote->user->is_expert ? $expert_vote_weight : $regular_vote_weight;
                $max_vote_score += $vote_weight;

                if ($vote->vote === 'real') {
                    $vote_score += $vote_weight;
                } elseif ($vote->vote === 'fake') {
                    $vote_score -= $vote_weight;
                }
            }

            // Normalize vote score
            if ($max_vote_score > 0) {
                $vote_score = ($vote_score + $max_vote_score) / (2 * $max_vote_score);
            } else {
                $vote_score = 0; // No votes case
            }

            // Determine the total weights
            $total_weight = (count($this->comments) > 0 ? 0.5 : 0) + (count($this->votes) > 0 ? 0.5 : 0);

            // Combine scores, avoiding division by zero
            if ($total_weight > 0) {
                $final_score = ($comment_score + $vote_score) / $total_weight;
            } else {
                $final_score = 0.5; // Default neutral score if no votes or comments
            }

            // Set final score within [0, 1]
            $this->classification_score = round(max(0, min(1, $final_score)), 2);

            // Save updated score
            $this->save();
        } else {
            $this->classification_score = 0.5; // Under investigations 
            $this->save();
        }
    }
}
