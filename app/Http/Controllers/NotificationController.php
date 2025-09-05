<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\WorkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;


class NotificationController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->paginate(20);

            return view('activities.notifications.index', compact('notifications'));
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

            return view('activities.notifications.show', compact('notification'));
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

    public function approve(Notification $notification)
    {
        try {
            Log::info('Approve notification attempt', [
                'notification_id' => $notification->id,
                'user_id'         => auth()->id(),
                'notif_user_id'   => $notification->user_id,
                'status'          => $notification->status,
            ]);

            if ($notification->status !== 'pending') {
                Log::warning('Approve notification skipped - already processed', [
                    'notification_id' => $notification->id,
                    'status'          => $notification->status,
                ]);
                return back()->with('error', 'Notifikasi sudah diproses.');
            }

            $assignment = $notification->assignment;
            if (!$assignment) {
                Log::error('Approve notification failed - assignment not found', [
                    'notification_id' => $notification->id,
                ]);
                return back()->with('error', 'Assignment tidak ditemukan.');
            }

            $workSession = WorkSession::create([
                'user_id'       => $notification->user_id,
                'assignment_id' => $assignment->id,
                'started_at'    => $assignment->scheduled_start ?? Carbon::now(),
                'hours_decimal' => null,
            ]);

            $notification->update([
                'status'  => 'approved',
                'is_read' => true,
            ]);

            Log::info('Notification approved & work session created', [
                'notification_id' => $notification->id,
                'assignment_id'   => $assignment->id,
                'work_session_id' => $workSession->id,
                'user_id'         => $notification->user_id,
            ]);

            return back()->with('success', 'Tugas disetujui & jam kerja dimulai.');
        } catch (\Throwable $e) {
            Log::error('Approve notification error', [
                'notification_id' => $notification->id ?? null,
                'error'           => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Gagal approve notifikasi.');
        }
    }

    public function decline(Notification $notification)
    {
        try {
            Log::info('Decline notification attempt', [
                'notification_id' => $notification->id,
                'user_id'         => auth()->id(),
                'notif_user_id'   => $notification->user_id,
                'status'          => $notification->status,
            ]);

            if ($notification->status !== 'pending') {
                Log::warning('Decline notification skipped - already processed', [
                    'notification_id' => $notification->id,
                    'status'          => $notification->status,
                ]);
                return back()->with('error', 'Notifikasi sudah diproses.');
            }

            $notification->update([
                'status'  => 'declined',
                'is_read' => true,
            ]);

            Log::info('Notification declined', [
                'notification_id' => $notification->id,
                'user_id'         => $notification->user_id,
            ]);

            return back()->with('success', 'Tugas ditolak.');
        } catch (\Throwable $e) {
            Log::error('Decline notification error', [
                'notification_id' => $notification->id ?? null,
                'error'           => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Gagal decline notifikasi.');
        }
    }
}
