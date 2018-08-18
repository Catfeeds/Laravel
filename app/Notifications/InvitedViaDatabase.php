<?php

namespace App\Notifications;

use App\Models\Invitation;
use App\Models\ProjectApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class InvitedViaDatabase extends Notification
{
    use Queueable;

    protected $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $user = $this->invitation->user;

        //存放在数据库里的数据
        return [
            'type' => 'invite_to_review',
            'user_id' =>$user->id, // 邀请者信息
            'user_name' => $user->name
        ];
    }
}
