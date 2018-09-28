<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserReviewController extends Controller
{
    public function pass(User $user)
    {
        $user->review_status = 1;
        $user->save();
        admin_toastr('审核成功：通过', 'success');
        return back();
    }


    public function fail(User $user)
    {
        $user->review_status = 2;
        $user->save();
        admin_toastr('审核成功：未通过', 'success');
        return back();
    }
}
