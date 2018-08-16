<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/14
 * Time: 下午3:25
 */

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Transformers\MessageTransformer;
use App\Transformers\ThreadTransformer;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use App\Models\Thread;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    // 当前登录用户的私信列表
    public function index()
    {
        // All threads, ignore deleted/archived participants
        // $threads = Thread::getAllLatest()->get();
        // All threads that user is participating in
        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();
        $currentUser = $this->user();
        $threads = Thread::forUser($currentUser->id)->latest('updated_at')->paginate(20);
        $threads->each(function ($thread) use ($currentUser) {
            $thread->setExtraAttributes($currentUser);
        });
        return $this->response->paginator($threads, new ThreadTransformer())
            ->addMeta('extra', [
                'unread_count' => $currentUser->unreadMessagesCount()
            ]);
    }

    // 给某个用户发送一条私信
    public function store(MessageRequest $request)
    {
        $currentUser = $this->user();

        // 看之前有没有发送过私信
        $thread = Thread::whereHas('participants', function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser->id);
        })->whereHas('participants', function ($query) use ($request) {
            $query->where('user_id', $request->to);
        })->first();

        // 如果没有发送过私信，创建thread，关联用户
        if (!$thread) {
            $thread = Thread::create([
                'subject' => 'direct_message'
            ]);
            // Sender
            Participant::create([
                'thread_id' => $thread->id,
                'user_id'   => $currentUser->id,
//                'last_read' => new Carbon,
            ]);
            // Receiver
            $thread->addParticipant($request->to);
        } else {
//            Participant::where('user_id', $currentUser->id)
//                ->first()
//                ->update(['last_read' => new Carbon]);
        }

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id'   => $currentUser->id,
            'body'      => $request->body,
        ]);

        return $this->response->item($message, new MessageTransformer());
    }

    // 发送一条私信
    public function storeByThreadId(Request $request, Thread $thread)
    {
        if (!is_string($request->body)) {
            return $this->response->errorBadRequest('消息内容必须是字符串');
        }

        // 只有私信参与双方可以添加私信
        $currentUser = $this->user();
        if (!in_array($currentUser->id, $thread->participantsUserIds())) {
            return $this->response->errorForbidden('不是私信参与双方');
        }

        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id'   => $currentUser->id,
            'body'      => $request->body,
        ]);
        Participant::where('user_id', $currentUser->id)
            ->first()
            ->update(['last_read' => new Carbon]);
        return $this->response->item($message, new MessageTransformer());
    }

    // 某个私信的消息列表
    public function threadIndex(Thread $thread)
    {
        $currentUser = $this->user();

        // 只有私信参与双方可以获取对话内容
        if (!in_array($currentUser->id, $thread->participantsUserIds())) {
            return $this->response->errorForbidden();
        }

        $thread->markAsRead($currentUser->id);
        $thread->setExtraAttributes($currentUser);
        $messages = $thread->messages()->orderBy('id', 'desc')->paginate(20);
        return $this->response
            ->paginator($messages, new MessageTransformer())
            ->addMeta('thread', (new ThreadTransformer())->transform($thread))
            ->addMeta('extra', [
                'unread_count' => $currentUser->unreadMessagesCount()
            ]);
    }

}