<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Subtask;

class SubtaskController extends Controller
{
    /**
     * Display subtasks for a specific task
     */
    public function index($taskId)
    {
        $user = Auth::user();

        // Find the task and verify access
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                    $memberQuery->where('user_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user', 'assignments'])
        ->first();

        if (!$task) {
            return redirect()->route('user.tasks')->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Check if user is assigned to this task or is the task creator
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignments->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            return redirect()->route('user.tasks')->with('error', 'Anda tidak memiliki akses untuk melihat subtask ini.');
        }

        // Get subtasks for this task with their subtask-level comments only
        $subtasks = Subtask::where('card_id', $taskId)
                          ->with(['comments' => function ($query) {
                              $query->whereNotNull('subtask_id') // Only subtask-level comments
                                    ->with('user')
                                    ->orderBy('created_at', 'desc');
                          }])
                          ->orderBy('position')
                          ->get();

        // Calculate subtask statistics
        $subtaskStats = [
            'total' => $subtasks->count(),
            'in_progress' => $subtasks->where('status', 'in_progress')->count(),
            'done' => $subtasks->where('status', 'done')->count(),
            'total_estimated' => $subtasks->sum('estimated_hours'),
            'total_actual' => $subtasks->where('status', 'done')->sum('actual_hours'),
        ];

        // Get active session for time tracking buttons
        $activeSession = \App\Models\Time_Log::where('user_id', $user->id)
            ->whereNull('end_time')
            ->first();

        // Get last paused session (for resume button)
        $pausedSession = \App\Models\Time_Log::where('user_id', $user->id)
            ->where('status', 'paused')
            ->whereNotNull('end_time')
            ->orderBy('end_time', 'desc')
            ->first();

        return view('pages.user.subtask', compact('task', 'subtasks', 'subtaskStats', 'user', 'activeSession', 'pausedSession'));
    }

    /**
     * Show the form for creating a new subtask
     */
    public function create($taskId)
    {
        $user = Auth::user();

        // Find the task and verify access
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->whereHas('projectMembers', function ($memberQuery) use ($user) {
                    $memberQuery->where('user_id', $user->id);
                })->orWhere('user_id', $user->id);
            });
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user', 'assignments'])
        ->first();

        if (!$task) {
            return redirect()->route('user.tasks')->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Check if user is assigned to this task or is the task creator
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignments->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            return redirect()->route('user.tasks')->with('error', 'Anda tidak memiliki akses untuk membuat subtask.');
        }

        return view('pages.user.subtask-create', compact('task'));
    }

    /**
     * Store a newly created subtask
     */
    public function store(Request $request, $taskId)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'subtask_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_hours' => 'nullable|numeric|min:0|max:999.99',
            'status' => 'required|in:in_progress,done',
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

        // Check access
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignments->where('user_id', $user->id)->isNotEmpty();

        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membuat subtask.');
        }

        // Get next position
        $nextPosition = Subtask::where('card_id', $taskId)->max('position') + 1;

        // Create subtask
        Subtask::create([
            'card_id' => $taskId,
            'user_id' => $user->id,
            'subtask_title' => $request->subtask_title,
            'description' => $request->description,
            'estimated_hours' => $request->estimated_hours,
            'position' => $nextPosition,
            'status' => $request->status ?? 'in_progress'
        ]);

        // Handle different actions
        $action = $request->input('action', 'create_and_view');

        if ($action === 'create_and_continue') {
            return redirect()->route('user.subtasks.create', $taskId)->with('success', 'Subtask berhasil dibuat! Anda dapat menambahkan subtask lainnya.');
        }

        return redirect()->route('user.subtasks', $taskId)->with('success', 'Subtask berhasil dibuat!');
    }

    /**
     * Show the form for editing a subtask
     */
    public function edit($taskId, $subtaskId)
    {
        $user = Auth::user();

        // Find the task and verify access
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
            return redirect()->route('user.tasks')->with('error', 'Task tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Find subtask and verify access
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
            return redirect()->route('user.subtasks', $taskId)->with('error', 'Subtask tidak ditemukan atau Anda tidak memiliki akses.');
        }

        return view('pages.user.subtask-edit', compact('task', 'subtask'));
    }

    /**
     * Update an existing subtask
     */
    public function update(Request $request, $taskId, $subtaskId)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'subtask_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        // Find subtask and verify access
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

        // Update subtask
        $subtask->update([
            'subtask_title' => $request->subtask_title,
            'description' => $request->description,
            'estimated_hours' => $request->estimated_hours,
        ]);

        return redirect()->back()->with('success', 'Subtask berhasil diupdate!');
    }

    /**
     * Toggle subtask status (in_progress <-> done)
     */
    public function toggleStatus($taskId, $subtaskId)
    {
        $user = Auth::user();

        // Find subtask and verify access
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

        // Toggle status and calculate actual hours if completing
        if ($subtask->status === 'in_progress') {
            // Mark as done and calculate actual hours
            $actualHours = null;
            if ($subtask->estimated_hours) {
                // For demo purposes, we'll use estimated hours as actual hours
                // In real implementation, you might want to track start time
                $actualHours = $subtask->estimated_hours;
            }

            $subtask->update([
                'status' => 'done',
                'actual_hours' => $actualHours
            ]);

            $message = 'Subtask berhasil diselesaikan!';
        } else {
            // Mark as in progress and reset actual hours
            $subtask->update([
                'status' => 'in_progress',
                'actual_hours' => null
            ]);

            $message = 'Subtask dikembalikan ke status in progress.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete a subtask
     */
    public function destroy($taskId, $subtaskId)
    {
        $user = Auth::user();

        // Find subtask and verify access
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

        $subtask->delete();

        return redirect()->back()->with('success', 'Subtask berhasil dihapus!');
    }
}
