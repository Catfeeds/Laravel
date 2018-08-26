<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // 获取平台的设计师，随机返回20名
    public function designers() {
        $users = User::where('type', 'designer')->inRandomOrder()->limit(20)->get();
        return $this->response->collection($users, new UserTransformer());
    }
}
