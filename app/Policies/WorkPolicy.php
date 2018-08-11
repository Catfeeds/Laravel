<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        return  $user->type === 'designer'; // 只有设计师才能创建作品
    }

    public function update(User $user, Work $work) {
        return $user->isAuthorOf($work);
    }

    public function destroy(User $user, Work $work) {
        return $user->isAuthorOf($work);
    }
}
