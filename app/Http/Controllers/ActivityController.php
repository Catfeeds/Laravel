<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Image;
use App\Models\User;
use App\Transformers\ActivityTransformer;

class ActivityController extends Controller
{
    public function store(ActivityRequest $request, Activity $activity)
    {
        $activity->fill($request->all());
        $activity->user_id = $this->user()->id;
        $activity->photo_urls = Image::findMany($request->photo_image_ids)->pluck('path');
        $activity->save();
        return $this->response->item($activity, new ActivityTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Activity $activity) {
        $this->authorize('delete', $activity);
        $activity->delete();
        return $this->response->noContent();
    }

    // 关注的人的动态
    public function feeds () {
        $activities = $this->user()->feeds()->latest()->paginate(20);
        return $this->response->paginator($activities, new ActivityTransformer());
    }

    // 用户的动态
    public function userIndex(User $user) {
        $activities = $user->activities()->latest()->paginate(20);
        return $this->response->paginator($activities, new ActivityTransformer());
    }
}
