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
        $client = $project->user;
        return (new MailMessage)
            ->subject($client->name . ' invites you to participate his/her project')
            ->greeting('Hello!')
            ->line("Client $client->name invites you to participate his/her project $project->title")
            ->action('View now', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('(This is an automatically generated email, please do not reply)')
            ->salutation( null);
    }
}
