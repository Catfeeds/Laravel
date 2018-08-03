<?php

namespace App\Models;

use Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable {
        notify as protected laravelNotify;
    }

    // 重写的主要目的是每次通知时通知数+1
    public function notify($instance)
    {
        // 不能自己通知自己
        if ($this->id == Auth::id()) {
            return;
        }
        $this->laravelNotify($instance);  // $instance 就是 Notifications\ActivityReplied 等对象
        $this->increment('notification_count');
    }

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

    public function activities()
    {
        return $this->hasMany('App\Models\Activity');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
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

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function markOneAsRead($notificationId)
    {
        $notification = DatabaseNotification::find($notificationId);
        if ($notification->unread()) {
            $this->decrement('notification_count');
            $notification->markAsRead();
        }
    }

    // 设置following属性：$user用户是否关注了此用户
    public function setFollowing($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        if (!$user) {
            return $this->attributes['following'] = false;
        }
        $this->attributes['following'] = $this->followers()
                ->newPivotStatementForId($user)
                ->value('follower_id') === $user;
    }
}
