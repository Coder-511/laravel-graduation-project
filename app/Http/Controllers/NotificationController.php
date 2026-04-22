<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ─── Get all notifications for the authenticated user ────────

    public function index(): \Illuminate\Contracts\View\View
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // ─── Get only unread notifications ───────────────────────────

    public function unread(): JsonResponse
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->unread()
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notifications);
    }

    // ─── Mark a single notification as read ──────────────────────

    public function markAsRead(int $id): JsonResponse
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', Auth::id())         // user can only mark their own
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Marked as read.']);
    }

    // ─── Mark all as read ────────────────────────────────────────

    public function markAllAsRead(): JsonResponse
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    // ─── Delete a single notification ────────────────────────────

    public function destroy(int $id): JsonResponse
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return response()->json(['message' => 'Notification deleted.']);
    }
}