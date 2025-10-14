<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Time_Log;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class TimeTrackingController extends Controller
{
    /**
     * Display team time tracking data for leader
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get date range from request or default to current month
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $selectedProject = $request->get('project_id');
        $selectedUser = $request->get('user_id');

        // Get projects where user is leader or member
        $projects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })->with('projectMembers.user')->get();

        // Get all team members from user's projects
        $teamMembers = collect();
        foreach ($projects as $project) {
            // Add project creator if not already added
            if (!$teamMembers->where('id', $project->user_id)->count()) {
                $teamMembers->push($project->user);
            }
            // Add project members
            foreach ($project->projectMembers as $member) {
                if (!$teamMembers->where('id', $member->user_id)->count()) {
                    $teamMembers->push($member->user);
                }
            }
        }

        // Build time logs query
        $timeLogsQuery = Time_Log::whereBetween('start_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('user_id', $teamMembers->pluck('id'))
            ->with(['card.board.project', 'subtask', 'user']);

        // Apply filters
        if ($selectedProject) {
            $timeLogsQuery->whereHas('card.board.project', function($q) use ($selectedProject) {
                $q->where('id', $selectedProject);
            });
        }

        if ($selectedUser) {
            $timeLogsQuery->where('user_id', $selectedUser);
        }

        $timeLogs = $timeLogsQuery->orderBy('start_time', 'desc')->get();

        // Calculate overall statistics
        $totalMinutes = $timeLogs->sum('duration_minutes') ?? 0;
        $totalHours = round($totalMinutes / 60, 2);

        // Group by user
        $userStats = $timeLogs->groupBy('user_id')->map(function($logs) {
            $user = $logs->first()->user;
            return [
                'user' => $user,
                'total_minutes' => $logs->sum('duration_minutes') ?? 0,
                'total_hours' => round(($logs->sum('duration_minutes') ?? 0) / 60, 2),
                'session_count' => $logs->count(),
                'projects_count' => $logs->map(function($log) {
                    return $log->card ? $log->card->board->project_id : null;
                })->filter()->unique()->count()
            ];
        });

        // Group by project
        $projectStats = $timeLogs->groupBy(function($log) {
            return $log->card ? $log->card->board->project_id : null;
        })->map(function($logs, $projectId) {
            $project = $logs->first()->card ? $logs->first()->card->board->project : null;
            return [
                'project' => $project,
                'total_minutes' => $logs->sum('duration_minutes') ?? 0,
                'total_hours' => round(($logs->sum('duration_minutes') ?? 0) / 60, 2),
                'session_count' => $logs->count(),
                'users_count' => $logs->pluck('user_id')->unique()->count()
            ];
        });

        // Group by date for chart
        $dailyStats = $timeLogs->groupBy(function($log) {
            return Carbon::parse($log->start_time)->format('Y-m-d');
        })->map(function($logs) {
            return [
                'total_minutes' => $logs->sum('duration_minutes') ?? 0,
                'total_hours' => round(($logs->sum('duration_minutes') ?? 0) / 60, 2),
                'users_count' => $logs->pluck('user_id')->unique()->count()
            ];
        });

        return view('pages.leader.time-tracking', compact(
            'timeLogs',
            'totalMinutes',
            'totalHours',
            'userStats',
            'projectStats',
            'dailyStats',
            'projects',
            'teamMembers',
            'startDate',
            'endDate',
            'selectedProject',
            'selectedUser'
        ));
    }

    /**
     * Get time tracking data for specific filters (AJAX)
     */
    public function getTimeData(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $projectId = $request->get('project_id');
        $userId = $request->get('user_id');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Start date and end date are required'], 400);
        }

        // Get projects where user is leader or member
        $projects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })->with('projectMembers.user')->get();

        // Get all team members from user's projects
        $teamMembers = collect();
        foreach ($projects as $project) {
            if (!$teamMembers->where('id', $project->user_id)->count()) {
                $teamMembers->push($project->user);
            }
            foreach ($project->projectMembers as $member) {
                if (!$teamMembers->where('id', $member->user_id)->count()) {
                    $teamMembers->push($member->user);
                }
            }
        }

        // Build query
        $timeLogsQuery = Time_Log::whereBetween('start_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('user_id', $teamMembers->pluck('id'))
            ->with(['card.board.project', 'subtask', 'user']);

        if ($projectId) {
            $timeLogsQuery->whereHas('card.board.project', function($q) use ($projectId) {
                $q->where('id', $projectId);
            });
        }

        if ($userId) {
            $timeLogsQuery->where('user_id', $userId);
        }

        $timeLogs = $timeLogsQuery->orderBy('start_time', 'desc')->get();

        $totalMinutes = $timeLogs->sum('duration_minutes') ?? 0;
        $totalHours = round($totalMinutes / 60, 2);

        return response()->json([
            'total_hours' => $totalHours,
            'total_minutes' => $totalMinutes,
            'logs_count' => $timeLogs->count(),
            'unique_users' => $timeLogs->pluck('user_id')->unique()->count(),
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
                    'user_name' => $log->user->name,
                    'user_email' => $log->user->email,
                ];
            })
        ]);
    }

    /**
     * Start work on a task (Leaders can also track time)
     */
    public function startWork(Request $request)
    {
        $request->validate([
            'card_id' => 'required|integer|exists:cards,id',
            'subtask_id' => 'nullable|integer|exists:subtasks,id',
        ]);

        try {
            $timeLog = Time_Log::startWorkSession(
                $request->card_id,
                $request->subtask_id
            );

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
        } catch (\Exception $e) {
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
            $activeSession = Time_Log::getAnyActiveSession($user->id);

            if (!$activeSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active work session found'
                ], 404);
            }

            $activeSession->stopWorkSession();

            return response()->json([
                'success' => true,
                'message' => 'Work session stopped successfully',
                'duration' => $activeSession->formatted_duration,
                'total_minutes' => $activeSession->duration_minutes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop work session: ' . $e->getMessage()
            ], 500);
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
                return response()->json([
                    'success' => false,
                    'message' => 'No active work session found'
                ], 404);
            }

            $activeSession->pauseWorkSession();

            return response()->json([
                'success' => true,
                'message' => 'Work session paused successfully',
                'duration' => $activeSession->formatted_duration,
                'total_minutes' => $activeSession->duration_minutes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to pause work session: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resume paused work session
     */
    public function resumeWork(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer|exists:time_logs,id',
        ]);

        try {
            $session = Time_Log::findOrFail($request->session_id);

            // Verify ownership
            if ($session->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to session'
                ], 403);
            }

            $newSession = $session->resumeWorkSession();

            return response()->json([
                'success' => true,
                'message' => 'Work session resumed successfully',
                'session' => [
                    'id' => $newSession->id,
                    'start_time' => $newSession->start_time,
                    'accumulated_minutes' => $newSession->duration_minutes,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resume work session: ' . $e->getMessage()
            ], 500);
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
