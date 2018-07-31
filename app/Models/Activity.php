<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 下午6:15
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = ['content', 'photo_urls'];
    protected $casts = [
        'photo_urls' => 'array'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}