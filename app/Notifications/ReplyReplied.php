<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * 自己发表在动态下的评论收到了他人的回复
 * Class ReplyReplied
 * @package App\Notifications
 */
class ReplyReplied extends Notification
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
            'type' => 'reply_replied',
            'activity_id' => $activity->id,
            'activity_content' => $activity->content,
            'reply_id' => $this->reply->id, // 收到的评论的id
            'reply_content' => $this->reply->content, // 收到的评论内容
            'target_reply_id' => $this->reply->reply_id, // 被回复的评论的id
            'user_id' => $this->reply->user->id, // 发布评论的用户的信息
            'user_name' => $this->reply->user->name
        ];
    }
}
