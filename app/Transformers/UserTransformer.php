<?php
namespace App\Transformers;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
            'views' => (int)$user->views,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),

            // 只有设计师有这些信息
            'professional_fields' => (array)$user->professional_fields,
        ];
    }
}