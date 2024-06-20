<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = ['publication_id', 'type', 'path'];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }
}

