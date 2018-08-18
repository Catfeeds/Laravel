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
            throw new AccessDeniedException(__('Already reviewed'));
        }
        if(!$invited) {
            throw new AccessDeniedException(__('Not invited'));
        }
        return true;
    }

    public function destroy(User $user, Review $review)
    {
        // 只有被评价的人与发出评价的人可以删除
        return  $user->isAuthorOf($review) || $user->id == $review->user->id;
    }
}
