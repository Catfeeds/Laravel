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
            ->subject($designer->name . ' has delivered the design file of your project')
            ->greeting('Hello!')
            ->line("Designer $designer->name has delivered the design file of your project $project->title")
            ->action('View now', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('(This is an automatically generated email, please do not reply)')
            ->salutation( null);
    }
}
