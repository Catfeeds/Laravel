<?php

namespace App\Notifications;

use App\Models\ProjectInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectInvitedViaEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invitation;

    public function __construct(ProjectInvitation $invitation)
    {
        $this->invitation = $invitation; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $project = $this->invitation->project;
        $party = $project->user;
        return (new MailMessage)
            ->subject($party->name . ' 邀请您参与Ta的项目')
            ->greeting('您好！')
            ->line("甲方 $party->name 邀请您参与Ta的项目 $project->title")
            ->action('立即查看', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('（这是一封自动产生的邮件，请勿回复）')
            ->salutation( null);
    }
}
