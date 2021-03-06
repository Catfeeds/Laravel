<?php

namespace App\Models;

class ProjectRemittance extends Model
{
    protected $guarded = [];
    protected $casts = [
        'remitted_at' => 'datetime'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
