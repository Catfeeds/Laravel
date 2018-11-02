<?php

namespace App\Notifications;

use App\Models\ProjectApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 项目收到了新报名
 * Class ProjectApplied
 * @package App\Notifications
 */
class ProjectAppliedViaEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(ProjectApplication $application)
    {
        $this->application = $application; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $project = $this->application->project;
        $designer = $this->application->user;
        return (new MailMessage)
            ->subject($designer->name . ' applied your project')
            ->greeting('Hello!')
            ->line("Designer $designer->name applied your project $project->title")
            ->line('Remark: '. $this->application->remark)
            ->action('View now', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('(This is an automatically generated email, please do not reply)')
            ->salutation( null);
    }
}
