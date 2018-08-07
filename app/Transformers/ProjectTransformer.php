<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/6
 * Time: 下午8:28
 */

namespace App\Transformers;

use App\Models\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    public function transform(Project $project)
    {
        return [
            'id'                     => $project->id,
            'user_id'                => $project->user_id,
            'status'                 => $project->status,
            'title'                  => $project->title,
            'types'                  => $project->types,
            'features'               => $project->features,
            'area'                   => $project->area,
            'description'            => $project->description,
            'project_file_url'       => $project->project_file_url,
            'delivery_time'          => $project->delivery_time,
            'payment'                => $project->payment,
            'supplement_description' => $project->supplement_description,
            'supplement_file_url'    => $project->supplement_file_url,
            'supplement_at'          => $project->supplement_at,
            'find_time'              => $project->find_time,
            'created_at'             => $project->created_at->toDateTimeString(),
            'updated_at'             => $project->updated_at->toDateTimeString(),
            'canceled_at'            => $project->canceled_at,
            'favorite_count'         => $project->favorite_count,
            'user'                   => (new UserTransformer())->transform($project->user),
        ];
    }
}