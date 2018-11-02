<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectRemittedViaEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $project = $this->project;
        return (new MailMessage)
            ->subject('The project you participating has remitted consulting fee')
            ->greeting('Hello!')
            ->line("The project $project->title you participating has remitted consulting fee. You can start working now!")
            ->action('View now', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('(This is an automatically generated email, please do not reply)')
            ->salutation( null);
    }
}
