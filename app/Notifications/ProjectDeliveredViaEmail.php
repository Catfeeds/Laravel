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
class ProjectDeliveredViaEmail extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $project = $this->delivery->project;
        $designer = $this->delivery->user;
        return (new MailMessage)
            ->subject($designer->name . ' 提交了项目设计文件')
            ->greeting('您好！')
            ->line("设计师 $designer->name 提交了您的项目 $project->title 的设计文件")
            ->action('立即查看', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('（这是一封自动产生的邮件，请勿回复）')
            ->salutation( null);
    }
}
