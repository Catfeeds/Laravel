<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectSupplementRequest;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\Reply;
use App\Models\Upload;
use App\Models\User;
use App\Transformers\ProjectForDesignerTransformer;
use App\Transformers\ProjectForPublisherTransformer;
use App\Transformers\ProjectTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    // 发布项目
    public function store(ProjectRequest $request)
    {
        $this->authorize('store', Project::class);
        $attrubutes = $request->only(['title', 'types', 'features', 'area', 'description', 'delivery_time', 'payment', 'find_time', 'remark']);
        $attrubutes['user_id'] = $this->user()->id;
        $attrubutes['status'] = Project::STATUS_REVIEWING;
        if ($request->project_file_id) {
            $attrubutes['project_file_url'] = Upload::find($request->project_file_id)->path;
        }
        return $this->response->item(Project::create($attrubutes), new ProjectForPublisherTransformer())
            ->setStatusCode(201);
    }

    // 获取项目详情
    public function index(Project $project)
    {
        $this->authorize('retrieve', $project);

        $currentUser = $this->user();
        $project->setExtraAttributes($currentUser);

        // 添加当前用户的报名信息
        if ($currentUser->type === 'designer') {
            $project['application'] = $project
                ->applications()
                ->where('user_id', $currentUser->id)
                ->first();
            return $this->response->item($project, new ProjectForDesignerTransformer());
        } else {
            if($currentUser->isAuthorOf($project)) {
                return $this->response->item($project, new ProjectForPublisherTransformer());
            } else {
                return $this->response->item($project, new ProjectTransformer());
            }
        }


    }

    // 补充项目
    public function update(ProjectSupplementRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        if ($project->supplement_at) {
            return $this->response->errorBadRequest(__('每个项目只能补充一次'));
        }
        $project->supplement_description = $request->supplement_description;
        if ($request->supplement_file_id) {
            $project->supplement_file_url = Upload::find($request->supplement_file_id)->path;
        }
        $project->supplement_at = Carbon::now()->toDateTimeString();
        $project->save();
        return $this->response->item($project, new ProjectForPublisherTransformer());
    }

    // 删除项目
    public function destroy(Project $project)
    {
        $this->authorize('destroy', $project);
        $project->delete();
        return $this->response->noContent();
    }

    //申请重新审核
    public function reReview(Project $project)
    {
        $this->authorize('update', $project);
        if ($project->status != Project::STATUS_REVIEW_FAILED) {
            return $this->response->errorBadRequest(__('只有未通过审核的订单才能申请重新审核'));
        } else {
            $project->status = Project::STATUS_REVIEWING;
            return $this->response->item($project, new ProjectForPublisherTransformer());
        }
    }

    // 取消项目
    public function cancel(Project $project)
    {
        $this->authorize('cancel', $project);

        // 已取消，直接返回
        if ($project->status == Project::STATUS_CANCELED) {
            return $this->response->noContent();
        }

        if (!in_array($project->status, [
            Project::STATUS_REVIEWING,
            Project::STATUS_REVIEW_FAILED,
            Project::STATUS_TENDERING,
        ])) {
            return $this->response->errorBadRequest(__('该项目无法取消'));
        }

        $project->status = Project::STATUS_CANCELED;
        $project->canceled_at = Carbon::now()->toDateTimeString();
        $project->save();

        return $this->response->noContent();
    }

    // 某个业主发布的项目
    public function partyIndex(User $user, Request $request)
    {
        if ($user->type !== 'party') {
            return $this->response->errorUnauthorized('公开接口只能访问甲方发布的项目');
        }

        $projects = $this->getBasicQuery($request)
            ->where('user_id', $user->id)
            ->recent()
            ->paginate(20);

        return $this->response->paginator($projects, new ProjectTransformer());
    }

    // 当前登录用户的项目
    public function userIndex(Request $request)
    {
        $currentUser = $this->user();

        // 当前用户是甲方：返回发布的项目
        if ($currentUser->type == 'party') {
            $projects = $this->getBasicQuery($request, true)
                ->where('user_id', $currentUser->id)
                ->recent()
                ->paginate(20);
            return $this->response->paginator($projects, new ProjectForPublisherTransformer());
        } else {
            // 当前用户是设计师：返回报名的项目
            $projects = $this->getBasicQuery($request, true)
                ->whereHas('applications', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                })
                ->recent()
                ->paginate(20);
            return $this->response->paginator($projects, new ProjectForDesignerTransformer());
        }

    }

    // 当前登录的业主进行中的项目
    public function processing(Request $request)
    {
        $currentUser = $this->user();

        // 当前用户是甲方：返回发布的项目
        if ($currentUser->type == 'party') {
            $projects = $currentUser->projects()
                ->whereIn('status', [
                    Project::STATUS_REVIEWING,
                    Project::STATUS_REVIEW_FAILED,
                    Project::STATUS_TENDERING,
                    Project::STATUS_WORKING
                ])
                ->recent()
                ->paginate(20);
        } else {
            // TODO 当前用户是设计师：返回报名的项目
            $this->response->error('未实现');
        }

        return $this->response->paginator($projects, new ProjectForPublisherTransformer());
    }

    // 当前登录用户收藏的项目
    public function favorite(Request $request)
    {
        $currentUser = $this->user();

        $projects = $this->getBasicQuery($request, true)
            ->whereHas('favoriteUser', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
            ->recent()
            ->paginate(20);

        $projects->each(function ($project) use ($currentUser) {
            $project->setExtraAttributes($currentUser);
        });

        return $this->response->paginator($projects, new ProjectTransformer());
    }

    // 搜索项目
    public function search(Request $request)
    {
        $projects = $this->getBasicQuery($request)
            ->recent()
            ->paginate(20);
        return $this->response->paginator($projects, new ProjectTransformer());
    }

    /**
     * 根据query中的参数初始化查询器
     * @params $request
     * @params $withPrivate 是否包含私有项目（审核中、审核失败、已取消）
     */
    private function getBasicQuery(Request $request, $withPrivate = false)
    {
        $status = [];
        $allStatus = [
            Project::STATUS_TENDERING,
            Project::STATUS_WORKING,
            Project::STATUS_COMPLETED
        ];

        if ($withPrivate) {
            $allStatus = array_merge($allStatus, [
                Project::STATUS_REVIEWING,
                Project::STATUS_REVIEW_FAILED,
                Project::STATUS_CANCELED
            ]);
        }

        if ($request->status) {
            // status可以是字符串也可以是数组
            if (is_array($request->status)) {
                $status = $request->status;
            } else {
                $status[] = $request->status;
            }
        } else {
            $status = $allStatus; // 如果没有设置status，则默认搜索所有项目
        }

        $query = Project::whereIn('status', $status)->recent();

        if (is_string($request->keyword)) {
            $query = $query->where('title', 'like', "%$request->keyword%");
        }

        return $query;
    }
}
