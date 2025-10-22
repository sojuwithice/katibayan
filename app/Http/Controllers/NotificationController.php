<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = Notification::where('user_id', $user->id)
                            ->where('is_read', false)
                            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get notifications for the user
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
                                    ->with('evaluation.event')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get()
                                    ->map(function ($notification) {
                                        return [
                                            'id' => $notification->id,
                                            'message' => $notification->message,
                                            'is_read' => $notification->is_read,
                                            'created_at' => $notification->created_at->diffForHumans(),
                                            'type' => $notification->type
                                        ];
                                    });

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
                                    ->where('id', $id)
                                    ->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now()
                    ]);

        return response()->json(['success' => true]);
    }
}