<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkRequest;
use App\Models\Upload;
use App\Models\User;
use App\Models\Work;
use App\Transformers\WorkTransformer;

class WorksController extends Controller
{
    public function store(WorkRequest $request, Work $work)
    {
        $this->authorize('store', Work::class);
        $work->user_id = $this->user()->id;
        $work->title = $request->input('title');
        $work->description = $request->input('description');
        $work->visible_range = $request->input('visible_range');
        $work->photo_urls = $request->input('photo_urls');
        $work->save();
        return $this->response->item($work, new WorkTransformer());
    }

    public function update(Work $work, WorkRequest $request)
    {
        $this->authorize('store', $work);
        $attributes = $request->only(['title', 'description', 'photo_urls', 'visible_range']);
        $work->update($attributes);
        return $this->response->item($work, new WorkTransformer());

    }

    public function index(Work $work)
    {
        return $this->response->item($work, new WorkTransformer());
    }

    public function destroy(Work $work)
    {
        $this->authorize('store', $work);
        $work->delete();
        return $this->response->noContent();
    }

    // 某个用户的作品集
    public function userIndex(User $user)
    {
        $currentUser = $this->user();
        if ($currentUser && $user->id === $currentUser->id) {
            $works = $user->works()->recent()->paginate(20);
        } else {
            $works = $user->works()->public()->recent()->paginate(20);
        }
        $works->each(function ($work) use ($currentUser) {
            $work->setLiked($currentUser);
        });
        return $this->response->paginator($works, new WorkTransformer());
    }

    // 所有作品集
    public function all()
    {
        $works = Work::public()->recent()->paginate(20);
        $currentUser = $this->user();
        $works->each(function ($work) use ($currentUser) {
            $work->setLiked($currentUser);
            $work->user->setFollowing($currentUser);
        });
        return $this->response->paginator($works, new WorkTransformer());
    }
}
