<?php

namespace App\Models;

class Review extends Model
{
    protected $with=['reviewer'];

    // 被评价的人
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 发出评价的人
    public function reviewer(){
        return $this->belongsTo(User::class);
    }
}
