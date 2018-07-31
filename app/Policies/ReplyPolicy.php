<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function destroy(User $user, Reply $reply)
    {
        // 只有动态作者和评论作者才能删除
        return  $user->isAuthorOf($reply) || $user->isAuthorOf($reply->activity);
    }
}
