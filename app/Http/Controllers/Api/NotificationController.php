<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->get()
            ->map(fn (DatabaseNotification $notification) => $this->transformNotification($notification))
            ->values();

        return response()->json([
            'message' => 'Notifications fetched successfully',
            'status' => true,
            'data' => [
                'items' => $notifications,
                'unread_count' => $user->unreadNotifications()->count(),
            ],
        ]);
    }

    public function markAsRead(Request $request, string $notificationId)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found',
                'status' => false,
            ], 404);
        }

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json([
            'message' => 'Notification marked as read successfully',
            'status' => true,
            'data' => [
                'notification' => $this->transformNotification($notification->fresh()),
            ],
        ]);
    }

    private function transformNotification(DatabaseNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? null,
            'body' => $notification->data['body'] ?? null,
            'type' => $notification->data['type'] ?? null,
            'appointment_id' => $notification->data['appointment_id'] ?? null,
            'is_read' => $notification->read_at !== null,
            'read_at' => optional($notification->read_at)?->toDateTimeString(),
            'created_at' => optional($notification->created_at)?->toDateTimeString(),
        ];
    }
}
