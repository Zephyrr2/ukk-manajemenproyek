<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Board;
use App\Models\Card;
use App\Models\Card_Assigment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MainController
{
    public function dashboard()
    {
        // Get total counts
        $totalProjects = Project::count();
        $totalUsers = User::count();

        // Get task counts from cards
        $completedTasks = Card::where('status', 'done')->count();
        $activeTasks = Card::whereIn('status', ['todo', 'in_progress', 'review'])->count();

        // Get overdue tasks (cards past due date)
        $overdueTasks = Card::where('due_date', '<', Carbon::now())
                           ->whereIn('status', ['todo', 'in_progress', 'review'])
                           ->count();

        // Get recent projects with progress calculation
        $recentProjects = Project::with(['user'])
                                ->latest()
                                ->take(5)
                                ->get()
                                ->map(function ($project) {
                                    // Get all cards for this project through boards
                                    $boards = Board::where('project_id', $project->id)->get();
                                    $totalCards = 0;
                                    $completedCards = 0;

                                    foreach ($boards as $board) {
                                        $boardCards = Card::where('board_id', $board->id)->get();
                                        $totalCards += $boardCards->count();
                                        $completedCards += $boardCards->where('status', 'done')->count();
                                    }

                                    $progress = $totalCards > 0 ? round(($completedCards / $totalCards) * 100, 1) : 0;

                                    return [
                                        'id' => $project->id,
                                        'project_name' => $project->project_name,
                                        'slug' => $project->slug,
                                        'progress' => $progress,
                                        'deadline' => $project->deadline,
                                        'creator' => $project->user->name ?? 'Unknown'
                                    ];
                                });

        // Get active team members with task counts
        $teamMembers = User::with(['assignedCards'])
                          ->where('status', 'working')
                          ->take(5)
                          ->get()
                          ->map(function ($user) {
                              $assignments = Card_Assigment::where('user_id', $user->id)->get();
                              $completedTasks = $assignments->where('assignment_status', 'completed')->count();
                              $totalTasks = $assignments->count();
                              $productivity = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

                              return [
                                  'id' => $user->id,
                                  'name' => $user->name,
                                  'role' => ucfirst($user->role),
                                  'avatar' => strtoupper(substr($user->name, 0, 1)),
                                  'completed_tasks' => $completedTasks,
                                  'productivity' => $productivity
                              ];
                          });

        // Task Completion Trend (Last 7 days)
        $completionTrendLabels = [];
        $completionTrendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $completionTrendLabels[] = $date->format('D');

            // Count tasks completed on this day
            $completedOnDay = Card::where('status', 'done')
                                 ->whereDate('updated_at', $date->format('Y-m-d'))
                                 ->count();
            $completionTrendData[] = $completedOnDay;
        }

        // Task Status Distribution
        $taskStatusData = [
            Card::where('status', 'done')->count(),
            Card::where('status', 'in_progress')->count(),
            Card::where('status', 'todo')->count(),
            Card::where('due_date', '<', Carbon::now())->whereIn('status', ['todo', 'in_progress', 'review'])->count()
        ];

        // Top Performers (Users with most completed tasks)
        $topPerformersRaw = Card_Assigment::select('user_id', DB::raw('COUNT(*) as completed_count'))
            ->whereHas('card', function ($q) {
                $q->where('status', 'done');
            })
            ->groupBy('user_id')
            ->orderBy('completed_count', 'desc')
            ->take(5)
            ->get();

        $topPerformersNames = [];
        $topPerformersData = [];

        foreach ($topPerformersRaw as $performer) {
            $user = User::find($performer->user_id);
            if ($user) {
                $topPerformersNames[] = $user->name;
                $topPerformersData[] = $performer->completed_count;
            }
        }

        // Project Status Overview - Simplified calculation
        $allProjects = Project::with(['boards.cards'])->get();

        $completedProjects = 0;
        $inProgressProjects = 0;
        $pendingProjects = 0;
        $onHoldProjects = 0;

        foreach ($allProjects as $project) {
            $totalCards = 0;
            $completedCards = 0;
            $hasInProgress = false;

            foreach ($project->boards as $board) {
                foreach ($board->cards as $card) {
                    $totalCards++;
                    if ($card->status === 'done') {
                        $completedCards++;
                    } elseif (in_array($card->status, ['in_progress', 'review'])) {
                        $hasInProgress = true;
                    }
                }
            }

            // Classify project based on progress
            if ($totalCards === 0) {
                $pendingProjects++;
            } elseif ($completedCards === $totalCards) {
                $completedProjects++;
            } elseif ($hasInProgress || $completedCards > 0) {
                $inProgressProjects++;
            } else {
                $pendingProjects++;
            }
        }

        $projectStatusData = [
            $completedProjects,
            $inProgressProjects,
            $pendingProjects,
            $onHoldProjects
        ];

        return view('pages.admin.dashboard', compact(
            'totalProjects',
            'totalUsers',
            'completedTasks',
            'activeTasks',
            'overdueTasks',
            'recentProjects',
            'teamMembers',
            'completionTrendLabels',
            'completionTrendData',
            'taskStatusData',
            'topPerformersNames',
            'topPerformersData',
            'projectStatusData'
        ));
    }

    public function reports(Request $request)
    {
        // Get date range from request or default to last month
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $projectId = $request->input('project_id');

        // Ensure dates are Carbon instances
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Build project query
        $projectQuery = Project::with(['user', 'boards.cards']);

        if ($projectId) {
            $projectQuery->where('id', $projectId);
        }

        // Filter projects created within date range
        $projectQuery->whereBetween('created_at', [$startDate, $endDate]);

        $reportProjects = $projectQuery->get()->map(function ($project) {
            // Calculate project progress
            $totalCards = 0;
            $completedCards = 0;

            foreach ($project->boards as $board) {
                $totalCards += $board->cards->count();
                $completedCards += $board->cards->where('status', 'done')->count();
            }

            $progress = $totalCards > 0 ? round(($completedCards / $totalCards) * 100, 1) : 0;

            // Determine status based on progress
            $status = 'planning';
            if ($progress >= 100) {
                $status = 'completed';
            } elseif ($progress > 0) {
                $status = 'in_progress';
            }

            $project->progress = $progress;
            $project->status = $status;

            return $project;
        });

        // Get all projects for filter dropdown
        $projects = Project::select('id', 'project_name')->orderBy('project_name')->get();

        // Calculate metrics
        $totalProjects = $reportProjects->count();
        $completedProjects = $reportProjects->where('status', 'completed')->count();

        // Get tasks (cards) within date range
        $taskQuery = Card::with(['board.project', 'assignedUsers'])
                        ->whereHas('board.project', function ($q) use ($startDate, $endDate, $projectId) {
                            $q->whereBetween('created_at', [$startDate, $endDate]);
                            if ($projectId) {
                                $q->where('id', $projectId);
                            }
                        });

        $reportTasks = $taskQuery->get();
        $totalTasks = $reportTasks->count();
        $completedTasks = $reportTasks->where('status', 'done')->count();

        // Get top performers
        $userTaskCounts = Card_Assigment::select('user_id')
                                      ->with(['user', 'card'])
                                      ->whereHas('card.board.project', function ($q) use ($startDate, $endDate, $projectId) {
                                          $q->whereBetween('created_at', [$startDate, $endDate]);
                                          if ($projectId) {
                                              $q->where('id', $projectId);
                                          }
                                      })
                                      ->get()
                                      ->groupBy('user_id');

        $topPerformers = collect($userTaskCounts)->map(function ($assignments, $userId) {
            $user = $assignments->first()->user;
            $totalTasks = $assignments->count();
            $completedTasks = $assignments->filter(function ($assignment) {
                return $assignment->card && $assignment->card->status === 'done';
            })->count();
            $inProgressTasks = $assignments->filter(function ($assignment) {
                return $assignment->card && $assignment->card->status === 'in_progress';
            })->count();

            $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

            $user->task_count = $totalTasks;
            $user->completed_tasks = $completedTasks;
            $user->in_progress_tasks = $inProgressTasks;
            $user->completion_rate = $completionRate;

            return $user;
        })->sortByDesc('completion_rate')->take(10)->values();

        // Get active users count
        $activeUsers = User::whereHas('assignedCards.board.project', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        // Task breakdown by status for print report
        $tasksByStatus = [
            'todo' => $reportTasks->where('status', 'todo')->count(),
            'in_progress' => $reportTasks->where('status', 'in_progress')->count(),
            'review' => $reportTasks->where('status', 'review')->count(),
            'done' => $reportTasks->where('status', 'done')->count(),
        ];

        // Task breakdown by priority
        $tasksByPriority = [
            'low' => $reportTasks->where('priority', 'low')->count(),
            'medium' => $reportTasks->where('priority', 'medium')->count(),
            'high' => $reportTasks->where('priority', 'high')->count(),
        ];

        // Overdue tasks
        $overdueTasks = $reportTasks->filter(function ($task) {
            return $task->due_date && Carbon::parse($task->due_date)->isPast() && $task->status !== 'done';
        })->count();

        // Average completion time (for completed tasks)
        $completedTasksWithDates = $reportTasks->filter(function ($task) {
            return $task->status === 'done' && $task->created_at && $task->updated_at;
        });

        $avgCompletionDays = 0;
        if ($completedTasksWithDates->count() > 0) {
            $totalDays = $completedTasksWithDates->sum(function ($task) {
                return Carbon::parse($task->created_at)->diffInDays(Carbon::parse($task->updated_at));
            });
            $avgCompletionDays = round($totalDays / $completedTasksWithDates->count(), 1);
        }

        // Project status breakdown
        $projectsByStatus = [
            'planning' => $reportProjects->where('status', 'planning')->count(),
            'in_progress' => $reportProjects->where('status', 'in_progress')->count(),
            'completed' => $reportProjects->where('status', 'completed')->count(),
        ];

        // Date range for display
        $dateRange = [
            'start' => $startDate->format('d M Y'),
            'end' => $endDate->format('d M Y'),
            'start_raw' => $startDate->format('Y-m-d'),
            'end_raw' => $endDate->format('Y-m-d'),
        ];

        // Overall completion rate
        $overallCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        return view('pages.admin.reports', compact(
            'reportProjects',
            'reportTasks',
            'topPerformers',
            'projects',
            'totalProjects',
            'completedProjects',
            'totalTasks',
            'completedTasks',
            'activeUsers',
            'tasksByStatus',
            'tasksByPriority',
            'overdueTasks',
            'avgCompletionDays',
            'projectsByStatus',
            'dateRange',
            'overallCompletionRate',
            'projectId'
        ));
    }
}
