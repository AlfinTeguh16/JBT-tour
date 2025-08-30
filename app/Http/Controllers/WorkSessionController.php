<?php

namespace App\Http\Controllers;

use App\Models\WorkSession;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkSessionController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            if (in_array($user->role, ['driver','guide'])) {
                $workSessions = WorkSession::with('assignment.order.customer')
                    ->where('user_id', $user->id)
                    ->latest('started_at')
                    ->paginate(15);
            } else {
                $workSessions = WorkSession::with(['user','assignment.order.customer'])
                    ->latest('started_at')
                    ->paginate(15);
            }

            return view('work-sessions.index', compact('workSessions'));
        } catch (\Throwable $e) {
            Log::error('WorkSessions index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat jam kerja.');
        }
    }

    public function create()
    {
        try {
            $assignments = Assignment::query()
                ->when(in_array(Auth::user()->role, ['driver','guide']), function ($q) {
                    $user = Auth::user();
                    $field = $user->role === 'driver' ? 'driver_id' : 'guide_id';
                    $q->where($field, $user->id);
                })
                ->whereIn('status', ['assigned','in_progress'])
                ->orderBy('scheduled_start')
                ->get();

            return view('work-sessions.create', compact('assignments'));
        } catch (\Throwable $e) {
            Log::error('WorkSessions create error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat form jam kerja.');
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!in_array($user->role, ['driver','guide'])) {
                Log::warning('WorkSession store forbidden for role', ['role' => $user->role, 'user_id' => $user->id]);
                abort(403, 'Hanya driver/guide yang dapat mencatat jam kerja.');
            }

            $data = $request->validate([
                'assignment_id' => ['nullable','exists:assignments,id'],
                'started_at'    => ['required','date'],
                'ended_at'      => ['nullable','date','after:started_at'],
                'hours_decimal' => ['nullable','numeric','min:0'],
            ]);

            // Pastikan assignment (jika ada) milik user
            if (!empty($data['assignment_id'])) {
                $ownField = $user->role === 'driver' ? 'driver_id' : 'guide_id';
                $belongs = Assignment::where('id', $data['assignment_id'])
                    ->where($ownField, $user->id)
                    ->exists();

                if (!$belongs) {
                    Log::warning('WorkSession store: assignment not owned', ['user_id' => $user->id, 'assignment_id' => $data['assignment_id']]);
                    return back()->withInput()->withErrors(['assignment_id' => 'Assignment tidak sesuai dengan akun anda.']);
                }
            }

            // Hitung hours_decimal jika tidak diisi namun ended_at ada
            if (empty($data['hours_decimal']) && !empty($data['ended_at'])) {
                $start = Carbon::parse($data['started_at']);
                $end   = Carbon::parse($data['ended_at']);
                $data['hours_decimal'] = round($start->diffInMinutes($end) / 60, 2);
            }

            $data['user_id'] = $user->id;

            $ws = DB::transaction(function () use ($data) {
                return WorkSession::create($data);
            });

            Log::info('WorkSession created', ['work_session_id' => $ws->id, 'user_id' => $user->id]);
            return redirect()->route('work-sessions.index')->with('success', 'Jam kerja berhasil dicatat.');

        } catch (\Throwable $e) {
            Log::error('WorkSession store error', ['payload' => $request->all(), 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal mencatat jam kerja.');
        }
    }

    public function show(WorkSession $workSession)
    {
        try {
            $user = Auth::user();
            if (in_array($user->role, ['driver','guide']) && $workSession->user_id !== $user->id) {
                Log::warning('WorkSession show forbidden', ['viewer_id' => $user->id, 'work_session_id' => $workSession->id]);
                abort(403);
            }

            $workSession->load(['assignment.order.customer','user']);
            return view('work-sessions.show', compact('workSession'));
        } catch (\Throwable $e) {
            Log::error('WorkSession show error', ['work_session_id' => $workSession->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat detail jam kerja.');
        }
    }

    public function edit(WorkSession $workSession)
    {
        try {
            $user = Auth::user();
            if (in_array($user->role, ['driver','guide']) && $workSession->user_id !== $user->id) {
                Log::warning('WorkSession edit forbidden', ['editor_id' => $user->id, 'work_session_id' => $workSession->id]);
                abort(403);
            }

            $assignments = Assignment::query()
                ->when(in_array(Auth::user()->role, ['driver','guide']), function ($q) use ($user) {
                    $field = $user->role === 'driver' ? 'driver_id' : 'guide_id';
                    $q->where($field, $user->id);
                })
                ->whereIn('status', ['assigned','in_progress'])
                ->orderBy('scheduled_start')
                ->get();

            return view('work-sessions.edit', compact('workSession','assignments'));
        } catch (\Throwable $e) {
            Log::error('WorkSession edit error', ['work_session_id' => $workSession->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memuat form edit jam kerja.');
        }
    }

    public function update(Request $request, WorkSession $workSession)
    {
        try {
            $user = Auth::user();
            if (in_array($user->role, ['driver','guide']) && $workSession->user_id !== $user->id) {
                Log::warning('WorkSession update forbidden', ['editor_id' => $user->id, 'work_session_id' => $workSession->id]);
                abort(403);
            }

            $data = $request->validate([
                'assignment_id' => ['nullable','exists:assignments,id'],
                'started_at'    => ['required','date'],
                'ended_at'      => ['nullable','date','after:started_at'],
                'hours_decimal' => ['nullable','numeric','min:0'],
            ]);

            if (!empty($data['assignment_id'])) {
                $ownField = $workSession->user->role === 'driver' ? 'driver_id' : 'guide_id';
                $belongs = Assignment::where('id', $data['assignment_id'])
                    ->where($ownField, $workSession->user_id)
                    ->exists();

                if (!$belongs) {
                    Log::warning('WorkSession update: assignment not owned', ['user_id' => $workSession->user_id, 'assignment_id' => $data['assignment_id']]);
                    return back()->withInput()->withErrors(['assignment_id' => 'Assignment tidak sesuai dengan akun anda.']);
                }
            }

            if (empty($data['hours_decimal']) && !empty($data['ended_at'])) {
                $start = Carbon::parse($data['started_at']);
                $end   = Carbon::parse($data['ended_at']);
                $data['hours_decimal'] = round($start->diffInMinutes($end) / 60, 2);
            }

            $workSession->update($data);
            Log::info('WorkSession updated', ['work_session_id' => $workSession->id]);

            return redirect()->route('work-sessions.index')->with('success', 'Jam kerja berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('WorkSession update error', ['work_session_id' => $workSession->id, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Gagal memperbarui jam kerja.');
        }
    }

    public function destroy(WorkSession $workSession)
    {
        try {
            $user = Auth::user();
            if (in_array($user->role, ['driver','guide']) && $workSession->user_id !== $user->id) {
                Log::warning('WorkSession delete forbidden', ['deleter_id' => $user->id, 'work_session_id' => $workSession->id]);
                abort(403);
            }

            $id = $workSession->id;
            $workSession->delete();
            Log::info('WorkSession deleted', ['work_session_id' => $id]);

            return redirect()->route('work-sessions.index')->with('success', 'Jam kerja berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('WorkSession delete error', ['work_session_id' => $workSession->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus jam kerja.');
        }
    }
}
