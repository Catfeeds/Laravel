<?php

namespace App\Notifications;

use App\Models\ProjectApplication;
use App\Models\ProjectInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 项目收到了新报名
 * Class ProjectApplied
 * @package App\Notifications
 */
class ProjectInvitationAcceptedViaDatabase extends Notification
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
        $designer = $this->invitation->user;

        //存放在数据库里的数据
        return [
            'type' => 'project_invitation_accepted',
            'project_id' => $project->id,
            'project_title' => $project->title,
            'invitation_id' => $this->invitation->id,
            'refusal_cause' => $this->invitation->refusal_cause, // 拒绝原因
            'user_id' =>$designer->id, // 设计师的信息
            'user_name' => $designer->name
        ];
    }
}
