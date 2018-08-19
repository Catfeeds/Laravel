<?php

namespace App\Notifications;

use App\Models\Invitation;
use App\Models\ProjectApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class InvitedViaEmail extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $user = $this->invitation->user;
        return (new MailMessage)
            ->subject($user->name . ' 邀请您评价')
            ->greeting('您好！')
            ->line("$user->name 邀请您评价Ta，您的评价将展示在Ta的个人主页")
            ->action('查看Ta的个人主页', url(env('APP_FRONT_URL') . "#/profile?uid=$user->id"))
            ->action('发表评价', url(env('APP_FRONT_URL') . "#/review/post?uid=$user->id"))
            ->line('（这是一封自动产生的邮件，请勿回复）')
            ->salutation( null);
    }
}
