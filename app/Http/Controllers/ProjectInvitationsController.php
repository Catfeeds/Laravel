<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Notifications\ProjectInvitationAcceptedViaDatabase;
use App\Notifications\ProjectInvitationDeclinedViaDatabase;
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

    // 接受邀请
    public function accept (Project $project) {
        $user = $this->user();

        $invitation = $project->invitations()->where('user_id', $user->id)->first();
        if(!$invitation) {
            $this->response->errorForbidden('您未被邀请');
        }

        $invitation->status = ProjectInvitation::STATUS_ACCEPTED;
        $invitation->save();

        $invitation->project->user->notify(new ProjectInvitationAcceptedViaDatabase($invitation));

        return $this->response->item($invitation, new ProjectInvitationTransformer());
    }

    // 拒绝邀请
    public function decline (Project $project, ProjectInvitationRequest $request) {
        $invitation = $project->invitations()->where('user_id', $this->user()->id)->first();
        if(!$invitation) {
            $this->response->errorForbidden('您未被邀请');
        }

        $invitation->status = ProjectInvitation::STATUS_DECLINED;
        $invitation->refusal_cause = $request->refusal_cause;
        $invitation->save();

        $invitation->project->user->notify(new ProjectInvitationDeclinedViaDatabase($invitation));

        return $this->response->item($invitation, new ProjectInvitationTransformer());
    }
}
