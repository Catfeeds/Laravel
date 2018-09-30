<?php

namespace App\Observers;

use App\Models\Review;
use App\Notifications\ReviewedViaDatabase;
use App\Notifications\ReviewedViaEmail;

class ReviewObserver
{
    // 收到一条新的评价
    public function created(Review $review)
    {
        $user = $review->user;
        $user->notify(new ReviewedViaDatabase($review));
        $user->notifyViaEmail(new ReviewedViaEmail($review));
    }
}
