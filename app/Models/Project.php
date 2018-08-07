<?php

namespace App\Models;

class Project extends Model
{
    protected $guarded = [];
    protected $casts = [
        'types' => 'array',
        'features' => 'array'
    ];
    protected $with=['user'];

    const STATUS_CANCELED = 500; // 已取消
    const STATUS_TENDERING = 1000; // 招标中
    const STATUS_WORKING = 1100; // 作标中
    const STATUS_COMPLETED = 1200; // 已完成

    // 作者
    public function user(){
        return $this->belongsTo(User::class);
    }
}
