<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/7
 * Time: 下午11:45
 */

namespace App\Transformers;


use App\Models\ProjectDelivery;
use League\Fractal\TransformerAbstract;

class ProjectDeliveryTransformer extends TransformerAbstract
{
    public function transform(ProjectDelivery $delivery)
    {
        return [
            'id'         => $delivery->id,
            'user_id'    => $delivery->user_id,
            'project_id' => $delivery->project_id,
            'remark'     => $delivery->remark,
            'file_url'   => $delivery->file_url,
            'created_at' => $delivery->created_at->toDateTimeString(),
            'updated_at' => $delivery->updated_at->toDateTimeString(),
            'user'       => (new UserTransformer())->transform($delivery->user),
        ];
    }
}