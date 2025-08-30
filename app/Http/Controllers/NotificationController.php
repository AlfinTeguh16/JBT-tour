<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->paginate(20);

            return view('notifications.index', compact('notifications'));
        } catch (\Throwable $e) {
            Log::error('Notifications index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat notifikasi.');
        }
    }

    public function show(Notification $notification)
    {
        try {
            $user = Auth::user();
            if ($notification->user_id !== $user->id) {
                Log::warning('Notification show forbidden', ['viewer_id' => $user->id, 'notification_id' => $notification->id]);
                abort(403);
            }

            return view('notifications.show', compact('notification'));
        } catch (\Throwable $e) {
            Log::error('Notification show error', ['notification_id' => $notification->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat notifikasi.');
        }
    }

    public function markAsRead(Notification $notification)
    {
        try {
            $user = Auth::user();
            if ($notification->user_id !== $user->id) {
                Log::warning('Notification markAsRead forbidden', ['user_id' => $user->id, 'notification_id' => $notification->id]);
                abort(403);
            }

            $notification->update(['is_read' => true]);
            Log::info('Notification marked as read', ['notification_id' => $notification->id]);

            return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
        } catch (\Throwable $e) {
            Log::error('Notification markAsRead error', ['notification_id' => $notification->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menandai notifikasi.');
        }
    }
}
