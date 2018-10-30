<?php

namespace App\Http\Controllers;

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
            $others = User::where('type', 'designer')
                ->doesntHave('recommendation')
                ->limit(20 - ($users->count()))
                ->inRandomOrder()
                ->get();
            $users = $users->concat($others);
        }

        return $this->response->collection($users, new UserTransformer());
    }

    // 获取近一个月点赞最多的设计师的作品
    public function works()
    {
        // 选择近一个月最热门的5个作品
        $works = Work::where('created_at', '>=', Carbon::now()->subMonths(1))
            ->public()
            ->orderBy('like_count', 'desc')
            ->limit(20)
            ->get();

        if (!$works->isEmpty()) {
            $num = $works->count() > 5 ? 5 : $works->count();
            $works = $works->random($num);
        }

        // 如果近一个月热门的不够5张，使用1个月前的补全
        if ($works->count() < 5) {
            $works = $works->concat(
                Work::where('created_at', '<', Carbon::now()->subMonths(1))
                    ->public()
                    ->orderBy('like_count', 'desc')
                    ->limit(5 - $works->count())
                    ->get()
            );
        }

        return $this->response->collection($works, new WorkTransformer());
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
