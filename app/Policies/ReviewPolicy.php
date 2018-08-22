<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function store(User $currentUser, Review $review, User $reviewedUser) {
        $reviewed = $reviewedUser->reviews()->where('reviewer_id', $currentUser->id)->exists();
        $invited = $reviewedUser->invitations()
            ->where('invited_user_id', $currentUser->id)
            ->toReview()
            ->exists();
        if($reviewed) {
            throw new AccessDeniedException(__('已发表过评价'));
        }
        if(!$invited) {
            throw new AccessDeniedException(__('未收到邀请'));
        }
        return true;
    }

    public function destroy(User $user, Review $review)
    {
        // 只有被评价的人与发出评价的人可以删除
        return  $user->id == $review->user_id || $user->id == $review->reviewer_id;
    }
}
