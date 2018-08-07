<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/7
 * Time: 下午11:45
 */

namespace App\Transformers;


use App\Models\ProjectApplication;
use League\Fractal\TransformerAbstract;

class ProjectApplicationTransformer extends TransformerAbstract
{
    public function transform(ProjectApplication $projectApplication)
    {
        return [
            'id'                   => $projectApplication->id,
            'user_id'              => $projectApplication->user_id,
            'project_id'           => $projectApplication->project_id,
            'remark'               => $projectApplication->remark,
            'application_file_url' => $projectApplication->application_file_url,
            'created_at'           => $projectApplication->created_at->toDateTimeString(),
            'updated_at'           => $projectApplication->updated_at->toDateTimeString(),
            'user'                 => (new UserTransformer())->transform($projectApplication->user),
        ];
    }
}