<?php
/**
 * User: ZhuKaihao
 * Date: 2018/9/2
 * Time: 下午6:14
 */

namespace App\Transformers;


use App\Models\Project;
use League\Fractal\TransformerAbstract;

class ProjectForDesignerTransformer extends TransformerAbstract
{
    public function transform(Project $project)
    {
        $basic = (new ProjectTransformer())->transform($project);
        $extra = [
            // 当前登录设计师的报名信息
            'application'            => $project->application ? [
                'id'                   => $project->application->id,
                'remark'               => $project->application->remark,
                'application_file_url' => $project->application->application_file_url,
                'created_at'           => $project->application->created_at->toDateTimeString(),
                'updated_at'           => $project->application->updated_at->toDateTimeString(),
            ] : null
        ];
        return array_merge($basic, $extra);
    }
}