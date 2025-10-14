<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Card;
use App\Models\Board;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get projects where current user is assigned as member or created by them
        $userProjects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id) // Projects created by this user
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id); // Projects where user is a member
                  });
        })
        ->with(['user', 'boards.cards', 'projectMembers.user'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate project statistics
        $projectStats = $userProjects->map(function ($project) {
            $totalCards = 0;
            $completedCards = 0;
            $inProgressCards = 0;
            $todoCards = 0;

            foreach ($project->boards as $board) {
                $cards = $board->cards;
                $totalCards += $cards->count();
                $completedCards += $cards->where('status', 'done')->count();
                $inProgressCards += $cards->where('status', 'in_progress')->count();
                $todoCards += $cards->where('status', 'todo')->count();
            }

            $progress = $totalCards > 0 ? round(($completedCards / $totalCards) * 100, 1) : 0;

            $project->total_tasks = $totalCards;
            $project->completed_tasks = $completedCards;
            $project->in_progress_tasks = $inProgressCards;
            $project->todo_tasks = $todoCards;
            $project->progress_percentage = $progress;

            // Add team members count (including project creator)
            $project->team_members_count = $project->projectMembers->count() + 1; // +1 for creator

            return $project;
        });

        // Auto-redirect logic: If user has only one project, redirect to it
        if ($userProjects->count() === 1 && !$request->has('show_all')) {
            $project = $userProjects->first();
            $firstBoard = $project->boards->first();

            if ($firstBoard) {
                return redirect()->route('leader.projects.board', $project->id)
                                ->with('message', 'Otomatis diarahkan ke project Anda: ' . $project->project_name);
            }
        }

        // Calculate overall statistics
        $totalActiveProjects = $userProjects->count();
        $totalCompletedTasks = $projectStats->sum('completed_tasks');
        $totalPendingTasks = $projectStats->sum(function ($project) {
            return $project->total_tasks - $project->completed_tasks;
        });
        $totalTeamMembers = $projectStats->sum('team_members_count');

        return view('pages.leader.projects', compact(
            'userProjects',
            'projectStats',
            'totalActiveProjects',
            'totalCompletedTasks',
            'totalPendingTasks',
            'totalTeamMembers'
        ));
    }

    public function board($projectId)
    {
        $user = Auth::user();

        // Check if user has access to this project
        $project = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->where('id', $projectId)
        ->with(['boards.cards.assignedUsers', 'user', 'projectMembers.user'])
        ->first();

        if (!$project) {
            return redirect()->route('leader.projects')
                           ->with('error', 'Anda tidak memiliki akses ke project ini.');
        }

        // Get or create default board for this project
        $board = Board::firstOrCreate([
            'project_id' => $project->id
        ], [
            'board_name' => $project->project_name . ' Board',
            'description' => 'Kanban board for ' . $project->project_name,
            'position' => 1
        ]);

        // Get cards from database, grouped by status
        $cards = Card::where('board_id', $board->id)
            ->with(['user', 'assignedUsers', 'comments.user'])
            ->orderBy('position')
            ->get();

        $boardData = [
            'todo' => $cards->where('status', 'todo')->values()->all(),
            'in_progress' => $cards->where('status', 'in_progress')->values()->all(),
            'review' => $cards->where('status', 'review')->values()->all(),
            'done' => $cards->where('status', 'done')->values()->all(),
        ];

        // Calculate project progress
        $totalCards = $cards->count();
        $completedCards = $cards->where('status', 'done')->count();
        $progressPercentage = $totalCards > 0 ? round(($completedCards / $totalCards) * 100, 1) : 0;

        // Get team members - creator + members
        $teamMembers = collect([$project->user]);
        foreach ($project->projectMembers as $member) {
            $teamMembers->push($member->user);
        }
        $teamMembers = $teamMembers->unique('id');

        return view('pages.leader.board', compact(
            'project',
            'board',
            'boardData',
            'progressPercentage',
            'teamMembers',
            'totalCards',
            'completedCards'
        ));
    }

    /**
     * Show project details
     */
    public function show($projectId)
    {
        $user = Auth::user();

        $project = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->where('id', $projectId)
        ->with(['user', 'boards.cards', 'projectMembers.user'])
        ->firstOrFail();

        // Calculate statistics
        $totalCards = $project->boards->flatMap->cards->count();
        $completedCards = $project->boards->flatMap->cards->where('status', 'done')->count();
        $inProgressCards = $project->boards->flatMap->cards->where('status', 'in_progress')->count();
        $todoCards = $project->boards->flatMap->cards->where('status', 'todo')->count();

        $progressPercentage = $totalCards > 0 ? round(($completedCards / $totalCards) * 100, 1) : 0;

        return view('pages.leader.project-detail', compact(
            'project',
            'totalCards',
            'completedCards',
            'inProgressCards',
            'todoCards',
            'progressPercentage'
        ));
    }

    /**
     * Update task status (for AJAX drag & drop)
     */
    /**
     * Update task status (for drag and drop or manual updates)
     */
    public function updateTaskStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer',
            'status' => 'required|in:todo,in_progress,review,done',
            'project_id' => 'required|integer'
        ]);

        $user = Auth::user();

        // Check if user has access to this project
        $project = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->where('id', $request->project_id)
        ->first();

        if (!$project) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update card status
        $card = Card::whereHas('board', function ($query) use ($request) {
            $query->where('project_id', $request->project_id);
        })->where('id', $request->task_id)->with(['user', 'assignedUsers'])->first();

        if ($card) {
            $oldStatus = $card->status;
            $newStatus = $request->status;

            // Update task status and started_at if moving to in_progress
            $updateData = ['status' => $newStatus];
            if ($newStatus === 'in_progress' && $oldStatus !== 'in_progress') {
                $updateData['started_at'] = now();
            }

            $card->update($updateData);

            // Update assignment status based on task status
            if ($card->assignedUsers()->exists()) {
                $assignmentStatus = match($newStatus) {
                    'todo' => 'assigned',
                    'in_progress' => 'in_progress',
                    'review', 'done' => 'completed',
                    default => 'assigned'
                };

                foreach ($card->assignedUsers as $assignedUser) {
                    $card->assignedUsers()->updateExistingPivot($assignedUser->id, [
                        'assignment_status' => $assignmentStatus,
                        'started_at' => $newStatus === 'in_progress' ? now() : null,
                        'completed_at' => in_array($newStatus, ['review', 'done']) ? now() : null,
                    ]);

                    // Update user status based on task status
                    if ($newStatus === 'in_progress' && $oldStatus !== 'in_progress') {
                        // Task started - user becomes working
                        User::where('id', $assignedUser->id)->update(['status' => 'working']);
                    } elseif (in_array($newStatus, ['review', 'done']) && $oldStatus === 'in_progress') {
                        // Task completed/reviewed - user becomes free
                        User::where('id', $assignedUser->id)->update(['status' => 'free']);
                    } elseif ($newStatus === 'todo' && $oldStatus === 'in_progress') {
                        // Task moved back to todo from in_progress - user becomes free
                        User::where('id', $assignedUser->id)->update(['status' => 'free']);
                    }
                }
            } elseif ($card->user_id) {
                // Handle direct assigned user (not through assignedUsers relationship)
                if ($newStatus === 'in_progress' && $oldStatus !== 'in_progress') {
                    User::where('id', $card->user_id)->update(['status' => 'working']);
                } elseif (in_array($newStatus, ['review', 'done']) && $oldStatus === 'in_progress') {
                    User::where('id', $card->user_id)->update(['status' => 'free']);
                } elseif ($newStatus === 'todo' && $oldStatus === 'in_progress') {
                    User::where('id', $card->user_id)->update(['status' => 'free']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Status task berhasil diupdate!',
                'task' => [
                    'id' => $card->id,
                    'title' => $card->card_title,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]
            ]);
        }

        return response()->json(['error' => 'Task not found'], 404);
    }

    public function createTask($projectId)
    {
        $user = Auth::user();

        // Check if user has access to this project
        $project = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->where('id', $projectId)
        ->with(['user', 'projectMembers.user'])
        ->first();

        if (!$project) {
            return redirect()->route('leader.projects')
                           ->with('error', 'Anda tidak memiliki akses ke project ini.');
        }

        // Get or create default board for this project
        $board = Board::firstOrCreate([
            'project_id' => $project->id
        ], [
            'board_name' => $project->project_name . ' Board',
            'description' => 'Kanban board for ' . $project->project_name,
            'position' => 1
        ]);

        return view('pages.leader.create-task', compact('project', 'board'));
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'board_id' => 'required|integer|exists:boards,id',
            'card_title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,review,done',
            'estimated_hours' => 'nullable|numeric|min:0|max:999.99'
        ]);

        $user = Auth::user();

        // Check if user has access to this project
        $project = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->where('id', $request->project_id)
        ->with('boards')
        ->first();

        if (!$project) {
            return redirect()->route('leader.projects')
                           ->with('error', 'Unauthorized access to project.');
        }

        // Check if board belongs to this project
        $board = $project->boards->where('id', $request->board_id)->first();
        if (!$board) {
            return redirect()->back()
                           ->with('error', 'Board not found for this project.')
                           ->withInput();
        }

        // Check if assigned user is a project member or owner
        $isProjectMember = ProjectMember::where('project_id', $request->project_id)
            ->where('user_id', $request->user_id)
            ->exists();

        if (!$isProjectMember && $request->user_id != $project->user_id) {
            return redirect()->back()
                           ->with('error', 'Assigned user is not a project member.')
                           ->withInput();
        }

        // Generate slug from title
        $slug = Str::slug($request->card_title);
        $originalSlug = $slug;
        $counter = 1;

        // Ensure slug is unique within the board
        while (Card::where('board_id', $request->board_id)->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Get the next position for the card in the specified status
        $lastPosition = Card::where('board_id', $request->board_id)
                           ->where('status', $request->status)
                           ->max('position') ?? 0;

        // Create the card
        $card = Card::create([
            'board_id' => $request->board_id,
            'user_id' => $request->user_id,
            'card_title' => $request->card_title,
            'slug' => $slug,
            'description' => $request->description,
            'position' => $lastPosition + 1,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'priority' => $request->priority,
            'estimated_hours' => $request->estimated_hours
        ]);

        // Handle form action
        $action = $request->input('action', 'create_and_view');

        if ($action === 'create_and_continue') {
            return redirect()->route('leader.projects.create-task', $project->id)
                           ->with('success', 'Task "' . $card->card_title . '" created successfully! Add another task.');
        }

        // Default: create_and_view
        return redirect()->route('leader.projects.board', $project->id)
                       ->with('success', 'Task "' . $card->card_title . '" created successfully!');
    }

    /**
     * Submit task for review (change status from in_progress to review)
     */
    public function submitTask(Request $request, $taskId)
    {
        $user = Auth::user();

        // Find the task and verify access
        $task = Card::whereHas('board.project', function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id)
                         ->orWhereHas('projectMembers', function ($memberQuery) use ($user) {
                             $memberQuery->where('user_id', $user->id);
                         });
            });
        })
        ->where('id', $taskId)
        ->with(['board.project', 'user'])
        ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        // Check if user is assigned to this task or is the task creator
        $hasAccess = $task->user_id == $user->id ||
                    $task->assignedUsers()->where('user_id', $user->id)->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk submit task ini.'
            ], 403);
        }

        // Only allow submit if task is in progress
        if ($task->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Task hanya bisa disubmit jika statusnya sedang dalam progress.'
            ], 400);
        }

        // Update task status to review
        $task->update([
            'status' => 'review',
            'actual_hours' => $request->input('actual_hours', $task->actual_hours),
        ]);

        // Update assignment status if exists
        $task->assignedUsers()->updateExistingPivot($user->id, [
            'assignment_status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil disubmit untuk review!',
            'task' => [
                'id' => $task->id,
                'title' => $task->card_title,
                'status' => $task->status,
            ]
        ]);
    }

    /**
     * Display task assignments page
     */
    public function tasks()
    {
        $user = Auth::user();

        // Get all projects where user is leader or member
        $projects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->with(['boards.cards.user', 'boards.cards.assignedUsers'])
        ->get();

        // Get all tasks from user's projects
        $allTasks = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $allTasks = $allTasks->merge($board->cards);
            }
        }

        // Group tasks by status
        $taskStats = [
            'total' => $allTasks->count(),
            'todo' => $allTasks->where('status', 'todo')->count(),
            'in_progress' => $allTasks->where('status', 'in_progress')->count(),
            'review' => $allTasks->where('status', 'review')->count(),
            'done' => $allTasks->where('status', 'done')->count(),
        ];

        // Get tasks assigned to the current user
        $myTasks = $allTasks->filter(function ($task) use ($user) {
            return $task->user_id == $user->id ||
                   $task->assignedUsers->contains('id', $user->id);
        });

        // Get tasks that need review (for project leaders)
        $tasksForReview = $allTasks->where('status', 'review');

        return view('pages.leader.tasks', compact(
            'projects',
            'taskStats',
            'myTasks',
            'tasksForReview',
            'allTasks'
        ));
    }
}
