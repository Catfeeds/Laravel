<?php

namespace App\Models;

class Work extends Model
{
    protected $with = ['user'];
    protected $casts = [
        'photo_urls' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublic($query) {
        return $query->where('visible_range', 'public');
    }
}
