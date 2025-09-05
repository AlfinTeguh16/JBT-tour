<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    public function index()
    {
        // dd(Auth::user()->role);
        try {
            $user = Auth::user();

            if (in_array($user->role, ['admin','staff'])) {
                $assignments = Assignment::with(['order.customer','driver','guide','vehicle'])
                    ->latest('scheduled_start')->paginate(15);
            } elseif ($user->role === 'driver') {
                $assignments = Assignment::with(['order.customer','vehicle'])
                    ->where('driver_id', $user->id)
                    ->latest('scheduled_start')->paginate(15);
            } else {
                $assignments = Assignment::with(['order.customer','vehicle'])
                    ->where('guide_id', $user->id)
                    ->latest('scheduled_start')->paginate(15);
            }

            return view('activities.assignments.index', compact('assignments'));
        } catch (\Throwable $e) {
            Log::error('Assignments index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat data penugasan.');
        }
    }

    public function create(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            $order = $orderId ? Order::findOrFail($orderId) : null;

            $drivers = User::where('role','driver')->where('is_active',1)->orderBy('name')->get();
            $guides  = User::where('role','guide')->where('is_active',1)->orderBy('name')->get();
            $vehicles = Vehicle::orderBy('plate_no')->get();

            return view('activities.assignments.create', compact('order','drivers','guides','vehicles'));
        } catch (\Throwable $e) {
            Log::error('Assignments create error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat form penugasan.');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'order_id'        => ['required','exists:orders,id','unique:assignments,order_id'],
                'staff_id'        => ['required','exists:users,id'],
                'driver_id'       => ['nullable','exists:users,id'],
                'guide_id'        => ['nullable','exists:users,id'],
                'vehicle_id'      => ['nullable','exists:vehicles,id'],
                'scheduled_start' => ['nullable','date'],
                'scheduled_end'   => ['nullable','date','after:scheduled_start'],
                'estimated_hours' => ['nullable','numeric','min:0'],
                'status'          => ['required','in:assigned,in_progress,completed,cancelled'],
            ]);


            foreach (['scheduled_start','scheduled_end'] as $col) {
                if (!empty($data[$col])) {
                    $data[$col] = \Carbon\Carbon::parse($data[$col])->format('Y-m-d H:i:s');
                }
            }

            return DB::transaction(function () use ($data) {

                $assignment = Assignment::create($data);


                $order = Order::find($data['order_id']);
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'assigned']);
                }


                if (!empty($assignment->driver_id)) {
                    Notification::create([
                        'user_id'       => $assignment->driver_id,
                        'assignment_id' => $assignment->id,
                        'title'         => 'Tugas Baru',
                        'body'          => "Anda ditugaskan untuk order #{$assignment->order_id} "
                            . ($assignment->scheduled_start ? 'mulai ' . $assignment->scheduled_start : ''),
                    ]);
                }


                if (!empty($assignment->guide_id)) {
                    Notification::create([
                        'user_id'       => $assignment->guide_id,
                        'assignment_id' => $assignment->id,
                        'title'         => 'Tugas Baru',
                        'body'          => "Anda ditugaskan untuk order #{$assignment->order_id} "
                            . ($assignment->scheduled_start ? 'mulai ' . $assignment->scheduled_start : ''),
                    ]);
                }

                Log::info('Assignment created', [
                    'assignment_id' => $assignment->id,
                    'order_id'      => $assignment->order_id,
                    'driver_id'     => $assignment->driver_id,
                    'guide_id'      => $assignment->guide_id,
                    'vehicle_id'    => $assignment->vehicle_id,
                    'scheduled_start' => $assignment->scheduled_start,
                    'scheduled_end'   => $assignment->scheduled_end,
                ]);

                return redirect()
                    ->route('assignments.show', $assignment)
                    ->with('success', 'Penugasan berhasil dibuat & notifikasi terkirim.');
            });

        } catch (\Throwable $e) {
            Log::error('Assignment store error', [
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->withInput()->with('error', 'Gagal membuat penugasan.');
        }
    }



    public function show(Assignment $assignment)
    {
        try {
            $assignment->load(['order.customer','driver','guide','vehicle','workSessions']);
            return view('activities.assignments.show', compact('assignment'));
        } catch (\Throwable $e) {
            Log::error('Assignment show error', ['assignment_id' => $assignment->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat detail penugasan.');
        }
    }

    public function edit(Assignment $assignment)
    {
        try {
            $assignment->load(['order']);
            $drivers = User::where('role','driver')->where('is_active',1)->orderBy('name')->get();
            $guides  = User::where('role','guide')->where('is_active',1)->orderBy('name')->get();
            $vehicles = Vehicle::orderBy('plate_no')->get();

            return view('activities.assignments.edit', compact('assignment','drivers','guides','vehicles'));
        } catch (\Throwable $e) {
            Log::error('Assignment edit error', ['assignment_id' => $assignment->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat form edit penugasan.');
        }
    }

    public function update(Request $request, Assignment $assignment)
    {
        try {
            $data = $request->validate([
                'driver_id'       => ['nullable','exists:users,id'],
                'guide_id'        => ['nullable','exists:users,id'],
                'vehicle_id'      => ['nullable','exists:vehicles,id'],
                'scheduled_start' => ['nullable','date'],
                'scheduled_end'   => ['nullable','date','after:scheduled_start'],
                'estimated_hours' => ['nullable','numeric','min:0'],
                'status'          => ['required','in:assigned,in_progress,completed,cancelled'],
            ]);

            // Validasi role
            if (!empty($data['driver_id']) && !User::where('id',$data['driver_id'])->where('role','driver')->exists()) {
                Log::warning('Assignment update invalid driver role', $data);
                return back()->withErrors(['driver_id' => 'User yang dipilih bukan driver.'])->withInput();
            }
            if (!empty($data['guide_id']) && !User::where('id',$data['guide_id'])->where('role','guide')->exists()) {
                Log::warning('Assignment update invalid guide role', $data);
                return back()->withErrors(['guide_id' => 'User yang dipilih bukan guide.'])->withInput();
            }

            // Cek bentrok jadwal saat update
            $start = $data['scheduled_start'] ?? $assignment->scheduled_start;
            $end   = $data['scheduled_end']   ?? $assignment->scheduled_end;
            if ($start && $end) {
                if (!empty($data['driver_id']) && $this->hasOverlap($data['driver_id'], $start, $end, 'driver_id', $assignment->id)) {
                    Log::warning('Driver overlap on update', ['driver_id' => $data['driver_id'], 'assignment_id' => $assignment->id]);
                    return back()->withErrors(['driver_id' => 'Driver bentrok dengan penugasan lain.'])->withInput();
                }
                if (!empty($data['guide_id']) && $this->hasOverlap($data['guide_id'], $start, $end, 'guide_id', $assignment->id)) {
                    Log::warning('Guide overlap on update', ['guide_id' => $data['guide_id'], 'assignment_id' => $assignment->id]);
                    return back()->withErrors(['guide_id' => 'Guide bentrok dengan penugasan lain.'])->withInput();
                }
            }

            return DB::transaction(function () use ($data, $assignment) {
                $assignment->update($data);

                // Sinkron status order
                $order = $assignment->order;
                if ($order) {
                    if ($assignment->status === 'cancelled') {
                        $order->update(['status' => 'pending']);
                    } elseif ($assignment->status === 'completed') {
                        $order->update(['status' => 'completed']);
                    } else {
                        $order->update(['status' => 'assigned']);
                    }
                }

                Log::info('Assignment updated', ['assignment_id' => $assignment->id]);
                return redirect()->route('assignments.show', $assignment)->with('success', 'Penugasan berhasil diperbarui.');
            });

        } catch (\Throwable $e) {
            Log::error('Assignment update error', ['assignment_id' => $assignment->id, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal memperbarui penugasan.');
        }
    }

    public function destroy(Assignment $assignment)
    {
        try {
            return DB::transaction(function () use ($assignment) {
                $order = $assignment->order;
                $id = $assignment->id;
                $assignment->delete();

                if ($order && $order->status !== 'completed') {
                    $order->update(['status' => 'pending']);
                }

                Log::info('Assignment deleted', ['assignment_id' => $id]);
                return redirect()->route('assignments.index')->with('success', 'Penugasan berhasil dihapus.');
            });
        } catch (\Throwable $e) {
            Log::error('Assignment delete error', ['assignment_id' => $assignment->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus penugasan.');
        }
    }

    private function hasOverlap(int $userId, string $start, string $end, string $roleField, ?int $excludeAssignmentId = null): bool
    {
        $start = Carbon::parse($start);
        $end   = Carbon::parse($end);

        return Assignment::when($excludeAssignmentId, fn($q) => $q->where('id','!=',$excludeAssignmentId))
            ->where($roleField, $userId)
            ->whereIn('status', ['assigned','in_progress'])
            ->whereNotNull('scheduled_start')
            ->whereNotNull('scheduled_end')
            ->where(function ($q) use ($start, $end) {
                $q->where('scheduled_start', '<', $end)
                  ->where('scheduled_end',   '>', $start);
            })
            ->exists();
    }
}
