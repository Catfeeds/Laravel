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
class ProjectAppliedViaDatabase extends Notification
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
        return ['database'];
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
}
