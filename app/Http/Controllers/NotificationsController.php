<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        if($request->type === 'unread') {
            $notifications = $this->user->unreadNotifications()->paginate(20);
        } else {
            $notifications = $this->user->notifications()->paginate(20);
        }
        return $this->response->paginator($notifications, new NotificationTransformer());
    }

    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }

    public function readAll()
    {
        $this->user()->markAsRead();
        return $this->response->noContent();
    }

    public function readOne(Notification $notification)
    {
        $this->authorize('update', $notification);
        $this->user()->markOneAsRead($notification);
        return $this->response->noContent();
    }

    public function destroy(Notification $notification) {
        $this->authorize('destroy', $notification);
        $this->user()->markOneAsRead($notification); // 删除之前先标记为已读
        $notification->delete();
        return $this->response->noContent();
    }

    public function destroyAll() {
        $currentUser = $this->user();
        $currentUser->markAsRead(); // 删除之前先标记为已读
        $currentUser->notifications()->delete();
        return $this->response->noContent();
    }
}
