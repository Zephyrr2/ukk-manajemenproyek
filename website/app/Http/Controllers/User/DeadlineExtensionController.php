<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeadlineExtensionController extends Controller
{
    /**
     * Request deadline extension
     */
    public function requestExtension(Request $request, $taskId)
    {
        $user = Auth::user();

        $request->validate([
            'new_deadline' => 'required|date|after:today',
            'reason' => 'required|string|max:500',
        ]);

        // Find the task
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                    $memberQuery->where('user_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        })
        ->where('id', $taskId)
        ->with(['board.project'])
        ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found or you do not have access.');
        }

        // Check if user is assigned to this task
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignments->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            return redirect()->back()->with('error', 'You are not assigned to this task.');
        }

        // Check if already has pending request
        if ($task->extension_status === 'pending') {
            return redirect()->back()->with('error', 'You already have a pending extension request for this task.');
        }

        // Update task with extension request
        $task->update([
            'extension_requested_date' => $request->new_deadline,
            'extension_reason' => $request->reason,
            'extension_status' => 'pending',
        ]);

        // Create notification for project leader
        $projectLeader = $task->board->project->user;
        if ($projectLeader) {
            Notification::create([
                'user_id' => $projectLeader->id,
                'type' => 'extension_request',
                'title' => 'Deadline Extension Request',
                'message' => $user->name . ' requested to extend deadline for task "' . $task->card_title . '" to ' . $request->new_deadline . '.',
                'card_id' => $task->id,
                'data' => [
                    'task_id' => $task->id,
                    'task_title' => $task->card_title,
                    'requested_by' => $user->name,
                    'current_deadline' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'requested_deadline' => $request->new_deadline,
                    'reason' => $request->reason,
                    'project_slug' => $task->board->project->slug,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Extension request submitted successfully. Waiting for leader approval.');
    }

    /**
     * Approve extension request (Leader only)
     */
    public function approveExtension($taskId)
    {
        $user = Auth::user();

        // Find the task and verify user is project leader
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user'])
        ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found or you do not have permission.');
        }

        // Check if there's a pending extension request
        if ($task->extension_status !== 'pending') {
            return redirect()->back()->with('error', 'No pending extension request for this task.');
        }

        // Update deadline and status
        $oldDeadline = $task->due_date;
        $task->update([
            'due_date' => $task->extension_requested_date,
            'extension_status' => 'approved',
        ]);

        // Create notification for task assignee
        $taskAssignee = $task->user;
        if ($taskAssignee) {
            Notification::create([
                'user_id' => $taskAssignee->id,
                'type' => 'extension_approved',
                'title' => 'Deadline Extension Approved',
                'message' => 'Your deadline extension request for task "' . $task->card_title . '" has been approved. New deadline: ' . $task->due_date->format('d M Y') . '.',
                'card_id' => $task->id,
                'data' => [
                    'task_id' => $task->id,
                    'task_title' => $task->card_title,
                    'approved_by' => $user->name,
                    'old_deadline' => $oldDeadline ? $oldDeadline->format('Y-m-d') : null,
                    'new_deadline' => $task->due_date->format('Y-m-d'),
                    'project_slug' => $task->board->project->slug,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Extension request approved. Deadline updated to ' . $task->due_date->format('d M Y') . '.');
    }

    /**
     * Reject extension request (Leader only)
     */
    public function rejectExtension($taskId)
    {
        $user = Auth::user();

        // Find the task and verify user is project leader
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user'])
        ->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found or you do not have permission.');
        }

        // Check if there's a pending extension request
        if ($task->extension_status !== 'pending') {
            return redirect()->back()->with('error', 'No pending extension request for this task.');
        }

        // Update status
        $task->update([
            'extension_status' => 'rejected',
        ]);

        // Create notification for task assignee
        $taskAssignee = $task->user;
        if ($taskAssignee) {
            Notification::create([
                'user_id' => $taskAssignee->id,
                'type' => 'extension_rejected',
                'title' => 'Deadline Extension Rejected',
                'message' => 'Your deadline extension request for task "' . $task->card_title . '" has been rejected. Please complete the task by the original deadline.',
                'card_id' => $task->id,
                'data' => [
                    'task_id' => $task->id,
                    'task_title' => $task->card_title,
                    'rejected_by' => $user->name,
                    'original_deadline' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'project_slug' => $task->board->project->slug,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Extension request rejected.');
    }
}
