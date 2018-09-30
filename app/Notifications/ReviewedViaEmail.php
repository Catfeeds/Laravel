<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 项目收到了新报名
 * Class ProjectApplied
 * @package App\Notifications
 */
class ReviewedViaEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    public function __construct(Review $review)
    {
        $this->review = $review; // 注入实体，方便toDatabase调用
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $reviewer = $this->review->reviewer;
        return (new MailMessage)
            ->subject($reviewer->name . ' 评价了您')
            ->greeting('您好！')
            ->line("$reviewer->name 评价了您。")
            ->line('评价内容：'. $this->review->content)
            ->action('立即查看', url(env('APP_FRONT_URL') . "#/profile"))
            ->line('（这是一封自动产生的邮件，请勿回复）')
            ->salutation( null);
    }
}
