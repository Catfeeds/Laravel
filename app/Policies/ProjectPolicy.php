<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function retrieve(User $user, Project $project)
    {
        // 项目未审核通过时，只有作者可以查看
        if ($project->status == Project::STATUS_REVIEWING ||
            $project->status == Project::STATUS_REVIEW_FAILED) {
            if (!$user->isAuthorOf($project)) {
                throw new AccessDeniedException(__('您无权查看'));
            }
            return true;
        }

        // 项目是其他状态时，如果是公开项目：所有人可访问
        if ($project->mode == 'free') {
            return true;
        } else {
            // 如果是非公开项目只有被邀请的人（设计师）可以看
            if (!$project->invitations()->where('user_id', $user->id)->exists()) {
                throw new AccessDeniedException(__('您无权查看'));
            }
            return true;
        }
    }

//    public function retrieve(User $user, Project $project) {
//        // 项目未审核通过时，只有作者可以查看
//        if ($project->status == Project::STATUS_REVIEWING ||
//            $project->status == Project::STATUS_REVIEW_FAILED) {
//            if (!$user->isAuthorOf($project)) {
//                throw new AccessDeniedException(__('您无权查看'));
//            }
//        } else {
//            // 项目是其他状态时，考虑项目类别与用户身份
//            // 如果是公开项目：所有人可访问
//            if ($project->mode == 'free') {
//                return true;
//            } else {
//                // 如果是非公开项目
//                // 如果当前用户是甲方：只有作者可查看
//                if ($user->type === 'party') {
//                    return $user->isAuthorOf($project);
//                } else {
//                    // 如果当前用户是设计师
//                    // 若项目在招标中，则所有被邀请的设计师可以访问
//                    if ($project->status == Project::STATUS_TENDERING) {
//                        if (!$project->invitations()->where('user_id', $user->id)->exists()) {
//                            throw new AccessDeniedException(__('您无权查看'));
//                        } else {
//                            return true;
//                        }
//                    } else {
//                        // 若是其他状态，则只有接受邀请的设计师可以访问
//                        if (!$project->invitations()->where([
//                            'user_id' => $user->id,
//                            'status' => ProjectInvitation::STATUS_ACCEPTED
//                        ])->exists()) {
//                            throw new AccessDeniedException(__('您无权查看'));
//                        }
//                        return true;
//                    }
//                }
//            }
//        }
//    }


    public function store(User $user)
    {
        return $user->type === 'party'; // 只有甲方才能发布项目
    }

    public function update(User $user, Project $project)
    {
        return $user->isAuthorOf($project);
    }

    public function destroy(User $user, Project $project)
    {
        return $user->isAuthorOf($project);
    }

    public function cancel(User $user, Project $project)
    {
        return $user->isAuthorOf($project);
    }

    public function favorite(User $user)
    {
        return $user->type === 'designer';
    }
}
