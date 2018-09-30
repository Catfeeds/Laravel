<?php
namespace App\Transformers;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserWithCanReviewTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $basic = (new UserTransformer())->transform($user);

        $extra = [
            // 是否可以评价：只有在index接口的时候才有该属性
            'can_review' => (boolean)$user->can_review
        ];

        return array_merge($basic, $extra);
    }
}