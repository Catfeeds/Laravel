<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectSupplementRequest;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\Reply;
use App\Models\Upload;
use App\Models\User;
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
        $attrubutes['status'] = Project::STATUS_TENDERING;
        if ($request->project_file_id) {
            $attrubutes['project_file_url'] = Upload::find($request->project_file_id)->path;
        }
        return $this->response->item(Project::create($attrubutes), new ProjectTransformer())
            ->setStatusCode(201);
    }

    // 获取项目详情
    public function index(Project $project)
    {
        $project->setExtraAttributes($this->user());
        return $this->response->item($project, new ProjectTransformer());
    }

    // 补充项目
    public function update(ProjectSupplementRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        if ($project->supplement_at) {
            $this->response->errorBadRequest(__('Allow only supplement the project once'));
        }
        $project->supplement_description = $request->supplement_description;
        if ($request->supplement_file_id) {
            $project->supplement_file_url = Upload::find($request->supplement_file_id)->path;
        }
        $project->supplement_at = Carbon::now()->toDateTimeString();
        $project->save();
        return $this->response->item($project, new ProjectTransformer());
    }

    // 取消项目
    public function cancel(Project $project)
    {
        $this->authorize('cancel', $project);
        // 已取消，直接返回
        if ($project->status == Project::STATUS_CANCELED) {
            return $this->response->noContent();
        }
        if ($project->status !== Project::STATUS_TENDERING) {
            return $this->response->errorBadRequest(__('The project cannot be canceled'));
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

        $status = $this->getStatusFromRequest($request);
        $projets = Project::where('user_id', $user->id)
            ->whereIn('status', $status)
            ->recent()
            ->paginate(20);
        return $this->response->paginator($projets, new ProjectTransformer());
    }

    // 当前登录用户的项目
    public function userIndex(Request $request)
    {
        $currentUser = $this->user();

        // 当前用户是甲方：返回发布的项目
        if ($currentUser->type == 'party') {
            return $this->partyIndex($currentUser, $request);
        }

        // 当前用户是设计师：返回报名的项目
        $status = $this->getStatusFromRequest($request);
        $projects = Project::whereHas('applications', function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser->id);
        })->whereIn('status', $status)
            ->recent()
            ->paginate(20);
        return $this->response->paginator($projects, new ProjectTransformer());
    }

    // 根据request初始化status数组
    private function getStatusFromRequest($request)
    {
        $allStatus = [
            Project::STATUS_CANCELED,
            Project::STATUS_TENDERING,
            Project::STATUS_WORKING,
            Project::STATUS_COMPLETED
        ];
        $status = [];
        if ($request->status) {
            if (is_array($request->status)) {
                $status = $request->status;
            } else {
                $status[] = $request->status;
            }
        } else {
            $status = $allStatus;
        }
        return $status;
    }
}
