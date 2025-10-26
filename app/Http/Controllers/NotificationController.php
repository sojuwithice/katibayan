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

    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
                                  ->where('user_id', Auth::id()) // Siguraduhin na 'yung user ang may-ari
                                  ->first();

        if ($notification) {
            $notification->is_read = 1; // 1 = read
            $notification->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }
}