<?php

namespace App\Http\Controllers;

use App\Models\IndexImage;
use App\Models\Project;
use App\Models\User;
use App\Models\Work;
use App\Transformers\ProjectTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\WorkTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // 获取平台的设计师
    public function designers()
    {
        // 先获取推荐的设计师
        $users = User::where('type', 'designer')
            ->has('recommendation')
            ->limit(20)
            ->inRandomOrder()
            ->get();

        if ($users->count() < 20) {
            $others = User::where([
                    'type' => 'designer',
                    // 'review_status' => 1 // 首页只显示通过审核的设计师
                ])
                ->doesntHave('recommendation')
                ->limit(20 - ($users->count()))
                ->inRandomOrder()
                ->get();
            $users = $users->concat($others);
        }

        return $this->response->collection($users, new UserTransformer());
    }

    // 获取首页作品
    public function works()
    {
        // 首页的10个轮播图
        $images = IndexImage::limit(10)->get();

        $images = $images->map(function ($img) {
           $img['url'] = \Storage::disk(config('admin.upload.disk'))->url($img['url']);
           return $img;
        });

        return $images;
    }

    // 获取项目：新发布 > 进行中 > 已完成
    public function projects()
    {
        $statusNewReleased = Project::STATUS_TENDERING;
        $statusInProgress = Project::STATUS_WORKING;
        $projects = Project::whereIn('status', [
            Project::STATUS_TENDERING, Project::STATUS_WORKING, Project::STATUS_COMPLETED
        ])->where('mode', 'free')
            ->orderByRaw("case when status = $statusNewReleased then 2
                        when status = $statusInProgress then 1 
                        else 0 end 
                        desc, id desc")
            ->paginate(20);
        return $this->response->paginator($projects, new ProjectTransformer());
    }
}
