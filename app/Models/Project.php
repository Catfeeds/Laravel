<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use softDeletes;
    protected $guarded = [];
    protected $casts = [
        'types' => 'array',
        'features' => 'array',
        'keywords' => 'array',
        'canceled_at' => 'datetime'
    ];
    protected $with=['user'];

    const STATUS_CANCELED = 500; // 已取消
    const STATUS_REVIEW_FAILED = 600; // 审核未通过
    const STATUS_REVIEWING = 900; // 审核中
    const STATUS_TENDERING = 1000; // 招标中
    const STATUS_WORKING = 1100; // 作标中
    const STATUS_COMPLETED = 1200; // 已完成

    // 作者
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 报名列表
    public function applications() {
        return $this->hasMany(ProjectApplication::class);
    }

    // 收藏者列表
    public function favoriteUser() {
        return $this->hasMany(ProjectFavorite::class);
    }

    // 用户是否报名了该项目
    public function setApplying($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        if (!$user) {
            return $this->attributes['applying'] = false;
        }
        $this->attributes['applying'] = $this->applications()
            ->where('user_id', $user)
            ->exists();
    }

    // 用户是否收藏了该项目
    public function setFavoriting($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        if (!$user) {
            return $this->attributes['favoriting'] = false;
        }
        $this->attributes['favoriting'] = $this->favoriteUser()
            ->where('user_id', $user)
            ->exists();
    }

    // 一次性设置所有的额外属性
    public function setExtraAttributes($user) {
        $this->setApplying($user);
        $this->setFavoriting($user);
    }
}
