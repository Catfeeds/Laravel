<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function retrieve(User $user, Project $project)
    {
        if ($project->status == Project::STATUS_REVIEWING ||
            $project->status == Project::STATUS_REVIEW_FAILED) {
            return $user->isAuthorOf($project);
        }
        return true;
    }

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
