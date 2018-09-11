<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectDeliveryRequest;
use App\Models\Project;
use App\Models\ProjectDelivery;
use App\Transformers\ProjectDeliveryTransformer;
use Illuminate\Http\Request;

class ProjectDeliveriesController extends Controller
{
    public function store(ProjectDeliveryRequest $request, Project $project)
    {
        $this->authorize('store', [ ProjectDelivery::class, $project]);

        $user = $this->user();

        if ($project->status !== Project::STATUS_WORKING) {
            $this->response->errorBadRequest(__('项目当前不允许交付'));
        }

        if (ProjectDelivery::where([
            'user_id'    => $user->id,
            'project_id' => $project->id
        ])->exists()) {
            $this->response->errorBadRequest(__('已上传过交付文件，不可重复上传'));
        }

        $delivery = ProjectDelivery::create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'remark'     => $request->remark,
            'file_url'   => $request->file_url
        ]);

        return $this->response->item($delivery, new ProjectDeliveryTransformer());
    }

    public function destroy(ProjectDelivery $delivery) {
        $this->authorize('destroy', $delivery);
        $delivery->delete();
        return $this->response->noContent();
    }

    // 获取某个项目的交付列表
    public function index(Project $project) {
        if($this->user()->id != $project->user_id) {
            return $this->response->errorForbidden('非项目所有者');
        }
        return $this->response->paginator($project->deliveries()
            ->paginate(20), new ProjectDeliveryTransformer());
    }
}
