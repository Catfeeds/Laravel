<?php

namespace App\Models;

class Work extends Model
{
    protected $with = ['user'];
    protected $casts = [
        'photo_urls' => 'array'
    ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes() {
        return $this->hasMany(WorkLike::class);
    }

    public function scopePublic($query) {
        return $query->where('visible_range', 'public');
    }

    // 设置liked属性：$user用户是否点赞了这个作品
    public function setLiked($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }
        if (!$user) {
            return $this->attributes['liked'] = false;
        }
        $this->attributes['liked'] = $this->likes()
            ->where('user_id', $user)
            ->exists();
    }
}
