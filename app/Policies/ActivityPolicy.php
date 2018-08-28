<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function store(User $user) {
        if($user->type != 'designer') {
            throw new AccessDeniedException(__('只有设计师才能发布动态'));
        }
        return true;
    }

    public function destroy(User $user, Activity $activity)
    {
        return  $user->isAuthorOf($activity);
    }
}
