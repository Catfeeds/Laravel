<?php
namespace App\Transformers;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CurrentUserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $basic = (new UserTransformer())->transform($user);

        // 隐私信息
        $extra = [
            'phone' => (string)$user->phone,
            'email' => (string)$user->email,

            'email_activated' => (boolean)$user->email_activated,
            'notification_count' => (int)$user->notification_count,
            'unread_message_count' => (int)$user->unreadMessagesCount(),

            // 以下信息只有设计师有，但是共用了一个表
            'id_number' => (string)$user->id_number,
            'id_card_url' => (string)$user->id_card_url,
            'bank_name' => (string)$user->bank_name,
            'bank_card_number' => (string)$user->bank_card_number,
            'account_name' => (string)$user->account_name,
            'qualification_urls' => (array)$user->qualification_urls,
            'total_payment' => (float)$user->payments()->sum('amount'),

            'in_blacklist' => (boolean)$user->in_blacklist,
            'review_status' => (integer)$user->review_status

        ];

        return array_merge($basic, $extra);
    }
}