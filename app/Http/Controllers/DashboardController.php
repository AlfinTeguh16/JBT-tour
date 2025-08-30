<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Order;
use App\Models\Assignment;
use App\Models\WorkSession;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Admin: ringkasan global + monitoring operasional (tanpa jam kerja pribadi).
     */
    public function admin(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();

        // Ringkasan global
        $summary = [
            'total_users'     => User::count(),
            'total_customers' => Customer::count(),
            'total_vehicles'  => Vehicle::count(),
            'total_orders'    => Order::count(),
        ];

        // Status orders
        $orderStatusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Order baru hari ini
        $todayOrders = Order::whereDate('requested_at', $today)
            ->with('customer')
            ->latest('requested_at')
            ->take(10)
            ->get();

        // Assignment aktif/akan datang (untuk monitoring)
        $upcomingAssignments = Assignment::with(['order.customer','vehicle','driver','guide'])
            ->whereIn('status', ['assigned','in_progress'])
            ->where(function ($q) use ($now) {
                $q->whereNull('scheduled_end')->orWhere('scheduled_end', '>=', $now);
            })
            ->orderBy('scheduled_start', 'asc')
            ->take(10)
            ->get();

        // Utilisasi kendaraan hari ini
        $vehicleUtilizationToday = Assignment::selectRaw('vehicle_id, COUNT(*) as jobs')
            ->whereDate('scheduled_start', $today)
            ->whereNotNull('vehicle_id')
            ->groupBy('vehicle_id')
            ->with('vehicle')
            ->get();

        // Notifikasi untuk admin yang login
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.admin', compact(
            'user',
            'summary',
            'orderStatusCounts',
            'todayOrders',
            'upcomingAssignments',
            'vehicleUtilizationToday',
            'notifications'
        ));
    }

    /**
     * Staff: fokus antrian order & penugasan (tanpa jam kerja pribadi).
     */
    public function staff(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd   = $now->copy()->endOfWeek();

        $summary = [
            'orders_pending'      => Order::where('status', 'pending')->count(),
            'orders_assigned'     => Order::where('status', 'assigned')->count(),
            'orders_in_progress'  => Order::where('status', 'in_progress')->count(),
            'orders_completed_week' => Order::whereBetween('service_date', [$weekStart, $weekEnd])
                ->where('status', 'completed')->count(),
        ];

        $pendingOrders = Order::with('customer')
            ->where('status', 'pending')
            ->orderBy('requested_at', 'asc')
            ->take(15)
            ->get();

        $upcomingAssignments = Assignment::with(['order.customer','vehicle','driver','guide'])
            ->whereIn('status', ['assigned','in_progress'])
            ->where(function ($q) use ($now) {
                $q->whereNull('scheduled_end')->orWhere('scheduled_end', '>=', $now);
            })
            ->orderBy('scheduled_start', 'asc')
            ->take(15)
            ->get();

        $todaySchedule = Assignment::with(['order.customer','vehicle','driver','guide'])
            ->whereDate('scheduled_start', $today)
            ->orderBy('scheduled_start')
            ->get();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.staff', compact(
            'user',
            'summary',
            'pendingOrders',
            'upcomingAssignments',
            'todaySchedule',
            'notifications'
        ));
    }

    /**
     * Driver: tugas milik driver + jam kerja (driver/guide saja yang punya jam kerja).
     */
    public function driver(Request $request)
    {
        $user = Auth::user(); // pastikan role=driver oleh middleware
        $now = Carbon::now();
        $today = $now->toDateString();
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd   = $now->copy()->endOfWeek();

        $myUpcoming = Assignment::with(['order.customer','vehicle'])
            ->where('driver_id', $user->id)
            ->whereIn('status', ['assigned','in_progress'])
            ->where(function ($q) use ($now) {
                $q->whereNull('scheduled_end')->orWhere('scheduled_end', '>=', $now);
            })
            ->orderBy('scheduled_start', 'asc')
            ->take(10)
            ->get();

        $myToday = Assignment::with(['order.customer','vehicle'])
            ->where('driver_id', $user->id)
            ->whereDate('scheduled_start', $today)
            ->orderBy('scheduled_start', 'asc')
            ->get();

        // Jam kerja hanya dihitung untuk driver/guide
        $myHoursWeek = WorkSession::where('user_id', $user->id)
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->sum('hours_decimal');

        $myHoursMonth = WorkSession::where('user_id', $user->id)
            ->whereYear('started_at', $now->year)
            ->whereMonth('started_at', $now->month)
            ->sum('hours_decimal');

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.driver', compact(
            'user',
            'myUpcoming',
            'myToday',
            'myHoursWeek',
            'myHoursMonth',
            'notifications'
        ));
    }

    /**
     * Guide: tugas milik guide + jam kerja.
     */
    public function guide(Request $request)
    {
        $user = Auth::user(); // pastikan role=guide oleh middleware
        $now = Carbon::now();
        $today = $now->toDateString();
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd   = $now->copy()->endOfWeek();

        $myUpcoming = Assignment::with(['order.customer','vehicle'])
            ->where('guide_id', $user->id)
            ->whereIn('status', ['assigned','in_progress'])
            ->where(function ($q) use ($now) {
                $q->whereNull('scheduled_end')->orWhere('scheduled_end', '>=', $now);
            })
            ->orderBy('scheduled_start', 'asc')
            ->take(10)
            ->get();

        $myToday = Assignment::with(['order.customer','vehicle'])
            ->where('guide_id', $user->id)
            ->whereDate('scheduled_start', $today)
            ->orderBy('scheduled_start', 'asc')
            ->get();

        // Jam kerja hanya dihitung untuk driver/guide
        $myHoursWeek = WorkSession::where('user_id', $user->id)
            ->whereBetween('started_at', [$weekStart, $weekEnd])
            ->sum('hours_decimal');

        $myHoursMonth = WorkSession::where('user_id', $user->id)
            ->whereYear('started_at', $now->year)
            ->whereMonth('started_at', $now->month)
            ->sum('hours_decimal');

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.guide', compact(
            'user',
            'myUpcoming',
            'myToday',
            'myHoursWeek',
            'myHoursMonth',
            'notifications'
        ));
    }
}
