<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Thread extends \Cmgmyr\Messenger\Models\Thread
{
    // 所有未读消息
    public function userUnreadMessages($userId)
    {
        $messages = $this->messages()->get();

        try {
            $participant = $this->getParticipantFromUser($userId);
        } catch (ModelNotFoundException $e) {
            return collect();
        }

        if (!$participant->last_read) {
            return $messages;
        }

        return $messages->filter(function ($message) use ($participant) {
            return $message->updated_at->gt($participant->last_read) &&
                $message->user_id != $participant->id; // 添加这一行：自己发送的消息不算未读消息
        });
    }

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
