<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard data for authenticated user
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Get projects where user is member or creator
            $projects = Project::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                          $subQuery->where('user_id', $user->id);
                      });
            })->with(['user', 'boards.cards'])->get();

            // Get all tasks from user's projects
            $allTasks = collect();
            foreach ($projects as $project) {
                foreach ($project->boards as $board) {
                    $allTasks = $allTasks->merge($board->cards);
                }
            }

            // Filter tasks assigned to current user
            $myTasks = $allTasks->filter(function ($task) use ($user) {
                return $task->user_id == $user->id ||
                       $task->assignments->where('user_id', $user->id)->isNotEmpty();
            });

            // Get overdue tasks
            $overdueTasks = $myTasks->filter(function ($task) {
                return $task->due_date &&
                       Carbon::parse($task->due_date)->isPast() &&
                       !in_array($task->status, ['done']);
            });

            // Get upcoming tasks (due within next 7 days)
            $upcomingTasks = $myTasks->filter(function ($task) {
                return $task->due_date &&
                       Carbon::parse($task->due_date)->isFuture() &&
                       Carbon::parse($task->due_date)->diffInDays(now()) <= 7 &&
                       !in_array($task->status, ['done']);
            })->take(5)->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_projects' => $projects->count(),
                    'total_tasks' => $myTasks->count(),
                    'completed_tasks' => $myTasks->where('status', 'done')->count(),
                    'active_tasks' => $myTasks->whereIn('status', ['todo', 'in_progress', 'review'])->count(),
                    'overdue_tasks' => $overdueTasks->count(),
                    'recent_projects' => $projects->take(5)->values(),
                    'upcoming_tasks' => $upcomingTasks,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
