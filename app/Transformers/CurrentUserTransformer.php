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
            'email_activated' => (boolean)$user->email_activated,
            'company_name' => (string)$user->company_name,
            'registration_number' => (string)$user->registration_number,
            'id_number' => (string)$user->id_number,
            'business_license_url' => (string)$user->business_license_url,
            'id_card_url' => (string)$user->id_card_url,
            'notification_count' => (int)$user->notification_count,
            'unread_message_count' => (int)$user->unreadMessagesCount()
        ];
    }
}