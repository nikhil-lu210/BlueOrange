<?php

namespace App\Http\Controllers\Administration\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsReadAndRedirect($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();

            if (isset($notification->data['url'])) {
                // Get the URL from the notification data and redirect
                return redirect($notification->data['url']);
            } else {
                return redirect()->back();
            }
            
        }

        return redirect()->back();
    }

    public function markAllAsRead() {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}
