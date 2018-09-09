<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDelivery extends Model
{
    protected $guarded = [];
    protected $with = ['user'];

    // 所属设计师
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 所属项目
    public function project(){
        return $this->belongsTo(Project::class);
    }
}
