<?php

namespace App\Http\Controllers;

use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(20);
        return $this->response->paginator($notifications, new NotificationTransformer());
    }
}
