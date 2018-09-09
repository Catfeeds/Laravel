<?php

namespace App\Notifications;

use App\Models\ProjectInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectInvitedViaDatabase extends Notification
{
    use Queueable;

    protected $invitation;

    public function __construct(ProjectInvitation $invitation)
    {
        $this->invitation = $invitation; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $project = $this->invitation->project;
        $party = $project->user;

        //存放在数据库里的数据
        return [
            'type' => 'project_invited',
            'project_id' => $project->id,
            'project_title' => $project->title,
            'party_id' =>$party->id, // 发出邀请的甲方的信息
            'party_name' => $party->name
        ];
    }
}
