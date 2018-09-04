<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/6
 * Time: 下午8:28
 */

namespace App\Transformers;

use App\Models\Project;
use App\Models\ProjectApplication;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    public function transform(Project $project)
    {
        return [
            'id'                     => $project->id,
            'user_id'                => $project->user_id,
            'status'                 => $project->status,
            'review_message'         => $project->review_message,
            'title'                  => $project->title,
            'types'                  => $project->types,
            'features'               => $project->features,
            'keywords'               => $project->keywords,
            'depth'                => $project->depth,
            'description'            => $project->description,
            'project_file_url'       => $project->project_file_url,
            'delivery_time'          => $project->delivery_time,
            'payment'                => $project->payment,
            'find_time'              => $project->find_time,
            'created_at'             => $project->created_at->toDateTimeString(),
            'updated_at'             => $project->updated_at->toDateTimeString(),
            'canceled_at'            => $project->canceled_at ? $project->canceled_at->toDateTimeString() : '',
            'user'                   => (new UserTransformer())->transform($project->user),
            'favorite_count'         => $project->favorite_count,
            'application_count'      => $project->applications()->count(), // 报名人数


            'favoriting'             => (boolean)$project->favoriting, // 是否收藏
            'applying'               => (boolean)$project->applying, // 是否报名
        ];
    }
}