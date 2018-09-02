<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    protected $with = ['user', 'targetReply'];
    protected $guarded = [];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 回复了哪条评论
    public function targetReply()
    {
        return $this->belongsTo(Reply::class, 'reply_id', 'id');
    }

    // 所有回复该评论或该评论的子评论的评论
    public function offspringReplies()
    {
        return $this->hasMany(Reply::class, 'root_reply_id', 'id')
            ->where('id', '!=', $this->id); // 子评论里不包含当前评论，因为当前评论作为根评论时，root_reply_id是自身id
    }
}
