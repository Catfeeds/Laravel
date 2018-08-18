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
            ->subject($designer->name . ' 报名了您发布的项目')
            ->greeting('您好！')
            ->line("设计师 $designer->name 报名了您的项目 $project->title")
            ->line('报名备注：'. $this->application->remark)
            ->action('立即查看', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('（这是一封自动产生的邮件，请勿回复）')
            ->salutation( null);
    }
}
