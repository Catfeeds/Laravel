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
            'area'                   => $project->area,
            'description'            => $project->description,
            'project_file_url'       => $project->project_file_url,
            'delivery_time'          => $project->delivery_time,
            'payment'                => $project->payment,
            'supplement_description' => $project->supplement_description,
            'supplement_file_url'    => $project->supplement_file_url,
            'supplement_at'          => $project->supplement_at,
            'find_time'              => $project->find_time,
            'favoriting'             => (boolean)$project->favoriting,
            'applying'               => (boolean)$project->applying,
            'created_at'             => $project->created_at->toDateTimeString(),
            'updated_at'             => $project->updated_at->toDateTimeString(),
            'canceled_at'            => $project->canceled_at ? $project->canceled_at->toDateTimeString() : '',

            // 收藏人数
            'favorite_count'         => $project->favorite_count,

            // 发布者信息
            'user'                   => (new UserTransformer())->transform($project->user),

            // 当前登录设计师的报名信息
            'application'            => $project->application ? [
                'id'                   => $project->application->id,
                'remark'               => $project->application->remark,
                'application_file_url' => $project->application->application_file_url,
                'created_at'           => $project->application->created_at->toDateTimeString(),
                'updated_at'           => $project->application->updated_at->toDateTimeString(),
            ] : null,

            // 报名人数
            'application_count'      => $project->applications()->count(),

            // 报名信息：20个
            'applications'             => $project->applications()->recent()->limit(20)->get()
        ];
    }
}