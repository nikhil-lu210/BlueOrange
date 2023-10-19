<?php

namespace App\Http\Controllers\Administration\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index() {
        $notifications = auth()->user()->notifications;
        
        return view('administration.notification.index', compact(['notifications']));
    }


    public function markAsReadAndRedirect($notification_id)
    {
        $notification = auth()->user()->notifications()->find($notification_id);

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

        toast('All Notification Has Been Marked As Read.', 'success');
        return redirect()->back();
    }

    public function destroy($notification_id) {
        $notification = Auth::user()->notifications->find($notification_id);

        if ($notification) {
            $notification->delete();
        }

        toast('Notification Has Been Deleted.', 'success');
        return redirect()->back();
    }

    public function destroyAll() {
        $notifications = Auth::user()->notifications;

        foreach ($notifications as $notification) {
            $notification->delete();
        }        

        toast('All Notification Has Been Deleted.', 'success');
        return redirect()->back();
    }
}
