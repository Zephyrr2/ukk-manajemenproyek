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

            $projects = Project::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                          $subQuery->where('user_id', $user->id);
                      });
            })->with(['boards.cards.user', 'boards.cards.assignments.user'])->get();

            $allTasks = collect();
            foreach ($projects as $project) {
                foreach ($project->boards as $board) {
                    $allTasks = $allTasks->merge($board->cards);
                }
            }

            $myTasks = $allTasks->filter(function ($task) use ($user) {
                return $task->user_id == $user->id ||
                       $task->assignments->where('user_id', $user->id)->isNotEmpty();
            });

            return response()->json([
                'success' => true,
                'data' => $myTasks->values()
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

            // Check for pending subtasks if status is changing to done
            $pendingSubtasks = 0;
            if ($request->status === 'done' && $oldStatus !== 'done') {
                $pendingSubtasks = $task->getPendingSubtasksCount();
            }

            $task->update(['status' => $request->status]);

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
