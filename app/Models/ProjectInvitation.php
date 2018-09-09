<?php

namespace App\Models;

class ProjectInvitation extends Model
{
    protected $with=['invitedUser'];
    protected $guarded = [];

    const STATUS_NOT_VIEWED = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_DECLINED = 2;


    public function invitedUser(){
        return $this->belongsTo(User::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
