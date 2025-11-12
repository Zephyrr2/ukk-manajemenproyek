<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Project;
use App\Models\Time_Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all tasks assigned to this user (both direct assignment and through card_assignments)
        $myTasks = Card::where(function ($query) use ($user) {
                $query->where('user_id', $user->id) // Direct assignment
                      ->orWhereHas('assignments', function ($subQuery) use ($user) {
                          $subQuery->where('user_id', $user->id); // Assignment through card_assignments table
                      });
            })
            ->with(['board.project', 'user', 'subtasks'])
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->get();

        // Get current task (highest priority in_progress task)
        $currentTask = $myTasks
            ->whereIn('status', ['in_progress'])
            ->sortByDesc(function ($task) {
                $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
                return $priorityOrder[$task->priority] ?? 0;
            })
            ->first();

        // If no in_progress task, get highest priority todo task
        if (!$currentTask) {
            $currentTask = $myTasks
                ->where('status', 'todo')
                ->sortByDesc(function ($task) {
                    $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
                    return $priorityOrder[$task->priority] ?? 0;
                })
                ->first();
        }

        // Calculate current task progress
        $currentTaskProgress = 0;
        $currentTaskTimeSpent = 0;

        if ($currentTask) {
            // Calculate progress based on subtasks
            $totalSubtasks = $currentTask->subtasks->count();
            $completedSubtasks = $currentTask->subtasks->where('status', 'done')->count();
            $currentTaskProgress = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;

            // Get time spent on this task
            $timeLogs = Time_Log::where('card_id', $currentTask->id)
                ->where('user_id', $user->id)
                ->get();

            $currentTaskTimeSpent = $timeLogs->sum('duration_minutes') / 60; // Convert to hours

            // Add active session time if exists
            $activeSession = Time_Log::where('card_id', $currentTask->id)
                ->where('user_id', $user->id)
                ->whereNull('end_time')
                ->first();

            if ($activeSession) {
                // Current session duration (from start_time until now)
                $activeMinutes = now()->diffInMinutes($activeSession->start_time);
                // Add accumulated time from previous paused sessions
                $accumulatedMinutes = $activeSession->duration_minutes ?? 0;
                $currentTaskTimeSpent += ($activeMinutes + $accumulatedMinutes) / 60;
            }
        }

        // Group tasks by status
        $todoTasks = $myTasks->where('status', 'todo');
        $inProgressTasks = $myTasks->where('status', 'in_progress');
        $reviewTasks = $myTasks->where('status', 'review');
        $doneTasks = $myTasks->where('status', 'done');

        // Calculate statistics
        $totalTasks = $myTasks->count();
        $completedTasks = $doneTasks->count();
        $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Get time tracking statistics
        $todayLogs = Time_Log::where('user_id', $user->id)
            ->whereDate('start_time', Carbon::today())
            ->get();

        $todayWorkTime = $todayLogs->sum('duration_minutes') / 60; // Convert to hours

        // Add active session time if exists
        $activeSession = Time_Log::where('user_id', $user->id)
            ->whereNull('end_time')
            ->first();

        if ($activeSession && $activeSession->start_time->isToday()) {
            // Current session duration
            $activeMinutes = now()->diffInMinutes($activeSession->start_time);
            // Add accumulated time from previous paused sessions
            $accumulatedMinutes = $activeSession->duration_minutes ?? 0;
            $todayWorkTime += ($activeMinutes + $accumulatedMinutes) / 60;
        }

        $thisWeekLogs = Time_Log::where('user_id', $user->id)
            ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();

        $weekWorkTime = $thisWeekLogs->sum('duration_minutes') / 60; // Convert to hours

        // Get projects
        $projects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->with('boards.cards')
        ->get();

        // Get recent tasks for dashboard display (limit to 8 tasks, show all statuses)
        // Sort by: priority (high first), then due_date (nearest first), then updated_at (recent first)
        $recentTasks = $myTasks->sortByDesc(function($task) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $priorityOrder[$task->priority] ?? 0;
        })->sortBy('due_date')->sortByDesc('updated_at')->take(8);

        $pageSubtitle = 'Welcome, ' . $user->name . '!';

        return view('pages.user.dashboard', compact(
            'user',
            'myTasks',
            'currentTask',
            'currentTaskProgress',
            'currentTaskTimeSpent',
            'todoTasks',
            'inProgressTasks',
            'reviewTasks',
            'doneTasks',
            'totalTasks',
            'completedTasks',
            'overallProgress',
            'todayWorkTime',
            'weekWorkTime',
            'projects',
            'recentTasks',
            'activeSession',
            'pageSubtitle'
        ));
    }
}
