<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "title", "content", "created_at", "updated_at", "classification_score"];

    protected $appends = ["has_upvoted", "has_downvoted", "has_commented", "user_vote", "classification"];

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

    // Classification

    public function getClassificationAttribute()
    {
        $this->loadMissing('votes');

        $score = $this->classification_score;

        $real_threshold = 50;
        $fake_threshold = -50;

        if ($score > $real_threshold) {
            return "real";
        } elseif ($score < $fake_threshold) {
            return "fake";
        } else {
            return "neutral";
        }
    }

    public function updateClassificationScore()
    {
        $this->loadMissing('votes', 'comments');

        $users_comment_weight = 1;
        $experts_comment_weight = 3;
        $regular_vote_weight = 5;
        $expert_vote_weight = 20;

        $score = $this->comments->sum(function ($comment) use ($experts_comment_weight, $users_comment_weight) {
            return $comment->user->is_expert ? $experts_comment_weight : $users_comment_weight;
        });

        foreach ($this->votes as $vote) {
            if ($vote->vote === 'real') {
                $score += $vote->user->is_expert ? $expert_vote_weight : $regular_vote_weight;
            } elseif ($vote->vote === 'fake') {
                $score += $vote->user->is_expert ? -$expert_vote_weight : -$regular_vote_weight;
            }
        }

        $this->classification_score = $score;
        $this->save();
    }
}
