<?php

namespace App\Models;

class ProjectRemittance extends Model
{
    protected $guarded = [];
    protected $casts = [
        'info' => 'array'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
