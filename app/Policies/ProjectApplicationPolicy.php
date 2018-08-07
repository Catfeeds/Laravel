<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectApplicationPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        return $user->type === 'designer';
    }

    public function retrieve(User $user, ProjectApplication $projectApplication)
    {
        return $user->isAuthorOf($projectApplication) || $user->isAuthorOf($projectApplication->project);
    }
}
