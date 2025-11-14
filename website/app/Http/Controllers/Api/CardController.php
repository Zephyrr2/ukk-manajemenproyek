<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            // Ambil task yang user buat atau di-assign ke user
            // EXCLUDE task yang sudah done/complete
            $myTasks = Card::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('assignments', function ($subQuery) use ($user) {
                          $subQuery->where('user_id', $user->id);
                      });
            })
            ->where('status', '!=', 'done') // Exclude completed tasks
            ->with(['user', 'assignments.user', 'board'])
            ->orderBy('due_date', 'asc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $myTasks
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $user = Auth::user();
            $task = Card::with(['board.project', 'user', 'assignments.user'])->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $task
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load task details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, string $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:todo,in_progress,review,done'
            ]);

            $task = Card::find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], 404);
            }

            $oldStatus = $task->status;

            // STOP ACTIVE TIMER when task moves to review or done
            // Timer should stop when task is no longer in active work
            if (in_array($request->status, ['review', 'done']) && $oldStatus === 'in_progress') {
                // Stop any active time logs for this task
                $activeLogs = \App\Models\Time_Log::where('card_id', $task->id)
                    ->whereNull('end_time')
                    ->get();

                foreach ($activeLogs as $log) {
                    $log->stopWorkSession();
                    \Illuminate\Support\Facades\Log::info('Auto-stopped timer when task status changed', [
                        'task_id' => $task->id,
                        'old_status' => $oldStatus,
                        'new_status' => $request->status,
                        'time_log_id' => $log->id
                    ]);
                }
            }

            // Check for pending subtasks if status is changing to done
            $pendingSubtasks = 0;
            if ($request->status === 'done' && $oldStatus !== 'done') {
                $pendingSubtasks = $task->getPendingSubtasksCount();
            }

            $task->update(['status' => $request->status]);

            // Update assigned user status to 'free' when task is done
            if ($request->status === 'done' && $oldStatus !== 'done') {
                // Get all assigned users for this task
                $assignedUserIds = $task->assignments()->pluck('user_id')->toArray();

                // Update each assigned user's status to 'free'
                if (!empty($assignedUserIds)) {
                    \App\Models\User::whereIn('id', $assignedUserIds)->update(['status' => 'free']);

                    \Illuminate\Support\Facades\Log::info('Updated user status to free when task completed', [
                        'task_id' => $task->id,
                        'user_ids' => $assignedUserIds
                    ]);
                }
            }

            // Create response message
            $message = 'Task status updated successfully';
            if ($pendingSubtasks > 0) {
                $message .= " ({$pendingSubtasks} subtasks auto-completed)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $task->id,
                    'old_status' => $oldStatus,
                    'new_status' => $task->status,
                    'subtasks_completed' => $pendingSubtasks
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
