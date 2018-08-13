<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * 动态有了新评论
 * Class ActivityReplied
 * @package App\Notifications
 */
class ActivityReplied extends Notification
{
    use Queueable;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $activity = $this->reply->activity;

        //存放在数据库里的数据
        return [
            'type' => 'activity_replied',
            'activity_id' => $activity->id,
            'activity_content' => $activity->content,
            'reply_id' => $this->reply->id, // 收到的评论的id
            'reply_content' => $this->reply->content, // 收到的评论内容
            'user_id' => $this->reply->user->id, // 发布评论的用户的信息
            'user_name' => $this->reply->user->name
        ];
    }
}
