<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\ActivityReplied;

class ReplyObserver
{
    // 每有一条评论创建成功后，动态评论数+1，通知用户
    public function created(Reply $reply)
    {
        $activity = $reply->activity;
        $activity->increment('reply_count');

        // 如果评论的作者不是动态的作者，需要通知
        if (!$reply->user->isAuthorOf($activity)) {
            $activity->user->notify(new ActivityReplied($reply));
        }
    }
}
