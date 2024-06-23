<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    // Reputation
    public function updateReputation()
    {
        $real_publication_weight = 10;
        $comment_weight = 3;

        $publications = $this->publications;
        $realPublicationsCount = 0;

        foreach ($publications as $publication) {
            if ($publication->classification == "real") {
                $realPublicationsCount++;
            }
        }

        $commentsCount = $this->comments()->count();

        $this->reputation = ($realPublicationsCount * $real_publication_weight) + ($commentsCount * $comment_weight);

        $this->is_expert = $this->reputation >= 1000;

        $this->save();

        return 1;
    }

}
