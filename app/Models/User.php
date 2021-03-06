<?php

namespace App\Models;

use App\Notifications\ActivateEmail;
use App\Policies\ReviewPolicy;
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

    protected $guarded = [];
//    protected $hidden = [
//        'password' // 取消hidden，管理系统要用到
//    ];
    protected $casts = [
        'qualification_urls'  => 'array',
        'professional_fields' => 'array'
    ];

    // 根据完成项目数从高到低排序
    // 完成项目以收到设计费为准
    public function scopeOrderByCompletedProjects($query)
    {
        return $query->withCount('payments')->orderBy('payments_count', 'desc');
    }

    // 该用户（设计师）是否被推荐
    public function recommendation()
    {
        return $this->hasOne(RecommendedUser::class);
    }

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

    // 发布的项目
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // 作品集
    public function works()
    {
        return $this->hasMany(Work::class);
    }

    // 发出的邀请
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // 收到的邀请：用来评价的邀请
    public function receivedInvitations()
    {
        return $this->hasMany(Invitation::class, 'invited_user_id');
    }

    // 收到的评价
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // 发表的评价
    public function postedReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    // 报名
    public function applications()
    {
        return $this->hasMany(ProjectApplication::class);
    }

    // 收到的项目邀请
    public function projectInvitations()
    {
        return $this->hasMany(ProjectInvitation::class);
    }

    // 设计费
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * 发送数据库通知，每次通知时通知数+1
     * 发送邮件的时候不需要增加通知数，应该调用notifyByEmail
     * 数据库存的是其他用户对该用户的通知，如评论了动态等，因此这类通知不能自己通知自己
     */
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
     * 发送邮件通知，相当于系统向该用户发送通知
     * @param $instance
     */
    public function notifyViaEmail($instance)
    {
        $this->laravelNotify($instance);
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

    // 设置following属性：当前登录用户$user是否关注了此用户
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

    // 设置review_status属性：此用户对当前登录用户$user的评价状态
    // inviting: $user邀请此用户评价
    // reviewed: 此用户已经评价$user
    public function setReviewStatusToUser($user)
    {
        if (!$user) {
            return $this->attributes['review_status'] = null;
        }
        if ($this->receivedInvitations()->where('user_id', $user->id)->exists()) {
            $this->attributes['review_status'] = 'inviting'; // 已邀请
        } else if ($user->reviews()->where('reviewer_id', $this->id)->exists()) {
            $this->attributes['review_status'] = 'reviewed'; // 已评价
        } else {
            $this->attributes['review_status'] = null; // 未邀请
        }
    }

    // 用户$user是否可以评价此用户$this
    public function setCanReview($user)
    {
        $KEY = 'can_review';

        if (!$user) {
            return $this->attributes[$KEY] = false;
        }

        try {
            $res = (new ReviewPolicy())->store($user, $this);
        } catch (\Exception $exception) {
            $res = false;
        }

        return $this->attributes[$KEY] = $res;
    }

    // 一次性设置所有的额外属性
    public function setExtraAttributes($user)
    {
        $this->setFollowing($user);
        $this->setReviewStatusToUser($user);
        $this->setCanReview($user);
    }
}
