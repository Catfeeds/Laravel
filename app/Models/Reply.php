<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    use SoftDeletes;

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
