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
    protected $availableIncludes = ['user', 'reviewer'];

    public function transform(Review $review)
    {
        return [
            'id'          => $review->id,
            'order_id'    => (int)$review->order_id,
            'content'     => (string)$review->content,
            'user_id'     => (int)$review->user_id,
            'reviewer_id' => (int)$review->reviewer_id,
            'created_at'  => $review->created_at->toDateTimeString(),
            'updated_at'  => $review->updated_at->toDateTimeString()
        ];
    }

    public function includeUser(Review $review)
    {
        return $this->item($review->user, new UserTransformer());
    }

    public function includeReviewer(Review $review)
    {
        return $this->item($review->reviewer, new UserTransformer());
    }
}