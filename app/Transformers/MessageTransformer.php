<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/14
 * Time: 下午11:58
 */

namespace App\Transformers;


use Cmgmyr\Messenger\Models\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {
        return [
            'id' => $message->id,
            'user_id' => $message->user_id,
            'thread_id' => $message->thread_id,
            'body' => $message->body,
            'created_at' => $message->created_at->toDateTimeString(),
            'updated_at' => $message->updated_at->toDateTimeString()
        ];
    }
}