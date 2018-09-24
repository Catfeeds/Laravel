<?php

namespace App\Notifications;

use App\Models\ProjectApplication;
use App\Models\ProjectDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 项目收到了新报名
 * Class ProjectApplied
 * @package App\Notifications
 */
class ProjectDeliveredViaDatabase extends Notification
{
    use Queueable;

    protected $delivery;

    public function __construct(ProjectDelivery $delivery)
    {
        $this->delivery = $delivery; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $project = $this->delivery->project;
        $designer = $this->delivery->user;

        //存放在数据库里的数据
        return [
            'type' => 'project_delivered',
            'project_id' => $project->id,
            'project_title' => $project->title,
            'delivery_id' => $this->delivery->id,
            'user_name' => $designer->name,
            'user_id' => $designer->id
        ];
    }
}
