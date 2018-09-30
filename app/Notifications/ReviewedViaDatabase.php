<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 收到了一条新的评价
 * Class ProjectApplied
 * @package App\Notifications
 */
class ReviewedViaDatabase extends Notification
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
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $reviewer = $this->review->reviewer;

        //存放在数据库里的数据
        return [
            'type' => 'reviewed',
            'content' => $this->review->content,
            'user_id' =>$reviewer->id, // 发表评价的用户的信息
            'user_name' => $reviewer->name
        ];
    }
}
