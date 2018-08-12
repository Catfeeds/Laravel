<?php

namespace App\Models;

class Work extends Model
{
    protected $casts = [
        'photo_urls' => 'array'
    ];

    public function scopePublic($query) {
        return $query->where('visible_range', 'public');
    }
}
