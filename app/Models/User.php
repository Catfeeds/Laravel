<?php

namespace App\Models;

use App\Notifications\ActivateEmail;
use Auth;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use softDeletes;
    use Messagable;
    use Notifiable {
        notify as public laravelNotify;
    }

    // 重写的主要目的是每次通知时通知数+1
    // 这里要注意，发送邮件的时候不需要增加通知数，应该调用laravelNotify
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
    protected $guarded = [];

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

    public function works() {
        return $this->hasMany(Work::class);
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

    public function markOneAsRead(Notification $notification)
    {
        if ($notification->unread()) {
            $this->decrement('notification_count');
            $notification->markAsRead();
        }
    }

    public function sendActiveMail() {
        $token = bcrypt($this->email.time());
        $emailToken = EmailToken::firstOrCreate(['email' => $this->email]);
        $emailToken->update([ 'token' => $token ]);
        $this->laravelNotify(new ActivateEmail($token));
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
