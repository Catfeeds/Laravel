<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\ProjectInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectRemittedViaDatabase extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $project = $this->project;
        //存放在数据库里的数据
        return [
            'type' => 'project_remitted',
            'project_id' => $project->id,
            'project_title' => $project->title
        ];
    }
}
