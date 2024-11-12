<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    public function getNotifications()
    {
        $notifications = auth()->user()->notifications;
        $notificationsCount = $notifications->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notificationsCount,
        ]);
    }
}
