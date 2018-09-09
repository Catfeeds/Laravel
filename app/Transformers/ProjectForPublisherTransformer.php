<?php
/**
 * User: ZhuKaihao
 * Date: 2018/9/2
 * Time: 下午6:14
 */

namespace App\Transformers;


use App\Models\Project;
use League\Fractal\TransformerAbstract;

class ProjectForPublisherTransformer extends TransformerAbstract
{
    public function transform(Project $project)
    {
        $basic = (new ProjectTransformer())->transform($project);
        $extra = [
            // 审核信息
            'review_message'    => $project->review_message,

            // 报名信息：20个
            'applications' => $project->applications()->recent()->limit(20)->get()->map(function ($application) {
                return (new ProjectApplicationTransformer())->transform($application);
            }),

            // 邀请信息：20个
            'invitations'  => $project->invitations()->recent()->limit(20)->get()->map(function ($invitation) {
                return (new ProjectInvitationTransformer())->transform($invitation);
            }),

            // 交付信息：20个
            'deliveries'  => $project->deliveries()->recent()->limit(20)->get()->map(function ($delivery) {
                return (new ProjectDeliveryTransformer())->transform($delivery);
            })
        ];
        return array_merge($basic, $extra);
    }
}