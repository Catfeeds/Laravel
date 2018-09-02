<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\ActivityReplied;
use App\Notifications\ReplyReplied;

class ReplyObserver
{
    // 每有一条评论创建成功后，动态的评论数+1，通知用户
    public function created(Reply $reply)
    {
        $activity = $reply->activity;
        $activity->increment('reply_count');

        // 通知动态的作者
        if (!$reply->user->isAuthorOf($activity)) {
            $activity->user->notify(new ActivityReplied($reply));
        }
        // 有可能是回复某条评论，还需要通知该评论的作者
        if ($reply->targetReply && !$reply->targetReply->user->isAuthorOf($reply)) {
            $reply->targetReply->user->notify(new ReplyReplied($reply));
        }
    }

    // 每有一条评论删除成功后，动态评论数-1
    public function deleted(Reply $reply)
    {
        $reply->activity->decrement('reply_count');
    }
}
