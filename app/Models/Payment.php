<?php

namespace App\Models;

class Payment extends Model
{
    protected $guarded = [];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    // 支付给哪个设计师
    public function user() {
        return $this->belongsTo(User::class);
    }
}
