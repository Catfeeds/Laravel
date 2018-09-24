<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectDelivery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ProjectDeliveryPolicy
{
    use HandlesAuthorization;

    public function store(User $user, Project $project) {
        if($user->type !== 'designer') {
            return false;
        }

        if($project->mode === 'free') {
            // 如果是自由式，看有没有报名
            return $project->applications()->where('user_id', $user->id)->exists();
        } else {
            // 如果是邀请或者指定，看有没有邀请
            return $project->invitations()->where('user_id', $user->id)->exists();
        }
    }

    public function update(User $user, ProjectDelivery $delivery) {
        return $user->isAuthorOf($delivery);
    }

    // 删除交付文件：TODO 项目所属甲方也能删除
    public function destroy(User $user, ProjectDelivery $delivery) {
        return $user->isAuthorOf($delivery) /* || $user->isAuthorOf($delivery->project) */;
    }
}
