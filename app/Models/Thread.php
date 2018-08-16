<?php

namespace App\Models;

class Thread extends \Cmgmyr\Messenger\Models\Thread
{

    // 一次性设置所有的额外属性
    public function setExtraAttributes($user)
    {
        $this->setUnreadCount($user);
        $this->setParticipant($user);
    }

    // 未读消息数
    public function setUnreadCount(User $user)
    {
        $this->attributes['unread_count'] = $this->userUnreadMessagesCount($user->id);
    }

    // 另一个参与者
    public function setParticipant(User $user)
    {
        $this->attributes['participant'] = $this->participants()->where('user_id', '!=', $user->id)
            ->first()->user;
    }
}
