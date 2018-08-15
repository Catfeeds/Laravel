<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Notification $notification)
    {
        return $notification->notifiable_type === User::class && $notification->notifiable_id == $user->id;
    }

    public function destroy(User $user, Notification $notification)
    {
        return $notification->notifiable_type === User::class && $notification->notifiable_id == $user->id;
    }
}
