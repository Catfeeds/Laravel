<?php

namespace App\Models;

class RecommendedUser extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->hasOne(User::class);
    }
}
