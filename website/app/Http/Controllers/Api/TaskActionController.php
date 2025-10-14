<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskActionController extends Controller
{
    /**
     * Start working on a task
     */
    public function startTask($taskId)
    {
        try {
            $user = Auth::user();

            $task = Card::whereHas('board.project', function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    })->orWhere('user_id', $user->id);
                });
            })
            ->where('id', $taskId)
            ->with(['assignments'])
            ->first();

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            // Check if user is assigned to this task
            $hasAccess = $task->user_id == $user->id ||
                        $task->assignments->where('user_id', $user->id)->isNotEmpty();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this task'
                ], 403);
            }

            // Only allow start if task is todo
            if ($task->status !== 'todo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Task can only be started if status is To Do'
                ], 400);
            }

            // Update task status to in_progress
            $task->update([
                'status' => 'in_progress',
                'started_at' => now()
            ]);

            // Update user status to working
            User::where('id', $user->id)->update(['status' => 'working']);

            return response()->json([
                'success' => true,
                'message' => 'Task started successfully',
                'data' => [
                    'id' => $task->id,
                    'status' => $task->status,
                    'started_at' => $task->started_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit task for review
     */
    public function submitTask($taskId)
    {
        try {
            $user = Auth::user();

            $task = Card::whereHas('board.project', function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    })->orWhere('user_id', $user->id);
                });
            })
            ->where('id', $taskId)
            ->with(['assignments'])
            ->first();

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            // Check if user is assigned to this task
            $hasAccess = $task->user_id == $user->id ||
                        $task->assignments->where('user_id', $user->id)->isNotEmpty();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this task'
                ], 403);
            }

            // Only allow submit if task is in_progress
            if ($task->status !== 'in_progress') {
                return response()->json([
                    'success' => false,
                    'message' => 'Task can only be submitted if status is In Progress'
                ], 400);
            }

            // Update task status to review
            $task->update(['status' => 'review']);

            // Update user status to available
            User::where('id', $user->id)->update(['status' => 'available']);

            return response()->json([
                'success' => true,
                'message' => 'Task submitted for review successfully',
                'data' => [
                    'id' => $task->id,
                    'status' => $task->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit task',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
