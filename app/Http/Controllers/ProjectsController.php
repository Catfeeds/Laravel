<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectSupplementRequest;
use App\Models\Project;
use App\Models\Upload;
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

    // 当前登录用户发布的项目
    public function userIndex(Request $request)
    {
        // 验证status: string或array
        $allStatus = [
            Project::STATUS_CANCELED,
            Project::STATUS_TENDERING,
            Project::STATUS_WORKING,
            Project::STATUS_COMPLETED
        ];
        if ($request->status) {
            if (is_array($request->status)) {
                foreach ($request->status as $status) {
                    if (!in_array($status, $allStatus))
                        return $this->response->errorBadRequest('Wrong status field');
                }
            } else if (!in_array($request->status, $allStatus)) {
                return $this->response->errorBadRequest('Wrong status field');
            }
        }

        $projets = Project::where('user_id', $this->user()->id)
            ->whereIn('status', $request->status)
            ->recent()
            ->paginate(20);
        return $this->response->paginator($projets, new ProjectTransformer());
    }
}
