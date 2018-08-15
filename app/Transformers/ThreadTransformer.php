<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/14
 * Time: 下午11:55
 */

namespace App\Transformers;


use Cmgmyr\Messenger\Models\Thread;
use League\Fractal\TransformerAbstract;

class ThreadTransformer extends TransformerAbstract
{
    public function transform(Thread $thread)
    {
        return [
            'id' => $thread->id,
            'subject' => $thread->subject,
            'unread_count' => (int)$thread->unread_count,
            'latest_message' => (new MessageTransformer())->transform($thread->latestMessage),
            'participant' => (new UserTransformer())->transform($thread->participant),
            'created_at' => $thread->created_at->toDateTimeString(),
            'updated_at' => $thread->updated_at->toDateTimeString()
        ];
    }
}