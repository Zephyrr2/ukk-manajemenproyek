<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Subtask;
use App\Models\Time_Log;

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
            'todo' => $subtasks->where('status', 'todo')->count(),
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

        $pageSubtitle = 'Manage subtasks for: ' . $task->card_title;

        return view('pages.user.subtask', compact('task', 'subtasks', 'subtaskStats', 'user', 'activeSession', 'pausedSession', 'pageSubtitle'));
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

        // Check if task is already completed
        if ($task->status === 'done') {
            return redirect()->route('user.subtasks', $taskId)->with('error', 'Cannot add subtask to a completed task.');
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
            'status' => 'nullable|in:todo,in_progress,done',
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

        // Check if task is already completed
        if ($task->status === 'done') {
            return redirect()->route('user.subtasks', $taskId)->with('error', 'Cannot add subtask to a completed task.');
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
            'status' => $request->status ?? 'todo'
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

        return redirect()->route('user.subtasks', $taskId)->with('success', 'Subtask berhasil diupdate!');
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
     * Start working on a subtask (todo -> in_progress)
     */
    public function start($taskId, $subtaskId)
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

        // Check if there's already an active time log for THIS SUBTASK specifically
        $activeSubtaskLog = \App\Models\Time_Log::where('user_id', $user->id)
            ->where('subtask_id', $subtaskId)
            ->whereNull('end_time')
            ->first();

        if ($activeSubtaskLog) {
            return redirect()->back()->with('error', 'Subtask ini sudah dalam status berjalan.');
        }

        // Check if user has another subtask running (not parent task)
        // This allows user to work on subtask even if parent task is running
        $otherActiveSubtask = \App\Models\Time_Log::where('user_id', $user->id)
            ->whereNotNull('subtask_id') // Only check for other subtasks, ignore parent task time logs
            ->where('subtask_id', '!=', $subtaskId)
            ->whereNull('end_time')
            ->first();

        if ($otherActiveSubtask) {
            $otherSubtask = \App\Models\Subtask::find($otherActiveSubtask->subtask_id);
            $subtaskTitle = $otherSubtask ? $otherSubtask->subtask_title : 'subtask lain';
            return redirect()->back()->with('error', 'Anda sudah memiliki subtask lain yang sedang berjalan: "' . $subtaskTitle . '". Silakan pause atau selesaikan terlebih dahulu.');
        }

        // Update subtask status to in_progress
        $subtask->update(['status' => 'in_progress']);

        // Create new time log entry
        \App\Models\Time_Log::create([
            'card_id' => $taskId,
            'subtask_id' => $subtaskId,
            'user_id' => $user->id,
            'start_time' => now(),
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Subtask dimulai! Timer aktif.');
    }

    /**
     * Pause working on a subtask
     */
    public function pause($taskId, $subtaskId)
    {
        $user = Auth::user();

        // Fetch subtask with card relationship
        $subtask = Subtask::with('card')->findOrFail($subtaskId);
        $cardTitle = $subtask->card->card_title;
        $subtaskTitle = $subtask->subtask_title;

        // Find the active time log for this subtask
        $timeLog = Time_Log::where('user_id', $user->id)
            ->where('subtask_id', $subtaskId)
            ->whereNull('end_time')
            ->where('status', 'active')
            ->first();

        if (!$timeLog) {
            return redirect()->back()->with('error', 'No active work session found.');
        }

        // Record the pause time
        $pauseTime = now();
        $startTime = \Carbon\Carbon::parse($timeLog->start_time);
        $durationMinutes = $startTime->diffInMinutes($pauseTime);

        // Update the current time log to mark completion of work session before pause
        $timeLog->update([
            'end_time' => $pauseTime,
            'duration_minutes' => $durationMinutes,
            'status' => 'paused',  // Changed to 'paused' to indicate it's paused, not fully completed
            'description' => "Work session paused - Subtask '{$subtaskTitle}' from Card '{$cardTitle}' - {$durationMinutes} minutes"
        ]);

        // Keep subtask status as 'in_progress', we'll check paused state from time_logs
        // $subtask->update(['status' => 'paused']); // Don't update subtask status

        return redirect()->back()->with('success', 'Subtask paused. Duration: ' . $durationMinutes . ' minutes.');
    }

    /**
     * Resume working on a subtask
     */
    public function resume($taskId, $subtaskId)
    {
        $user = Auth::user();

        // Fetch subtask with card relationship
        $subtask = Subtask::with('card')->findOrFail($subtaskId);
        $cardTitle = $subtask->card->card_title;
        $subtaskTitle = $subtask->subtask_title;

        // Find the most recent paused time log for this subtask (has end_time and status = paused)
        $pausedLog = Time_Log::where('user_id', $user->id)
            ->where('subtask_id', $subtaskId)
            ->where('status', 'paused')
            ->whereNotNull('end_time')
            ->orderBy('end_time', 'desc')
            ->first();

        if (!$pausedLog) {
            return redirect()->back()->with('error', 'No paused work session found.');
        }

        $resumeTime = now();

        // Create a new active work session
        Time_Log::create([
            'user_id' => $user->id,
            'card_id' => $taskId,
            'subtask_id' => $subtaskId,
            'start_time' => $resumeTime,
            'end_time' => null,
            'duration_minutes' => 0,
            'status' => 'active',
            'description' => "Resumed work on Subtask '{$subtaskTitle}' from Card '{$cardTitle}' at " . $resumeTime->format('H:i:s d/m/Y')
        ]);

        // Subtask status remains 'in_progress', no need to update

        return redirect()->back()->with('success', 'Subtask resumed! Time tracking restarted.');
    }

    /**
     * Complete a subtask (in_progress -> done)
     */
    public function complete($taskId, $subtaskId)
    {
        $user = Auth::user();

        // Find subtask and verify access (with card relationship)
        $subtask = Subtask::with('card')->whereHas('card.board.project', function ($query) use ($user) {
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
            return redirect()->back()->with('error', 'Subtask not found or you do not have access.');
        }

        $cardTitle = $subtask->card->card_title;
        $subtaskTitle = $subtask->subtask_title;
        $completionTime = now();

        // If there's an active time log, close it
        $activeLog = Time_Log::where('user_id', $user->id)
            ->where('subtask_id', $subtaskId)
            ->whereNull('end_time')
            ->where('status', 'active')
            ->first();

        if ($activeLog) {
            $startTime = \Carbon\Carbon::parse($activeLog->start_time);
            $durationMinutes = $startTime->diffInMinutes($completionTime);

            $activeLog->update([
                'end_time' => $completionTime,
                'duration_minutes' => $durationMinutes,
                'status' => 'completed',
                'description' => "Final work session - Subtask '{$subtaskTitle}' from Card '{$cardTitle}' - {$durationMinutes} minutes"
            ]);
        }

        // Calculate total actual hours from all work sessions (completed and paused)
        $totalMinutes = Time_Log::where('subtask_id', $subtaskId)
            ->whereIn('status', ['completed', 'paused'])
            ->whereNotNull('duration_minutes')
            ->where('duration_minutes', '>', 0)  // Only count entries with actual duration
            ->sum('duration_minutes');

        $actualHours = round($totalMinutes / 60, 2);

        // Update subtask status to done
        $subtask->update([
            'status' => 'done',
            'actual_hours' => $actualHours
        ]);

        // Always create a completion summary entry to show in time log
        Time_Log::create([
            'user_id' => $user->id,
            'card_id' => $taskId,
            'subtask_id' => $subtaskId,
            'start_time' => $completionTime,
            'end_time' => $completionTime,
            'duration_minutes' => $totalMinutes,  // Show total time
            'status' => 'completed',
            'description' => "✓ Completed Subtask '{$subtaskTitle}' from Card '{$cardTitle}'. Total work time: {$actualHours} hours ({$totalMinutes} minutes)"
        ]);

        return redirect()->back()->with('success', 'Subtask completed! Total work time: ' . $actualHours . ' hours (' . $totalMinutes . ' minutes).');
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
