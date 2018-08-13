<?php

namespace App\Notifications;

use App\Models\ProjectApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectApplied extends Notification
{
    use Queueable;

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
            'application_id' => $this->application->id, // 报名id
            'user_id' =>$designer->id, // 报名的设计师的信息
            'user_name' => $designer->name
        ];
    }
}
