<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Transformers\ProjectInvitationTransformer;

class ProjectInvitationsController extends Controller
{
    // 获取某个项目的邀请列表
    public function index(Project $project) {
        if(!$this->user()->isAuthorOf($project)) {
            return $this->response->errorForbidden('非项目所有者');
        }
        return $this->response->paginator($project->invitations()
            ->paginate(20), new ProjectInvitationTransformer());
    }

    // 拒绝邀请
    public function decline (ProjectInvitation $invitation, ProjectInvitationRequest $request) {
        if(!$this->user()->id !== $invitation->invited_user_id) {
            $this->response->errorForbidden('非被邀请的设计师本人');
        }

        $invitation->status = ProjectInvitation::STATUS_DECLINED;
        $invitation->refusal_cause = $request->refusal_cause;
        $invitation->save();

        return $this->response->item($invitation, new ProjectInvitationTransformer());
    }
}
