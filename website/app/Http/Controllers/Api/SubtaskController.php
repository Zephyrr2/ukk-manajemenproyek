<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subtask;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Subtasks endpoint',
            'data' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Subtask created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'message' => 'Subtask details',
            'id' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Subtask updated successfully',
            'id' => $id
        ]);
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Subtask status updated successfully',
            'id' => $id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Subtask deleted successfully',
            'id' => $id
        ]);
    }

    /**
     * Get subtasks for a task
     */
    public function getTaskSubtasks($taskId)
    {
        try {
            $user = Auth::user();

            // Verify user has access to the task
            $task = Card::whereHas('board.project', function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    })->orWhere('user_id', $user->id);
                });
            })->find($taskId);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            $subtasks = Subtask::where('card_id', $taskId)
                              ->orderBy('created_at', 'asc')
                              ->get();

            return response()->json([
                'success' => true,
                'data' => $subtasks
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load subtasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create subtask
     */
    public function createTaskSubtask(Request $request, $taskId)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'estimated_hours' => 'nullable|numeric|min:0'
            ]);

            $user = Auth::user();

            // Verify user has access to the task
            $task = Card::whereHas('board.project', function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    })->orWhere('user_id', $user->id);
                });
            })->find($taskId);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            $subtask = Subtask::create([
                'card_id' => $taskId,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'todo',
                'estimated_hours' => $request->estimated_hours
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtask created successfully',
                'data' => $subtask
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subtask',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle subtask status
     */
    public function toggleSubtaskStatus($taskId, $subtaskId)
    {
        try {
            $user = Auth::user();

            // Verify user has access to the task
            $task = Card::whereHas('board.project', function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id);
                    })->orWhere('user_id', $user->id);
                });
            })->find($taskId);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            $subtask = Subtask::where('card_id', $taskId)
                             ->where('id', $subtaskId)
                             ->first();

            if (!$subtask) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subtask not found'
                ], 404);
            }

            // Toggle status between todo and done
            $newStatus = $subtask->status === 'done' ? 'todo' : 'done';
            $subtask->update([
                'status' => $newStatus,
                'actual_hours' => $newStatus === 'done' ? $subtask->estimated_hours : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtask status updated successfully',
                'data' => [
                    'id' => $subtask->id,
                    'status' => $subtask->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subtask status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
