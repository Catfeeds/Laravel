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
            ->subject($reviewer->name . ' wrote a review for you')
            ->greeting('Hello!')
            ->line("$reviewer->name wrote a review for you.")
            ->line('Content: '. $this->review->content)
            ->action('View now', url(env('APP_FRONT_URL') . "#/profile"))
            ->line('(This is an automatically generated email, please do not reply)')
            ->salutation( null);
    }
}
