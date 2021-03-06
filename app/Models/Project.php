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
        'canceled_at' => 'datetime',
        'completed_at' => 'datetime',
        'remittance_submitted_at' => 'datetime',
        'payment_remark_updated_at' => 'datetime'
    ];
    protected $with=['user'];

    const STATUS_CANCELED = 500; // 已取消
    const STATUS_REVIEW_FAILED = 600; // 审核未通过
    const STATUS_REVIEWING = 900; // 审核中
    const STATUS_TENDERING = 1000; // 招标中
    const STATUS_WORKING = 1100; // 作标中
    const STATUS_COMPLETED = 1200; // 已完成

    const PUBLIC_STATUS = [
        Project::STATUS_TENDERING,
        Project::STATUS_WORKING,
        Project::STATUS_COMPLETED
    ];

    const ALL_STATUS = [
        Project::STATUS_TENDERING,
        Project::STATUS_WORKING,
        Project::STATUS_COMPLETED,
        Project::STATUS_REVIEWING,
        Project::STATUS_REVIEW_FAILED,
        Project::STATUS_CANCELED
    ];

    const DESIGNER_ORDER_STATUS = [
        Project::STATUS_TENDERING,
        Project::STATUS_WORKING,
        Project::STATUS_COMPLETED,
        Project::STATUS_CANCELED
    ];

    // 作者
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 收藏者列表
    public function favoriteUser() {
        return $this->hasMany(ProjectFavorite::class);
    }

    // 报名列表
    public function applications() {
        return $this->hasMany(ProjectApplication::class);
    }

    // 邀请列表
    public function invitations() {
        return $this->hasMany(ProjectInvitation::class);
    }

    // 交付列表
    public function deliveries() {
        return $this->hasMany(ProjectDelivery::class);
    }

    // 甲方的真实汇款信息
    public function remit() {
        return $this->hasOne(ProjectRemittance::class);
    }

    // 奖金发放列表
    public function payments() {
        return $this->hasMany(Payment::class);
    }

    // 所有人都能访问的项目
    function scopePublic($query) {
        $query->whereIn('status', Project::PUBLIC_STATUS)->where('mode', 'free');
    }

    // 获取所有参与者：报名项目的人（自由式）或同意邀请的人（其他）
    // $is_accepted：是否必须要接受邀请？
    function getParticipants($is_accepted = true) {
        $id = $this->id;
        if($this->mode === 'free') {
            return User::whereHas('applications', function ($query) use ($id) {
                $query->where('project_id', $id);
            })->get();
        } else {
            return User::whereHas('projectInvitations', function ($query) use ($id, $is_accepted) {
                $query->where('project_id', $id);
                if($is_accepted) {
                    $query->where('status', ProjectInvitation::STATUS_ACCEPTED);
                }
            })->get();
        }
    }

    // 是否是所有人都能访问的项目
    function isPublic() {
        return in_array($this->status, Project::PUBLIC_STATUS) && $this->mode === 'free';
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
