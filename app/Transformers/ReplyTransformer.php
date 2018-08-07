<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/31
 * Time: 上午9:54
 */

namespace App\Transformers;

use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{
    public function transform(Reply $reply)
    {
        return [
            'id'           => $reply->id,
            'user_id'      => $reply->user_id,
            'activity_id'  => $reply->activity_id,
            'reply_id'     => $reply->reply_id,
            'replyee_id'   => $reply->replyee_id,
            'replyee_name' => $reply->replyee ? $reply->replyee->name : '',
            'content'      => $reply->content,
            'user'         => (new UserTransformer())->transform($reply->user),
            'created_at'   => $reply->created_at->toDateTimeString(),
            'updated_at'   => $reply->updated_at->toDateTimeString()
        ];
    }
}