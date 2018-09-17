<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectPayedViaDatabase extends Notification
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $project = $this->payment->project;
        //存放在数据库里的数据
        return [
            'type' => 'project_payed',
            'project_id' => $project->id,
            'project_title' => $project->title,
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount
        ];
    }
}
