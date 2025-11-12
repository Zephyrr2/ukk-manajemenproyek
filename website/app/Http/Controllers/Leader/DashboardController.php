<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Card;
use App\Models\User;
use App\Models\Time_Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get projects where user is leader (creator or member)
        $projects = Project::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                      $subQuery->where('user_id', $user->id);
                  });
        })
        ->with(['boards.cards.user', 'user', 'projectMembers.user'])
        ->get();

        // Calculate overall statistics
        $totalProjects = $projects->count();
        $totalTasks = 0;
        $completedTasks = 0;
        $inProgressTasks = 0;
        $reviewTasks = 0;

        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $boardTasks = $board->cards;
                $totalTasks += $boardTasks->count();
                $completedTasks += $boardTasks->where('status', 'done')->count();
                $inProgressTasks += $boardTasks->where('status', 'in_progress')->count();
                $reviewTasks += $boardTasks->where('status', 'review')->count();
            }
        }

        // Get active project (most recent with incomplete tasks)
        $activeProject = $projects->filter(function ($project) {
            $incompleteTasks = 0;
            foreach ($project->boards as $board) {
                $incompleteTasks += $board->cards->whereNotIn('status', ['done'])->count();
            }
            return $incompleteTasks > 0;
        })->first();

        // If no active project with incomplete tasks, get the most recent project
        if (!$activeProject) {
            $activeProject = $projects->sortByDesc('created_at')->first();
        }

        // Get board data for active project
        $boardData = [
            'todo' => collect(),
            'in_progress' => collect(),
            'review' => collect(),
            'done' => collect(),
        ];

        $progressPercentage = 0;

        if ($activeProject) {
            $allCards = collect();
            foreach ($activeProject->boards as $board) {
                $allCards = $allCards->merge($board->cards);
            }

            $boardData = [
                'todo' => $allCards->where('status', 'todo'),
                'in_progress' => $allCards->where('status', 'in_progress'),
                'review' => $allCards->where('status', 'review'),
                'done' => $allCards->where('status', 'done'),
            ];

            $totalCards = $allCards->count();
            $completedCards = $allCards->where('status', 'done')->count();
            $progressPercentage = $totalCards > 0 ? round(($completedCards / $totalCards) * 100) : 0;
        }

        // Get high priority tasks (across all projects)
        $highPriorityTasks = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $priorityCards = $board->cards
                    ->where('priority', 'high')
                    ->whereNotIn('status', ['done'])
                    ->map(function ($card) use ($project) {
                        $card->project_name = $project->project_name;
                        return $card;
                    });
                $highPriorityTasks = $highPriorityTasks->merge($priorityCards);
            }
        }

        // Sort by due date
        $highPriorityTasks = $highPriorityTasks->sortBy('due_date')->take(5);

        // Get team members from all projects
        $teamMembers = collect();
        foreach ($projects as $project) {
            $teamMembers->push($project->user);
            foreach ($project->projectMembers as $member) {
                $teamMembers->push($member->user);
            }
        }
        $teamMembers = $teamMembers->unique('id');

        // Get recent activity (last 10 tasks updated)
        $recentActivity = collect();
        foreach ($projects as $project) {
            foreach ($project->boards as $board) {
                $recentCards = $board->cards->map(function ($card) use ($project) {
                    $card->project_name = $project->project_name;
                    return $card;
                });
                $recentActivity = $recentActivity->merge($recentCards);
            }
        }
        $recentActivity = $recentActivity->sortByDesc('updated_at')->take(10);

        $pageSubtitle = 'Hello, ' . $user->name . '!';

        return view('pages.leader.dashboard', compact(
            'user',
            'projects',
            'activeProject',
            'boardData',
            'progressPercentage',
            'totalProjects',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'reviewTasks',
            'highPriorityTasks',
            'teamMembers',
            'recentActivity',
            'pageSubtitle'
        ));
    }
}
