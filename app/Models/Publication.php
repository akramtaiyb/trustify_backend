<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "title", "content", "created_at", "updated_at"];

    protected $appends = ["has_upvoted", "has_downvoted", "has_commented", "user_vote"];

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
}
