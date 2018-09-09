<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/7
 * Time: 下午11:45
 */

namespace App\Transformers;


use App\Models\ProjectApplication;
use App\Models\ProjectInvitation;
use League\Fractal\TransformerAbstract;

class ProjectInvitationTransformer extends TransformerAbstract
{
    public function transform(ProjectInvitation $invitation)
    {
        return [
            'id'              => $invitation->id,
            'invited_user_id' => $invitation->invited_user,
            'project_id'      => $invitation->project_id,
            'status'          => $invitation->status,
            'refusal_cause'   => $invitation->refusal_cause,
            'created_at'      => $invitation->created_at->toDateTimeString(),
            'updated_at'      => $invitation->updated_at->toDateTimeString(),
            'invited_user'    => (new UserTransformer())->transform($invitation->invitedUser),
        ];
    }
}