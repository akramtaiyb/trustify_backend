<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'publication_id', 'vote'];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }
}
