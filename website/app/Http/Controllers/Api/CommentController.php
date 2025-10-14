<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Get comments for a task
     */
    public function getTaskComments($taskId)
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

            $comments = Comment::where('commentable_type', 'App\\Models\\Card')
                             ->where('commentable_id', $taskId)
                             ->with('user')
                             ->orderBy('created_at', 'desc')
                             ->get();

            return response()->json([
                'success' => true,
                'data' => $comments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add comment to task
     */
    public function addTaskComment(Request $request, $taskId)
    {
        try {
            $request->validate([
                'content' => 'required|string'
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

            $comment = Comment::create([
                'commentable_type' => 'App\\Models\\Card',
                'commentable_id' => $taskId,
                'user_id' => $user->id,
                'content' => $request->content
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $comment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
