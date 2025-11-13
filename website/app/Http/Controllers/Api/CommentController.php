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

            $comments = Comment::where('card_id', $taskId)
                             ->where('comment_type', 'card')
                             ->with('user')
                             ->orderBy('created_at', 'desc')
                             ->get();

            // Transform data for API consistency
            $comments = $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'commentable_type' => 'App\\Models\\Card',
                    'commentable_id' => $comment->card_id,
                    'user_id' => $comment->user_id,
                    'content' => $comment->comment_text,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                    'user' => $comment->user,
                ];
            });

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
                'card_id' => $taskId,
                'user_id' => $user->id,
                'comment_text' => $request->input('content'),
                'comment_type' => 'card',
            ]);

            $comment->load('user');

            // Transform for API consistency
            $commentData = [
                'id' => $comment->id,
                'commentable_type' => 'App\\Models\\Card',
                'commentable_id' => $comment->card_id,
                'user_id' => $comment->user_id,
                'content' => $comment->comment_text,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
                'user' => $comment->user,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $commentData
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
