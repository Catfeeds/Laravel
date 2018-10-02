<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProjectApplicationPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        if ($user->type != 'designer') {
            return false;
        }

        if ($user->review_status != 1) {
            throw new BadRequestHttpException(__('您还未通过审核，无法报名该项目'));
        }

        return true;
    }

    public function retrieve(User $user, ProjectApplication $projectApplication)
    {
        return $user->isAuthorOf($projectApplication) || $user->isAuthorOf($projectApplication->project);
    }
}
