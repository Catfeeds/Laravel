<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 下午6:19
 */

namespace App\Transformers;


use App\Models\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'author'
    ];

    public function transform(Activity $activity)
    {
        return [
            'id'         => $activity->id,
            'content'    => $activity->content,
            'photo_urls' => $activity->photo_urls,
            'created_at' => $activity->created_at->toDateTimeString(),
            'updated_at' => $activity->updated_at->toDateTimeString(),
            'author'     => $this->item($activity->user, new UserTransformer()),
        ];
    }

    public function includeAuthor(Activity $activity)
    {
        return $this->item($activity->user, new UserTransformer());
    }
}