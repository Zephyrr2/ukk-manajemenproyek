<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Card_Assigment;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display task assignment page with tasks for review
     */
    public function index()
    {
        $user = Auth::user();

        // Get all projects where user is leader or member
        $projects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->with(['boards.cards.user', 'boards.cards.assignments.user'])
        ->get();

        // Get all tasks from user's projects
        $allTasks = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $allTasks = $allTasks->merge($board->cards);
            }
        }

        // Group tasks by status
        $taskStats = [
            'total' => $allTasks->count(),
            'todo' => $allTasks->where('status', 'todo')->count(),
            'in_progress' => $allTasks->where('status', 'in_progress')->count(),
            'review' => $allTasks->where('status', 'review')->count(),
            'done' => $allTasks->where('status', 'done')->count(),
        ];

        // Get tasks assigned to the current user
        $myTasks = $allTasks->filter(function ($task) use ($user) {
            return $task->user_id == $user->id ||
                   $task->assignments->where('user_id', $user->id)->isNotEmpty();
        });

        // Get tasks that need review (for project leaders)
        $tasksForReview = $allTasks->where('status', 'review')->values();

        // Get recent assignment history
        $assignmentHistory = Card_Assigment::whereHas('card.board.project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['card', 'user'])
        ->orderBy('updated_at', 'desc')
        ->limit(10)
        ->get();

        return view('pages.leader.task-assignment', compact(
            'projects',
            'taskStats',
            'myTasks',
            'tasksForReview',
            'allTasks',
            'assignmentHistory'
        ));
    }

    /**
     * Approve task (change status from review to done) - Form submission
     */
    public function approve($taskId)
    {
        $user = Auth::user();

        // Find the task and verify user is project leader
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where('user_id', $user->id); // Only project leaders can approve
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user', 'assignments'])
        ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses untuk approve.');
        }

        // Only allow approve if task is in review
        if ($task->status !== 'review') {
            return redirect()->back()->with('error', 'Task hanya bisa diapprove jika statusnya sedang dalam review.');
        }

        // Calculate actual hours from started_at to when it was submitted for review
        $actualHours = null;
        if ($task->started_at) {
            // Find when the task was submitted for review
            $submissionAssignment = $task->assignments()
                ->where('assignment_status', 'completed')
                ->whereNotNull('completed_at')
                ->orderBy('completed_at', 'desc')
                ->first();

            if ($submissionAssignment && $submissionAssignment->completed_at) {
                // Calculate time from start to submission (excluding review time)
                $startTime = $task->started_at;
                $submissionTime = $submissionAssignment->completed_at;
                $totalMinutes = $startTime->diffInMinutes($submissionTime);
                $actualHours = round($totalMinutes / 60, 2);
            } else {
                // Fallback: calculate from start to now if no submission record found
                $startTime = $task->started_at;
                $endTime = now();
                $totalMinutes = $startTime->diffInMinutes($endTime);
                $actualHours = round($totalMinutes / 60, 2);
            }
        }

        // Check if there are in-progress subtasks that will be auto-completed
        $pendingSubtasks = $task->getPendingSubtasksCount();

        // Update task status to done and set actual hours
        $task->update([
            'status' => 'done',
            'actual_hours' => $actualHours
        ]);

        // Create assignment history record for approval
        Card_Assigment::create([
            'card_id' => $task->id,
            'user_id' => $user->id, // Leader who approved
            'assigned_at' => now(),
            'assignment_status' => 'completed',
            'started_at' => $task->started_at,
            'completed_at' => now(),
        ]);

        // Create success message with subtask info
        $message = 'Task "' . $task->card_title . '" berhasil diapprove!';
        if ($pendingSubtasks > 0) {
            $message .= " ({$pendingSubtasks} subtask otomatis diselesaikan)";
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Reject task (change status from review back to todo)
     */
    public function reject($taskId)
    {
        $user = Auth::user();

        // Find the task and verify user is project leader
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where('user_id', $user->id); // Only project leaders can reject
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user', 'assignments'])
        ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses untuk reject.');
        }

        // Only allow reject if task is in review
        if ($task->status !== 'review') {
            return redirect()->back()->with('error', 'Task hanya bisa direject jika statusnya sedang dalam review.');
        }

        // Update task status back to in_progress and reset actual_hours
        $task->update([
            'status' => 'in_progress',
            'actual_hours' => null // Reset actual hours since task is back in progress
        ]);

        // Get the task assignee (user who originally worked on the task)
        $taskAssignee = null;
        if ($task->user_id) {
            $taskAssignee = $task->user_id; // Task assigned directly to a user
        } else {
            // Find assignee from assignment records
            $assignment = $task->assignments()->where('assignment_status', 'completed')
                                              ->whereNotNull('completed_at')
                                              ->orderBy('completed_at', 'desc')
                                              ->first();
            if ($assignment) {
                $taskAssignee = $assignment->user_id;
            }
        }

        // Update task assignee status back to working (since they need to continue working)
        if ($taskAssignee) {
            User::where('id', $taskAssignee)->update(['status' => 'working']);

            // Update assignment status to in_progress for the assignee
            $assignment = $task->assignments()->where('user_id', $taskAssignee)->first();
            if ($assignment) {
                $assignment->update([
                    'assignment_status' => 'in_progress'
                ]);
            }
        }

        // Create assignment history record for rejection
        Card_Assigment::create([
            'card_id' => $task->id,
            'user_id' => $user->id, // Leader who rejected
            'assigned_at' => now(),
            'assignment_status' => 'in_progress',
            'started_at' => $task->started_at,
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Task "' . $task->card_title . '" direject dan dikembalikan ke status todo.');
    }
}
