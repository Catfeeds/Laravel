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
            ->subject("You have received consulting fee: {$this->payment->amount}")
            ->greeting('Hello!')
            ->line("You have received the consulting fee of $project->title: {$this->payment->amount} 元")
            ->action('View now', url(env('APP_FRONT_URL') . "#/payment/{$this->payment->id}"))
            ->line('(This is an automatically generated email, please do not reply)')
            ->salutation( null);
    }
}
