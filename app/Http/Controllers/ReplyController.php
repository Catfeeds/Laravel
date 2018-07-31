<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/31
 * Time: 上午9:45
 */

namespace App\Http\Controllers;


use App\Http\Requests\ReplyRequest;
use App\Models\Activity;
use App\Models\Reply;
use App\Transformers\ReplyTransformer;

class ReplyController extends Controller
{
    public function store(ReplyRequest $request, Activity $activity, Reply $reply)
    {
        $reply->content = $request->content;
        $reply->activity_id = $activity->id;
        $reply->user_id = $this->user()->id;
        $reply->save();
        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }
}