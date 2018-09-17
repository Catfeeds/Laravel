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
            ->subject('您参与的项目已托管赏金')
            ->greeting('您好！')
            ->line("您参与的项目 $project->title 已托管赏金，您可以开始工作了！")
            ->action('立即查看', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('（这是一封自动产生的邮件，请勿回复）')
            ->salutation( null);
    }
}
