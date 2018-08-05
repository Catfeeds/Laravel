<?php

namespace App\Models;

class Reply extends Model
{
    protected $with = ['user', 'replyee'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replyee(){
        return $this->belongsTo(User::class);
    }
}
