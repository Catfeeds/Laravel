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

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Activity $activity, Reply $reply)
    {
        $currentUser = $this->user();
        $reply->content = $request->input('content');
        $reply->activity_id = $activity->id;
        $reply->user_id = $currentUser->id;
        $reply->replyee_id = $request->replyee_id;
        $reply->save();
        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Activity $activity, Reply $reply) {
        if($activity->id != $reply->activity_id) {
            $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();
        return $this->response->noContent();
    }

    public function index(Activity $activity) {
        $replies = $activity->replies()->recent()->paginate(10);
        return $this->response->paginator($replies, new ReplyTransformer());
    }

    public function userIndex() {
        $replies = $this->user()->replies()->recent()->paginate(20);
        return $this->response->paginator($replies, new ReplyTransformer());
    }
}