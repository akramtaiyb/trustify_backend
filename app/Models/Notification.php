<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'publication_id',
        'vote_id',
        'comment_id',
        'type',

        // For further scalability
        //'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
