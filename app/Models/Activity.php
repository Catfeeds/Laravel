<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 下午6:15
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use softDeletes;
    protected $fillable = ['content', 'photo_urls'];
    protected $casts = [
        'photo_urls' => 'array'
    ];
    protected $visible = ['id', 'content', 'photo_urls', 'like_count', 'reply_count', 'created_at', 'updated_at', 'user'];
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function likes() {
        return $this->hasMany(ActivityLike::class);
    }

    // 设置liked属性：$user用户是否点赞了这条动态
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