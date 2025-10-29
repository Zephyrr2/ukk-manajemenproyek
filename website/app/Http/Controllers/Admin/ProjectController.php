<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use App\Models\Board;
use App\Models\Card;
use App\Models\ProjectMember;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function tampilProject()
    {
        $projects = Project::with(['user', 'membersWithUsers', 'boards.cards'])->get();
        return view('pages.admin.projects', compact('projects'));
    }

    public function tambahProject()
    {
        return view('pages.admin.create-project');
    }

    function storeProject(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'team_lead_id' => 'nullable|exists:users,id',
        ]);

        // Verify the selected user is actually a leader (if provided)
        if ($request->filled('team_lead_id')) {
            $teamLead = User::where('id', $request->team_lead_id)
                           ->where('role', 'leader')
                           ->first();

            if (!$teamLead) {
                return back()->withErrors(['team_lead_id' => 'Please select a valid team leader.'])
                            ->withInput();
            }

            // Check if leader already has a project
            $existingProject = Project::where('user_id', $request->team_lead_id)->first();
            if ($existingProject) {
                return back()->withErrors(['team_lead_id' => 'Leader sudah memiliki project: ' . $existingProject->project_name])
                            ->withInput();
            }
        }

        $project = Project::create([
            'project_name' => $request->project_name,
            'description' => $request->description,
            'deadline' => $request->due_date,
            'user_id' => $request->filled('team_lead_id') ? $request->team_lead_id : Auth::id(), // Use team leader ID or current admin ID
            'slug' => Str::slug($request->project_name, '-'),
        ]);

        // Project creator (admin/team leader) akan ditampilkan secara terpisah di UI
        // Jika tidak ada team leader yang dipilih, admin yang membuat project akan menjadi default

        return redirect()->route('admin.projects')->with('success', 'Project created successfully.');
    }

    public function edit($slug)
    {
        $project = Project::where('slug', $slug)->with('user')->firstOrFail();
        return view('pages.admin.update-project', compact('project'));
    }

    function updateProject(Request $request, $slug)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'team_lead_id' => 'nullable|exists:users,id',
        ]);

        $project = Project::where('slug', $slug)->firstOrFail();

        // Update project data
        $updateData = [
            'project_name' => $request->project_name,
            'description' => $request->description,
            'deadline' => $request->due_date,
        ];

        // Only update user_id if team_lead_id is provided
        if ($request->filled('team_lead_id')) {
            // Verify the selected user is actually a leader
            $teamLead = User::where('id', $request->team_lead_id)
                           ->where('role', 'leader')
                           ->first();

            if ($teamLead) {
                // Check if leader already has another project (not this one)
                $existingProject = Project::where('user_id', $request->team_lead_id)
                                         ->where('id', '!=', $project->id)
                                         ->first();

                if ($existingProject) {
                    return back()->withErrors(['team_lead_id' => 'Leader sudah memiliki project: ' . $existingProject->project_name])
                                ->withInput();
                }

                $updateData['user_id'] = $request->team_lead_id;
            }
        }
        // If no team lead selected, we don't update user_id (keep existing value)

        $project->update($updateData);

        return redirect()->route('admin.projects')->with('success', 'Project updated successfully.');
    }

    /**
     * Search for team leaders by name
     */
    public function searchLeaders(Request $request)
    {
        $query = $request->get('q');
        $projectId = $request->get('project_id'); // For edit mode

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $leaders = User::where('role', 'leader')
                      ->where('name', 'LIKE', "%{$query}%")
                      ->with('createdProjects:id,project_name,user_id')
                      ->select('id', 'name', 'email')
                      ->limit(10)
                      ->get()
                      ->map(function($leader) use ($projectId) {
                          $hasProject = $leader->createdProjects->isNotEmpty();
                          $currentProject = $leader->createdProjects->first();

                          // Check if this is the current project being edited
                          $isCurrentProject = false;
                          if ($projectId && $currentProject) {
                              $isCurrentProject = $currentProject->id == $projectId;
                          }

                          return [
                              'id' => $leader->id,
                              'name' => $leader->name,
                              'email' => $leader->email,
                              'has_project' => $hasProject,
                              'project_name' => $hasProject ? $currentProject->project_name : null,
                              'is_current_project' => $isCurrentProject,
                          ];
                      });

        return response()->json($leaders);
    }

    /**
     * Show project details by slug
     */
    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->with(['user', 'membersWithUsers', 'boards.cards.user'])
            ->firstOrFail();

        // Calculate progress data
        $totalTasks = $project->cards()->count();
        $completedTasks = $project->cards()->where('status', 'completed')->count();
        $inProgressTasks = $project->cards()->where('status', 'in_progress')->count();
        $todoTasks = $project->cards()->where('status', 'todo')->count();

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Get recent activities (recent card updates)
        $recentActivities = $project->cards()
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('pages.admin.project-detail', compact(
            'project',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'todoTasks',
            'progressPercentage',
            'recentActivities'
        ));
    }

    /**
     * Show project board by slug
     */
    public function board($slug)
    {
        $project = Project::where('slug', $slug)->with('user')->firstOrFail();

        // Get or create board for this project
        $board = Board::firstOrCreate([
            'project_id' => $project->id
        ], [
            'board_name' => $project->project_name . ' Board',
            'description' => 'Kanban board for ' . $project->project_name,
            'position' => 1
        ]);

        // Get cards from database, grouped by status
        $cards = Card::where('board_id', $board->id)->with('user')->orderBy('position')->get();

        $boardData = [
            'todo' => $cards->where('status', 'todo')->values()->all(),
            'in_progress' => $cards->where('status', 'in_progress')->values()->all(),
            'review' => $cards->where('status', 'review')->values()->all(),
            'done' => $cards->where('status', 'done')->values()->all(),
        ];

        return view('pages.admin.board', compact('project', 'boardData'));
    }

    /**
     * Show create task form
     */
    public function createTask($slug)
    {
        $project = Project::where('slug', $slug)
            ->with(['user', 'projectMembers.user'])
            ->firstOrFail();

        return view('pages.admin.add-task', compact('project'));
    }

    public function addMember(Request $request, $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:developer,designer'
        ]);

        // Check if user is already a member
        $existingMember = ProjectMember::where('project_id', $project->id)
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($existingMember) {
            return response()->json([
                'success' => false,
                'message' => 'User is already a member of this project'
            ], 400);
        }

        // Check if user is currently working on a task (status = working)
        $user = User::find($validated['user_id']);
        if ($user && $user->status === 'working') {
            return response()->json([
                'success' => false,
                'message' => "User {$user->name} is currently working on a task. Only users with 'free' status can be added to projects."
            ], 400);
        }

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
            'joined_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member added successfully'
        ]);
    }

    /**
     * Remove member from project
     */
    public function removeMember($slug, $memberId)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        $member = ProjectMember::where('project_id', $project->id)
            ->where('id', $memberId)
            ->firstOrFail();

        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member removed successfully'
        ]);
    }

    /**
     * Search users for adding as members
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        $projectId = $request->get('project_id');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get the project to exclude project manager from search
        $project = Project::find($projectId);

        // Get users that are not already members of this project
        $existingMemberIds = ProjectMember::where('project_id', $projectId)->pluck('user_id');

        // Also exclude the project manager from search results
        if ($project) {
            $existingMemberIds->push($project->user_id);
        }

        $users = User::whereNotIn('id', $existingMemberIds)
                    ->where(function($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('email', 'LIKE', "%{$query}%");
                    })
                    ->where('role', '!=', 'admin') // Exclude admin users
                    ->select('id', 'name', 'email', 'role', 'status')
                    ->limit(10)
                    ->get();

        return response()->json($users);
    }

    /**
     * Store new task for a project
     */
    public function storeTask(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,review,done',
            'assignee' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'estimated_hours' => 'nullable|numeric|min:0|max:999.99',
        ]);

        $project = Project::where('slug', $slug)->firstOrFail();

        // Get or create default board for this project
        $board = Board::firstOrCreate([
            'project_id' => $project->id
        ], [
            'board_name' => $project->project_name . ' Board',
            'description' => 'Kanban board for ' . $project->project_name,
            'position' => 1
        ]);

        // Create task card
        $card = Card::create([
            'board_id' => $board->id,
            'card_title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->assignee,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'priority' => $request->priority,
            'estimated_hours' => $request->estimated_hours
        ]);

        return redirect()->route('admin.projects.board', $project->slug)
                       ->with('success', 'Task "' . $request->title . '" created successfully!');
    }

    public function destroy($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        // Hapus semua board dan card terkait (cascade delete)
        foreach ($project->boards as $board) {
            // Hapus semua card dalam board
            foreach ($board->cards as $card) {
                // Hapus subtask dan time logs
                $card->subtasks()->delete();
                $card->timeLogs()->delete();
                $card->delete();
            }
            $board->delete();
        }

        // Hapus project members
        $project->membersWithUsers()->delete();

        // Hapus project
        $projectName = $project->project_name;
        $project->delete();

        return redirect()->route('admin.projects')
                       ->with('success', "Project '{$projectName}' berhasil dihapus!");
    }
}
