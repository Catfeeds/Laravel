<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar_url' => $this->reply->user->avatar_url,
            'activity_id' => $activity->id
        ];
    }
}
