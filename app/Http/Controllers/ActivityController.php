<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Image;
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
}
