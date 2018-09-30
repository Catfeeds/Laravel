<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function store(User $currentUser, User $reviewedUser)
    {
        // 已经评价过就不能再评价了
        $reviewed = $reviewedUser->reviews()->where('reviewer_id', $currentUser->id)->exists();
        if ($reviewed) {
            throw new AccessDeniedException(__('已发表过评价'));
        }

        // 有一个已完成（交付了设计文件）的项目时可以评价
        // 1. 甲方评价设计师：该设计师曾经在甲方的一个已完成项目里提交过设计文件
        if ($currentUser->type === 'party' && $reviewedUser->type === 'designer') {
            $hasCompletedProject = $currentUser->projects()
                ->where('status', Project::STATUS_COMPLETED)
                ->whereHas('deliveries', function ($query) use ($reviewedUser) {
                    $query->where('user_id', $reviewedUser->id);
                })->exists();
            if ($hasCompletedProject) {
                return true;
            }
        }
        // 2. 设计师评价甲方：该设计师曾经在甲方的一个已完成项目里提交过设计文件
        if ($currentUser->type === 'designer' && $reviewedUser->type === 'party') {
            $hasCompletedProject = $reviewedUser->projects()
                ->where('status', Project::STATUS_COMPLETED)
                ->whereHas('deliveries', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                })->exists();
            if ($hasCompletedProject) {
                return true;
            }
        }

        // 收到邀请时可以评价
        $invited = $reviewedUser->invitations()
            ->where('invited_user_id', $currentUser->id)
            ->toReview()
            ->exists();
        if ($invited) {
            return true;
        }

        return false;
    }

    public function destroy(User $user, Review $review)
    {
        // 只有被评价的人与发出评价的人可以删除
        return $user->id == $review->user_id || $user->id == $review->reviewer_id;
    }
}
