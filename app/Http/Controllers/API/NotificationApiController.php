<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    // Get all notifications for the authenticated user
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'icon' => $notification->data['icon'] ?? 'fa fa-bell',
                    'objective' => $notification->data['objective'] ?? '',
                    'url' => $notification->data['url'] ?? '#',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                ];
            })
        ]);
    }

    // Mark a notification as read
    public function markAsRead(Request $request, $id)
    {
        $notification = DatabaseNotification::find($id);
        if ($notification && $notification->notifiable_id == $request->user()->id) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Notification not found or unauthorized'], 404);
    }

    // Mark all notifications as read
    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
