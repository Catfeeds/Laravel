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
    protected $defaultIncludes = [
        'author'
    ];

    public function transform(Reply $reply)
    {
        return [
            'id'         => $reply->id,
            'user_id'    => $reply->user_id,
            'content'    => $reply->content,
            'created_at' => $reply->created_at->toDateTimeString(),
            'updated_at' => $reply->updated_at->toDateTimeString()
        ];
    }

    public function includeAuthor(Reply $reply)
    {
        return $this->item($reply->author, new UserTransformer());
    }
}