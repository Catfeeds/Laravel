<?php
/**
 * User: ZhuKaihao
 * Date: 2018/9/9
 * Time: 下午11:10
 */

namespace App\Services;


use App\Models\Project;
use App\Notifications\ProjectInvitedViaDatabase;
use App\Notifications\ProjectInvitedViaEmail;
use App\Notifications\ProjectRemittedViaDatabase;
use App\Notifications\ProjectRemittedViaEmail;

class ProjectsService
{
    // 审核通过后，向被邀请的所有设计师发送通知邮件
    public function notifyInvitedDesigners(Project $project)
    {
        $project->invitations()
            ->where('notified', false) // 注意只通知未被邀请过的
            ->get()
            ->each(function ($invitation) {
                $designer = $invitation->user;
                $designer->notify(new ProjectInvitedViaDatabase($invitation));
                $designer->notifyViaEmail(new ProjectInvitedViaEmail($invitation));
            });

        $project->invitations()->update(['notified' => true]);
    }

    // 甲方托管赏金后，向参与项目的所有设计师发送通知邮件
    public function notifyParticipatingDesigners(Project $project)
    {
        $project->getParticipants()
            ->each(function ($designer) use ($project) {
                $designer->notify(new ProjectRemittedViaDatabase($project));
                $designer->notifyViaEmail(new ProjectRemittedViaEmail($project));
            });
    }
}