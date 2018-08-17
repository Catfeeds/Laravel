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
class ProjectApplied extends Notification implements ShouldQueue
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
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $project = $this->application->project;
        $designer = $this->application->user;

        //存放在数据库里的数据
        return [
            'type' => 'project_applied',
            'project_id' => $project->id,
            'project_title' => $project->title,
            'application_id' => $this->application->id, // 报名id
            'application_remark' => $this->application->remark,
            'user_id' =>$designer->id, // 报名的设计师的信息
            'user_name' => $designer->name
        ];
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
