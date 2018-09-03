<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 下午6:19
 */

namespace App\Transformers;

use App\Models\Work;
use League\Fractal\TransformerAbstract;

class WorkTransformer extends TransformerAbstract
{
    public function transform(Work $work)
    {
        return [
            'id'          => $work->id,
            'user_id'     => $work->user_id,
            'title'       => $work->title,
            'description' => $work->description,
            'photo_urls'  => $work->photo_urls ?? [],
            'like_count'  => $work->like_count,
            'created_at'  => $work->created_at->toDateTimeString(),
            'updated_at'  => $work->updated_at->toDateTimeString(),
            'user'        => (new UserTransformer())->transform($work->user),
            'visible_range' => $work->visible_range,

            // 附加属性
            'liked'       => (boolean)$work->liked,
        ];
    }
}