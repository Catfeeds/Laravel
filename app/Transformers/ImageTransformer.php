<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 上午11:40
 */

namespace App\Transformers;

use App\Models\Upload;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform(Upload $image)
    {
        return [
            'id'         => $image->id,
            'user_id'    => $image->user_id,
            'type'       => $image->type,
            'path'       => $image->path,
            'created_at' => $image->created_at->toDateTimeString(),
            'updated_at' => $image->updated_at->toDateTimeString(),
        ];
    }
}