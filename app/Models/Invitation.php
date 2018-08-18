<?php

namespace App\Models;

class Invitation extends Model
{
    protected $fillable = ['user_id', 'invited_user_id', 'type'];

    public function scopeToReview($query) {
        return $query->where('type', 'review');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function invitedUser(){
        return $this->belongsTo(User::class);
    }
}
