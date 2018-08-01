<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 下午6:15
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function scopeRecent($query)
    {
        return $query->orderBy('id', 'desc');
    }
}