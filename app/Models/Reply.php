<?php

namespace App\Models;

class Reply extends Model
{
    protected $with = ['user', 'targetReply'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 回复了哪条评论
    public function targetReply(){
        return $this->belongsTo(Reply::class, 'reply_id' ,'id');
    }
}
