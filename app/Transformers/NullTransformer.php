<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/5
 * Time: 上午10:05
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class NullTransformer extends TransformerAbstract
{
    public function transform($null)
    {
        return [];
    }
}