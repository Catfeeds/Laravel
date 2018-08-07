<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectApplicationRequest;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\Upload;
use App\Transformers\ProjectApplicationTransformer;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ProjectApplicationsController extends Controller
{
    // 报名
    public function store(ProjectApplicationRequest $request, Project $project, ProjectApplication $projectApplication)
    {
        $this->authorize('store', ProjectApplication::class);
        $currentUser = $this->user();
        if (ProjectApplication::where([
            'project_id' => $project->id,
            'user_id'    => $currentUser->id
        ])->exists()) {
            throw new ConflictHttpException(__('Has already applied for the project'));
        } else {
            $projectApplication->user_id = $currentUser->id;
            $projectApplication->project_id = $project->id;
            $projectApplication->remark = $request->remark;
            if ($request->application_file_id) {
                $project->application_file_url = Upload::find($request->application_file_id)->path;
            }
            $projectApplication->save();
            return $this->response->item($projectApplication, new ProjectApplicationTransformer());
        }
    }

    // 取消报名
    public function destroy(Project $project)
    {
        ProjectApplication::where([
            'project_id' => $project->id,
            'user_id'    => $this->user()->id
        ])->delete();
        return $this->response->noContent();
    }

    // 获取报名详情
    public function index(Project $project, ProjectApplication $projectApplication)
    {
        if ($project->id !== $projectApplication->project_id) {
            return $this->response->errorBadRequest();
        }
        $this->authorize('retrieve', $projectApplication);
        return $this->response->item($projectApplication, new ProjectApplicationTransformer());
    }
}
