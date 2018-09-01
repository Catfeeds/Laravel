<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLike;
use App\Models\Work;
use App\Models\WorkLike;
use App\Transformers\ActivityTransformer;
use App\Transformers\WorkTransformer;

class WorkLikesController extends Controller
{
    // 点赞
    public function store(Work $work, WorkLike $workLike)
    {
        $currentUser = $this->user();
        if (!WorkLike::where([
            'work_id' => $work->id,
            'user_id'     => $currentUser->id
        ])->exists()) {
            $workLike->user_id = $currentUser->id;
            $work->likes()->save($workLike);
            $work->increment('like_count');
        }
        $work->liked = true;
        return $this->response->item($work, new WorkTransformer());
}

   // 取消点赞
    public function destroy(Work $work)
    {
        $currentUser = $this->user();
        if (WorkLike::where([
            'work_id' => $work->id,
            'user_id'     => $currentUser->id
        ])->exists()) {
            $work->likes()->where('user_id', $currentUser->id)->delete();
            $work->decrement('like_count');
        }
        $work->liked = false;
        return $this->response->item($work, new WorkTransformer());
    }
}
