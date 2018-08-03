<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Image;
use App\Models\User;
use App\Transformers\ActivityTransformer;

class ActivitiesController extends Controller
{
    public function store(ActivityRequest $request, Activity $activity)
    {
        $activity->user_id = $this->user()->id;
        $activity->content = $request->input('content');
        $activity->photo_urls = Image::findMany($request->photo_image_ids)->pluck('path');
        $activity->save();
        $activity->load('user'); // 因为关联关系是延迟加载，所以要手动加载一下该属性
        return $this->response->item($activity, new ActivityTransformer())->setStatusCode(201);
    }

    public function destroy(Activity $activity)
    {
        $this->authorize('destroy', $activity);
        $activity->delete();
        return $this->response->noContent();
    }

    // 关注的人的动态
    public function feeds()
    {
        $currentUser = $this->user();
        $followings = $currentUser->followings()->pluck('id');
        $followings[] = $currentUser;
        $activities = Activity::whereHas('user', function ($query) use ($followings) {
            $query->whereIn('id', $followings);
        })->recent()->paginate(20);
        $activities->each(function ($activity) use ($currentUser) {
            $activity->setLiked($currentUser);
            $activity->user->setFollowing($currentUser);
        });
        return $this->response->paginator($activities, new ActivityTransformer());
    }

    // 某个用户的动态
    public function userIndex(User $user)
    {
        $currentUser = $this->user();
        $activities = $user->activities()->recent()->paginate(20);
        $activities->each(function ($activity) use ($currentUser) {
            $activity->setLiked($currentUser);
            $activity->user->setFollowing($currentUser);
        });
        return $this->response->paginator($activities, new ActivityTransformer());
    }
}
