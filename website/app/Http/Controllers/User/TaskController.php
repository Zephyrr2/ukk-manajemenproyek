<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Card;
use App\Models\Card_Assigment;
use App\Models\Project;
use App\Models\User;
use App\Models\Time_Log;

class TaskController extends Controller
{
    /**
     * Display user's tasks
     */
    public function index()
    {
        $user = Auth::user();

        // Get all projects where user is member
        $projects = Project::whereHas('projectMembers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->orWhere('user_id', $user->id) // Include projects created by user
        ->with(['boards.cards.user', 'boards.cards.assignments.user', 'boards.cards.subtasks'])
        ->get();

        // Get all tasks from user's projects
        $allTasks = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $allTasks = $allTasks->merge($board->cards);
            }
        }

        // Get tasks assigned to the current user with their task-level comments only
        $myTasks = $allTasks->filter(function ($task) use ($user) {
            return $task->user_id == $user->id ||
                   $task->assignments->where('user_id', $user->id)->isNotEmpty();
        });

        // Load task-level comments separately (not subtask comments)
        $myTasks = $myTasks->map(function ($task) {
            $task->load(['comments' => function ($query) {
                $query->whereNull('subtask_id') // Only task-level comments, not subtask comments
                      ->with('user')
                      ->orderBy('created_at', 'desc');
            }]);
            return $task;
        });

        // Group tasks by status
        $taskStats = [
            'total' => $myTasks->count(),
            'todo' => $myTasks->where('status', 'todo')->count(),
            'in_progress' => $myTasks->where('status', 'in_progress')->count(),
            'review' => $myTasks->where('status', 'review')->count(),
            'done' => $myTasks->where('status', 'done')->count(),
        ];

        // Get active session for time tracking buttons
        $activeSession = Time_Log::where('user_id', $user->id)
            ->whereNull('end_time')
            ->first();

        // Get last paused session (for resume button)
        $pausedSession = Time_Log::where('user_id', $user->id)
            ->where('status', 'paused')
            ->whereNotNull('end_time')
            ->orderBy('end_time', 'desc')
            ->first();

        $pageSubtitle = 'Manage tasks assigned to you';

        return view('pages.user.tasks', compact(
            'projects',
            'taskStats',
            'myTasks',
            'allTasks',
            'user',
            'activeSession',
            'pausedSession',
            'pageSubtitle'
        ));
    }

    /**
     * Start working on a task (change status from todo to in_progress)
     */
    public function startTask(Request $request, $taskId)
    {
        $user = Auth::user();

        // Find the task and verify access
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                    $memberQuery->where('user_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user', 'assignments'])
        ->first();

        if (!$task) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task tidak ditemukan atau Anda tidak memiliki akses.'
                ], 404);
            }
            return redirect()->route('user.tasks')->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Check if user is assigned to this task or is the task creator
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignments->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk start task ini.'
                ], 403);
            }
            return redirect()->route('user.tasks')->with('error', 'Anda tidak memiliki akses untuk start task ini.');
        }

        // Only allow start if task is todo
        if ($task->status !== 'todo') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task hanya bisa distart jika statusnya masih To Do. Status saat ini: ' . $task->status
                ], 400);
            }
            return redirect()->route('user.tasks')->with('error', 'Task hanya bisa distart jika statusnya masih To Do.');
        }

        // Update task status to in_progress
        $task->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        // Update user status to working
        User::where('id', $user->id)->update(['status' => 'working']);

        // Update or create assignment status
        $assignment = $task->assignments()->where('user_id', $user->id)->first();
        if ($assignment) {
            $assignment->update([
                'assignment_status' => 'in_progress',
                'started_at' => now(),
            ]);
        } else {
            // Create new assignment if not exists
            Card_Assigment::create([
                'card_id' => $task->id,
                'user_id' => $user->id,
                'assigned_at' => now(),
                'assignment_status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Auto-start time tracking
        try {
            Time_Log::startWorkSession($task->id, null, $user->id);
        } catch (\Exception $e) {
            // Log error but don't fail the task start
            Log::warning('Failed to start time tracking for task ' . $task->id . ': ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dimulai! Status Anda sekarang working.',
                'data' => [
                    'task_id' => $task->id,
                    'task_status' => $task->status,
                    'user_status' => 'working',
                    'started_at' => $task->started_at
                ]
            ]);
        }

        // Redirect back to dashboard if came from dashboard, otherwise to tasks
        $redirectRoute = $request->get('from') === 'dashboard' ? 'user.dashboard' : 'user.tasks';
        return redirect()->route($redirectRoute)->with('success', '✅ Task berhasil dimulai! Timer aktif dan status Anda sekarang Working.');
    }

    /**
     * Submit task for review (change status from in_progress to review)
     */
    public function submitTask(Request $request, $taskId)
    {
        $user = Auth::user();

        // Find the task and verify access
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                    $memberQuery->where('user_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user', 'assignments'])
        ->first();

        if (!$task) {
            return redirect()->route('user.tasks')->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Check if user is assigned to this task or is the task creator
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignments->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            return redirect()->route('user.tasks')->with('error', 'Anda tidak memiliki akses untuk submit task ini.');
        }

        // Only allow submit if task is in progress
        if ($task->status !== 'in_progress') {
            return redirect()->route('user.tasks')->with('error', 'Task hanya bisa disubmit jika statusnya sedang dalam progress.');
        }

        // STOP ACTIVE TIMER - Timer harus berhenti saat task masuk review
        // Karena waktu review bukan waktu kerja user
        $activeSession = \App\Models\Time_Log::getActiveSession($user->id, $task->id, null);
        if ($activeSession) {
            $activeSession->stopWorkSession();
            \Illuminate\Support\Facades\Log::info('Auto-stopped timer when task submitted to review', [
                'task_id' => $task->id,
                'user_id' => $user->id,
                'time_log_id' => $activeSession->id
            ]);
        }

        // Update task status to review
        $task->update([
            'status' => 'review',
            'actual_hours' => $request->input('actual_hours', $task->actual_hours),
        ]);

        // Create notification for project leader
        $projectLeader = $task->board->project->user;
        if ($projectLeader) {
            \App\Models\Notification::create([
                'user_id' => $projectLeader->id,
                'type' => 'task_submitted',
                'title' => 'Task Submitted for Review',
                'message' => $user->name . ' has submitted task "' . $task->card_title . '" for review.',
                'card_id' => $task->id,
                'data' => [
                    'task_id' => $task->id,
                    'task_title' => $task->card_title,
                    'submitted_by' => $user->name,
                    'project_slug' => $task->board->project->slug,
                ],
            ]);
        }

        // Update user status to free (task completed, waiting for review)
        User::where('id', $user->id)->update(['status' => 'free']);

        // Update assignment status if exists and record when submitted for review
        $assignment = $task->assignments()->where('user_id', $user->id)->first();
        if ($assignment) {
            $assignment->update([
                'assignment_status' => 'completed',
                'completed_at' => now(),
            ]);
        } else {
            // Create assignment record if it doesn't exist
            Card_Assigment::create([
                'card_id' => $task->id,
                'user_id' => $user->id,
                'assigned_at' => $task->started_at ?? now(),
                'assignment_status' => 'completed',
                'started_at' => $task->started_at,
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('user.tasks')->with('success', 'Task berhasil disubmit untuk review! Status Anda sekarang free.');
    }
}
