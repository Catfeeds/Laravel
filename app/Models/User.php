<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'type', 'avatar_url', 'title', 'introduction', 'birthday', 'sex'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function followings()
    {
        return $this->belongsToMany('App\Models\User', 'follow', 'follower_id', 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany('App\Models\User', 'follow', 'user_id', 'follower_id');
    }

    // 关注的人的所有动态
    public function activities()
    {
        return $this->hasMany('App\Models\Activity');
    }

    // 关注的人的所有动态
    public function feeds()
    {
        return $this->hasManyThrough('App\Models\Activity', 'App\Models\Follow', 'follower_id', 'user_id', 'id', 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
}
