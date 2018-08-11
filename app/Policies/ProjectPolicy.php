<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        return  $user->type === 'party'; // 只有甲方才能发布项目
    }

    public function update(User $user, Project $project)
    {
        return  $user->isAuthorOf($project);
    }

    public function cancel(User $user, Project $project)
    {
        return  $user->isAuthorOf($project);
    }

    public function favorite(User $user)
    {
        return  $user->type === 'designer';
    }
}
