<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Card;
use App\Models\Subtask;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Store a new comment for a task
     */
    public function storeTaskComment(Request $request, $taskId)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);

        // Find the task and verify access
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                    $memberQuery->where('user_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        })
        ->where('id', $taskId)
        ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Check if user is assigned to this task or is the task creator or project member
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignments->where('user_id', $user->id)->isNotEmpty() ||
                    $task->board->project->user_id == $user->id ||
                    $task->board->project->projectMembers->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengomentari task ini.');
        }

        // Create new comment (allow multiple comments per user)
        Comment::create([
            'card_id' => $taskId,
            'user_id' => $user->id,
            'comment_text' => $request->comment_text,
            'comment_type' => 'card'
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Store a new comment for a subtask
     */
    public function storeSubtaskComment(Request $request, $taskId, $subtaskId)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);

        // Find the subtask and verify access
        $subtask = Subtask::whereHas('card.board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                    $memberQuery->where('user_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        })
        ->where('id', $subtaskId)
        ->where('card_id', $taskId)
        ->first();

        if (!$subtask) {
            return redirect()->back()->with('error', 'Subtask tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Check access
        $hasAccess = $subtask->user_id == $user->id ||
                    $subtask->card->user_id == $user->id ||
                    $subtask->card->assignments->where('user_id', $user->id)->isNotEmpty() ||
                    $subtask->card->board->project->user_id == $user->id ||
                    $subtask->card->board->project->projectMembers->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengomentari subtask ini.');
        }

        // Create new comment (allow multiple comments per user)
        Comment::create([
            'card_id' => $subtask->card_id,
            'subtask_id' => $subtaskId,
            'user_id' => $user->id,
            'comment_text' => $request->comment_text,
            'comment_type' => 'subtask'
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Delete a comment (only owner can delete)
     */
    public function destroy($commentId)
    {
        $user = Auth::user();

        $comment = Comment::where('id', $commentId)
                         ->where('user_id', $user->id)
                         ->first();

        if (!$comment) {
            return redirect()->back()->with('error', 'Komentar tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}
