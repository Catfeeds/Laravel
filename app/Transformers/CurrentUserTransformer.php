<?php
namespace App\Transformers;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CurrentUserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'type' => $user->type,
            'avatar_url' => (string)$user->avatar_url,
            'title' => (string)$user->title,
            'introduction' => (string)$user->introduction,
            'following_count' => (int)$user->following_count,
            'follower_count' => (int)$user->follower_count,
            'following' => (boolean)$user->following,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),

            // 隐私信息
            'phone' => (string)$user->phone,
            'email' => (string)$user->email,
            'id_number' => (string)$user->id_number,
            'id_card_url' => (string)$user->id_card_url,
            'bank_name' => (string)$user->bank_name,
            'bank_card_number' => (string)$user->bank_card_number,
            'account_name' => (string)$user->account_name,
            'qualification_urls' => (array)$user->qualification_urls,

            'email_activated' => (boolean)$user->email_activated,
            'notification_count' => (int)$user->notification_count,
            'unread_message_count' => (int)$user->unreadMessagesCount()
        ];
    }
}