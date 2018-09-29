<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecommendedUser;
use App\Models\User;

class UserRecommendController extends Controller
{
    public function recommend(User $user)
    {
        RecommendedUser::where('user_id', $user->id)->delete();
        RecommendedUser::create(['user_id' => $user->id]);
        admin_toastr('推荐成功，该设计师会在平台首页置顶显示', 'success');
        return back();
    }


    public function cancelRecommend(User $user)
    {
        RecommendedUser::where('user_id', $user->id)->delete();
        admin_toastr('取消推荐成功', 'success');
        return back();
    }
}
