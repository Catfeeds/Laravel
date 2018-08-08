<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use softDeletes;
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
