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

/**
 * 没有登录的用户能获取到的有限的项目信息
 * Class SimpleProjectTransformer
 * @package App\Transformers
 */
class SimpleProjectTransformer extends TransformerAbstract
{
    public function transform(Project $project)
    {
        return [
            'id'                => $project->id,
            'user_id'           => $project->user_id,
            'status'            => $project->status,
            'mode'            => $project->mode,
            'title'             => $project->title,
            'types'             => (array)$project->types,
            'features'          => (array)$project->features,
            'keywords'          => (array)$project->keywords,
            'created_at'        => $project->created_at->toDateTimeString(),
            'updated_at'        => $project->updated_at->toDateTimeString(),
            'canceled_at'       => $project->canceled_at ? $project->canceled_at->toDateTimeString() : '',
            'user'              => (new UserTransformer())->transform($project->user),
            'favorite_count'    => $project->favorite_count,
            'application_count' => $project->applications()->count(), // 报名人数
            'invitation_count' => $project->invitations()->count(), // 邀请人数

            'favoriting' => (boolean)$project->favoriting, // 是否收藏
            'applying'   => (boolean)$project->applying, // 是否报名
        ];
    }
}