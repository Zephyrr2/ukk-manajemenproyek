<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Time_Log;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class TimeTrackingController extends Controller
{
    /**
     * Display user's time tracking data
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get date range from request or default to current month
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Get projects where user is member or creator
        $projects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })->pluck('project_name', 'id');

        // Get time logs for the user within date range
        $timeLogs = Time_Log::where('user_id', $user->id)
            ->whereBetween('start_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with(['card.board.project', 'subtask'])
            ->orderBy('start_time', 'desc')
            ->get();

        // Calculate statistics
        $totalMinutes = $timeLogs->sum('duration_minutes') ?? 0;
        $totalHours = round($totalMinutes / 60, 2);

        // Group by project
        $projectStats = $timeLogs->groupBy(function($log) {
            return $log->card ? $log->card->board->project->project_name : 'No Project';
        })->map(function($logs) {
            return [
                'total_minutes' => $logs->sum('duration_minutes') ?? 0,
                'total_hours' => round(($logs->sum('duration_minutes') ?? 0) / 60, 2),
                'session_count' => $logs->count()
            ];
        });

        // Group by date for chart
        $dailyStats = $timeLogs->groupBy(function($log) {
            return Carbon::parse($log->start_time)->format('Y-m-d');
        })->map(function($logs) {
            return [
                'total_minutes' => $logs->sum('duration_minutes') ?? 0,
                'total_hours' => round(($logs->sum('duration_minutes') ?? 0) / 60, 2),
            ];
        });

        return view('pages.user.time-tracking', compact(
            'timeLogs',
            'totalMinutes',
            'totalHours',
            'projectStats',
            'dailyStats',
            'projects',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get time tracking data for specific date range (AJAX)
     */
    public function getTimeData(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Start date and end date are required'], 400);
        }

        $timeLogs = Time_Log::where('user_id', $user->id)
            ->whereBetween('start_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with(['card.board.project', 'subtask'])
            ->orderBy('start_time', 'desc')
            ->get();

        $totalMinutes = $timeLogs->sum('duration_minutes') ?? 0;
        $totalHours = round($totalMinutes / 60, 2);

        return response()->json([
            'total_hours' => $totalHours,
            'total_minutes' => $totalMinutes,
            'logs_count' => $timeLogs->count(),
            'logs' => $timeLogs->map(function($log) {
                return [
                    'id' => $log->id,
                    'start_time' => $log->start_time,
                    'end_time' => $log->end_time,
                    'duration_minutes' => $log->duration_minutes,
                    'duration_hours' => round(($log->duration_minutes ?? 0) / 60, 2),
                    'description' => $log->description,
                    'task_title' => $log->card ? $log->card->card_title : null,
                    'subtask_title' => $log->subtask ? $log->subtask->subtask_title : null,
                    'project_name' => $log->card && $log->card->board ? $log->card->board->project->project_name : null,
                ];
            })
        ]);
    }

    /**
     * Start work on a task
     */
    public function startWork(Request $request)
    {
        try {
            $request->validate([
                'card_id' => 'required|integer|exists:cards,id',
                'subtask_id' => 'nullable|integer|exists:subtasks,id',
            ]);

            Log::info('Starting work session', [
                'card_id' => $request->card_id,
                'subtask_id' => $request->subtask_id,
                'user_id' => Auth::id()
            ]);

            $timeLog = Time_Log::startWorkSession(
                $request->card_id,
                $request->subtask_id,
                Auth::id()
            );

            Log::info('Work session created', ['time_log_id' => $timeLog->id]);

            return response()->json([
                'success' => true,
                'message' => 'Work session started successfully',
                'session' => [
                    'id' => $timeLog->id,
                    'start_time' => $timeLog->start_time,
                    'card_title' => $timeLog->card->card_title ?? 'Unknown Task',
                    'subtask_title' => $timeLog->subtask->subtask_title ?? null,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to start work session', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to start work session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stop work on current task
     */
    public function stopWork(Request $request)
    {
        try {
            $user = Auth::user();
            Log::info('Stopping work session', ['user_id' => $user->id]);

            $activeSession = Time_Log::getAnyActiveSession($user->id);

            if (!$activeSession) {
                Log::warning('No active session found', ['user_id' => $user->id]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No active work session found'
                    ], 404);
                }
                return redirect()->route('user.dashboard')->with('error', '❌ Tidak ada sesi kerja aktif yang ditemukan.');
            }

            Log::info('Found active session', ['session_id' => $activeSession->id]);

            $activeSession->stopWorkSession();

            // Refresh to get updated values
            $activeSession->refresh();

            Log::info('Work session stopped', [
                'session_id' => $activeSession->id,
                'duration_minutes' => $activeSession->duration_minutes
            ]);

            $durationHours = round($activeSession->duration_minutes / 60, 1);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Work session stopped successfully',
                    'duration' => $activeSession->formatted_duration,
                    'duration_minutes' => $activeSession->duration_minutes
                ]);
            }

            // Smart redirect based on referer
            return redirect()->back()->with('success', '✅ Timer dihentikan! Durasi: ' . $durationHours . ' jam. Status Anda kembali ke Available.');

        } catch (\Exception $e) {
            Log::error('Failed to stop work session', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to stop work session: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('user.dashboard')->with('error', '❌ Gagal menghentikan timer: ' . $e->getMessage());
        }
    }

    /**
     * Pause current work session
     */
    public function pauseWork(Request $request)
    {
        try {
            $user = Auth::user();
            $activeSession = Time_Log::getAnyActiveSession($user->id);

            if (!$activeSession) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No active work session found'
                    ], 404);
                }
                return redirect()->route('user.dashboard')->with('error', '❌ Tidak ada sesi kerja aktif yang ditemukan.');
            }

            $activeSession->pauseWorkSession();

            // Update user status to paused
            User::where('id', $user->id)->update(['status' => 'paused']);

            // Refresh to get updated duration_minutes
            $activeSession->refresh();

            $durationHours = round($activeSession->duration_minutes / 60, 1);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Work session paused successfully',
                    'duration' => $activeSession->formatted_duration,
                    'total_minutes' => $activeSession->duration_minutes
                ]);
            }

            // Smart redirect based on referer
            return redirect()->back()->with('success', '⏸️ Timer dijeda! Waktu tersimpan: ' . $durationHours . ' jam.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to pause work session: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('user.dashboard')->with('error', '❌ Gagal menjeda timer: ' . $e->getMessage());
        }
    }

    /**
     * Resume paused work session
     */
    public function resumeWork(Request $request)
    {
        $user = Auth::user();

        try {
            // Find the most recent paused session for this user
            $session = Time_Log::where('user_id', $user->id)
                ->where('status', 'paused')
                ->whereNotNull('end_time')
                ->orderBy('end_time', 'desc')
                ->first();

            if (!$session) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No paused session found'
                    ], 404);
                }
                return redirect()->route('user.dashboard')->with('error', '❌ Tidak ada sesi yang di-pause.');
            }

            $newSession = $session->resumeWorkSession();

            // Update user status back to working
            User::where('id', $user->id)->update(['status' => 'working']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Work session resumed successfully',
                    'session' => [
                        'id' => $newSession->id,
                        'start_time' => $newSession->start_time,
                        'accumulated_minutes' => $newSession->duration_minutes,
                    ]
                ]);
            }

            // Smart redirect based on referer
            return redirect()->back()->with('success', '▶️ Pekerjaan dilanjutkan! Timer aktif kembali.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resume work session: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('user.dashboard')->with('error', '❌ Gagal melanjutkan timer: ' . $e->getMessage());
        }
    }

    /**
     * Get current active session
     */
    public function getActiveSession(Request $request)
    {
        try {
            $user = Auth::user();
            $activeSession = Time_Log::getAnyActiveSession($user->id);

            if (!$activeSession) {
                return response()->json([
                    'success' => true,
                    'active_session' => null
                ]);
            }

            // Calculate current duration
            $currentDuration = now()->diffInMinutes($activeSession->start_time);
            $totalMinutes = ($activeSession->duration_minutes ?? 0) + $currentDuration;

            return response()->json([
                'success' => true,
                'active_session' => [
                    'id' => $activeSession->id,
                    'card_id' => $activeSession->card_id,
                    'subtask_id' => $activeSession->subtask_id,
                    'start_time' => $activeSession->start_time,
                    'current_duration_minutes' => $currentDuration,
                    'total_duration_minutes' => $totalMinutes,
                    'card_title' => $activeSession->card->card_title ?? 'Unknown Task',
                    'subtask_title' => $activeSession->subtask->subtask_title ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get active session: ' . $e->getMessage()
            ], 500);
        }
    }
}
