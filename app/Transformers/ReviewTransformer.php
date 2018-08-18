<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/4
 * Time: 下午5:21
 */

namespace App\Transformers;


use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    public function transform(Review $review)
    {
        return [
            'id'                      => $review->id,
//            'rate'                    => (integer)$review->rate,
            'content'                 => (string)$review->content,
//            'additional_content'      => (string)$review->additional_content,
//            'order_id'                => (int)$review->user_id,
//            'requirement_id'          => (int)$review->user_id,
            'user_id'                 => (int)$review->user_id,
            'reviewer_id'             => (int)$review->reviewer_id,
            'reviewer'                => (new UserTransformer())->transform($review->reviewer),
            'created_at'              => $review->created_at->toDateTimeString(),
            'updated_at'              => $review->updated_at->toDateTimeString()
        ];
    }
}