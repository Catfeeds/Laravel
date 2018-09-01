<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Work;
use App\Transformers\ProjectTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\WorkTransformer;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // 获取平台的设计师，随机返回20名
    public function designers()
    {
        $users = User::where('type', 'designer')->inRandomOrder()->limit(20)->get();
        return $this->response->collection($users, new UserTransformer());
    }

    // 获取设计师的作品，随机返回5个  TODO: 点赞最多的5个
    public function works()
    {
        $works = Work::inRandomOrder()->limit(5)->get();
        return $this->response->collection($works, new WorkTransformer());
    }

    // 获取项目，进行中的项目在前面，已完成的项目在后面
    public function projects()
    {
        $status = Project::STATUS_COMPLETED;
        $projects = Project::whereIn('status', [
            Project::STATUS_TENDERING, Project::STATUS_WORKING, Project::STATUS_COMPLETED
        ])->orderByRaw("case when status = $status then 0 else 1 end desc, id desc")
            ->paginate(20);
        return $this->response->paginator($projects, new ProjectTransformer());
    }
}
