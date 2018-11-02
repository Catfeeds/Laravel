<?php

namespace App\Notifications;

use App\Models\Invitation;
use App\Models\ProjectApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Action;
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
            ->subject($user->name . ' invites you to write review')
            ->greeting('Hello!')
            ->line("$user->name invites you to review him, your review will be shown on his profile page")
            ->action('Write review', url(env('APP_FRONT_URL') . "#/review/post?uid=$user->id"))
            ->line('View his/her profile page: ')
            ->line(url(env('APP_FRONT_URL') . "#/profile?uid=$user->id"))
            ->line('(This is an automatically generated email, please do not reply)')
            ->salutation(null);
    }
}
