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
            'id'              => $reply->id,
            'user_id'         => $reply->user_id,
            'activity_id'     => $reply->activity_id,
            'reply_id'        => $reply->reply_id,
            'replied_user_id' => $reply->replied_user_id,
            'content'         => $reply->content,
            'created_at'      => $reply->created_at->toDateTimeString(),
            'updated_at'      => $reply->updated_at->toDateTimeString(),
            'replyee'         => $reply->replyee ? (new UserTransformer())->transform($reply->replyee) : null,
            'user'            => (new UserTransformer())->transform($reply->user),
        ];
    }
}