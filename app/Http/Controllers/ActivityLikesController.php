<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLike;
use App\Transformers\ActivityTransformer;

class ActivityLikesController extends Controller
{
    // 点赞
    public function store(Activity $activity, ActivityLike $activityLike)
    {
        $currentUser = $this->user();
        if (!ActivityLike::where([
            'activity_id' => $activity->id,
            'user_id'     => $currentUser->id
        ])->exists()) {
            $activityLike->user_id = $currentUser->id;
            $activity->likes()->save($activityLike);
            $activity->increment('like_count');
        }
        $activity->setLiked($currentUser);
        return $this->response->item($activity, new ActivityTransformer());
}

   // 取消点赞
    public function destroy(Activity $activity)
    {
        $currentUser = $this->user();
        if (ActivityLike::where([
            'activity_id' => $activity->id,
            'user_id'     => $currentUser->id
        ])->exists()) {
            $activity->likes()->where('user_id', $currentUser->id)->delete();
            $activity->decrement('like_count');
        }
        $activity->setLiked($currentUser);
        return $this->response->item($activity, new ActivityTransformer());
    }
}
