<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectPayedViaEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $project = $this->payment->project;
        return (new MailMessage)
            ->subject("您已收到项目设计费 {$this->payment->amount} 元")
            ->greeting('您好！')
            ->line("您已收到项目 $project->title 的设计费 {$this->payment->amount} 元")
            ->action('立即查看', url(env('APP_FRONT_URL') . "#/project/$project->id"))
            ->line('（这是一封自动产生的邮件，请勿回复）')
            ->salutation( null);
    }
}
