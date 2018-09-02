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
        $reply->user_id = $currentUser->id;
        $reply->activity_id = $activity->id;
        $reply->reply_id = $request->reply_id;
        $reply->save();

        // 构建一个并查集，使所有非一级评论的root_reply_id都指向其所属的一级评论
        if ($request->reply_id) {
            $parentReply = Reply::find($request->reply_id);
            $reply->update(['root_reply_id' => $parentReply->root_reply_id]);
        } else {
            $reply->update(['root_reply_id' => $reply->id]); // 根节点的root_reply_id就是自身
        }

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Activity $activity, Reply $reply)
    {
        if ($activity->id != $reply->activity_id) {
            $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();
        return $this->response->noContent();
    }

    // 获取某个动态的直接评论
    public function index(Activity $activity)
    {
        $replies = $activity->replies()->where('reply_id', null)->recent()->paginate(20);
        return $this->response->paginator($replies, new ReplyTransformer());
    }

    public function userIndex()
    {
        $replies = $this->user()->replies()->recent()->paginate(20);
        return $this->response->paginator($replies, new ReplyTransformer());
    }

    public function replyIndex(Reply $reply)
    {
        $replies = $reply->offspringReplies()->recent()->paginate(20);
        return $this->response->paginator($replies, new ReplyTransformer());
    }
}