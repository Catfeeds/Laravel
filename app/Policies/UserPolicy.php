<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserPolicy
{
    use HandlesAuthorization;

    public function retrieve(User $currentUser, User $user)
    {
        // 设计师的信息是所有人都可以看的
        if ($user->type === 'designer') {
            return true;
        }

        // 可以查看自己的信息
        if ($user->id == $currentUser->id) {
            return true;
        }

        // 设计师的信息是所有人都可以看的
        if ($user->type === 'designer') {
            return true;
        } else {
            // 甲方的信息只有与之达成协议的设计师或评价他的设计师可以看：

            // 1. 设计师报名了甲方的某个项目，且该项目正在工作中
            $condition1 = $user->projects()
                ->where('status', Project::STATUS_WORKING)
                ->whereHas('applications', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                })->exists();

            // 2. 甲方在某个项目里邀请了该设计师，若该项目正在报名中，则设计师不能明确拒绝邀请
            $condition2 = $user->projects()
                ->where('status', Project::STATUS_TENDERING)
                ->whereHas('invitations', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id)->where('status', '!=', ProjectInvitation::STATUS_DECLINED);
                })->exists();

            // 3. 甲方在某个项目里邀请了该设计师，若该项目正在工作中，则设计师必须接受邀请
            $condition3 = $user->projects()
                ->where('status', Project::STATUS_WORKING)
                ->whereHas('invitations', function ($query) use ($currentUser) {
                    $query->where([
                        'user_id' => $currentUser->id,
                        'status'  => ProjectInvitation::STATUS_ACCEPTED
                    ]);
                })->exists();

            // 4. 甲方邀请了当前设计师评价他，或者当前设计师已经评价过他（曾经邀请过，但是邀请记录在评价完之后就删除了）
            $reviewed = $user->reviews()->where('reviewer_id', $currentUser->id)->exists();
            $invited = $user->invitations()
                ->where('invited_user_id', $currentUser->id)
                ->toReview()
                ->exists();

            if ($condition1 || $condition2 || $condition3 || $reviewed || $invited) {
                return true;
            }

            if ($currentUser->type === 'designer') {
                throw new AccessDeniedException(__('您无权查看该甲方的信息，仅当您与Ta有进行中的项目时或收到Ta的评价邀请时才可查看'));
            } else {
                throw new AccessDeniedException(__('您无权查看该甲方的信息'));
            }
        }
    }

    public function login(User $user)
    {
        if ($user->in_blacklist) {
            throw new HttpException(419, __('该账号已被拉黑'));
        }
        return true;
    }
}
