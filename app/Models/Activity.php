<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 下午6:15
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['content', 'photo_urls'];
    protected $casts = [
        'photo_urls' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}