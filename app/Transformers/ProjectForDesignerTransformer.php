<?php
/**
 * User: ZhuKaihao
 * Date: 2018/9/2
 * Time: 下午6:14
 */

namespace App\Transformers;


use App\Models\Project;
use League\Fractal\TransformerAbstract;

/**
 * 某个设计师查看的项目详情，包含了他在这个项目里的相关信息
 * Class ProjectForDesignerTransformer
 * @package App\Transformers
 */
class ProjectForDesignerTransformer extends TransformerAbstract
{
    public function transform(Project $project)
    {
        $basic = (new ProjectTransformer())->transform($project);
        $extra = [
            // 当前登录设计师的报名信息
            'application' => $project->application ? [
                'id'                   => $project->application->id,
                'remark'               => $project->application->remark,
                'application_file_url' => $project->application->application_file_url,
                'created_at'           => $project->application->created_at->toDateTimeString(),
                'updated_at'           => $project->application->updated_at->toDateTimeString(),
            ] : null,

            // 当前登录设计师的邀请信息
            'invitation'  => $project->invitation ? [
                'id'            => (int)$project->invitation->id,
                'status'        => (int)$project->invitation->status,
                'refusal_cause' => $project->invitation->refusal_cause,
                'created_at'    => $project->invitation->created_at->toDateTimeString(),
                'updated_at'    => $project->invitation->updated_at->toDateTimeString(),
            ] : null,

            // 当前登录设计师的交付信息
            'delivery'    => $project->delivery ? [
                'id'         => $project->delivery->id,
                'remark'     => $project->delivery->remark,
                'file_url'   => $project->delivery->file_url,
                'created_at' => $project->delivery->created_at->toDateTimeString(),
                'updated_at' => $project->delivery->updated_at->toDateTimeString(),
            ] : null
        ];
        return array_merge($basic, $extra);
    }
}