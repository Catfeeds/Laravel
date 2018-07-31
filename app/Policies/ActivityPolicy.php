<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function destroy(User $user, Activity $activity)
    {
        return  $user->isAuthorOf($activity);
    }
}
