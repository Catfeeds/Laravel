<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

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

    // 回复了哪个用户
    public function replyee(){
        return $this->belongsTo(User::class, 'replied_user_id' ,'id');
    }
}
