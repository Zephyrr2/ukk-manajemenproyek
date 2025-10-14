<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Find the task and verify leader access (project must belong to leader)
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('id', $taskId)
        ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Create comment
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

        // Find the subtask and verify leader access
        $subtask = Subtask::whereHas('card.board.project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('id', $subtaskId)
        ->where('card_id', $taskId)
        ->first();

        if (!$subtask) {
            return redirect()->back()->with('error', 'Subtask tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Create comment
        Comment::create([
            'subtask_id' => $subtaskId,
            'user_id' => $user->id,
            'comment_text' => $request->comment_text,
            'comment_type' => 'subtask'
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Delete a comment (leader can delete any comment in their projects)
     */
    public function destroy($commentId)
    {
        $user = Auth::user();

        $comment = Comment::with(['card.board.project', 'subtask.card.board.project'])
                         ->where('id', $commentId)
                         ->first();

        if (!$comment) {
            return redirect()->back()->with('error', 'Komentar tidak ditemukan.');
        }

        // Check if leader owns the project where this comment belongs
        $projectOwner = null;
        if ($comment->card) {
            $projectOwner = $comment->card->board->project->user_id;
        } elseif ($comment->subtask) {
            $projectOwner = $comment->subtask->card->board->project->user_id;
        }

        if ($projectOwner !== $user->id && $comment->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus komentar ini.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}
