<?php
namespace App\Transformers;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserForReviewTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $basic = (new UserTransformer())->transform($user);

        // 此用户对当前登录用户的评价状态
        $extra = [
            'review_status' => $user->review_status
        ];

        return array_merge($basic, $extra);
    }
}