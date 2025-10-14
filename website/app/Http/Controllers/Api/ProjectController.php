<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Get user's projects
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Get projects where user is member or creator
            $projects = Project::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                          $subQuery->where('user_id', $user->id);
                      });
            })
            ->with(['user', 'projectMembers.user', 'boards.cards'])
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $projects
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project details
     */
    public function show(string $id)
    {
        try {
            $user = Auth::user();

            $project = Project::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('projectMembers', function ($subQuery) use ($user) {
                          $subQuery->where('user_id', $user->id);
                      });
            })
            ->where('id', $id)
            ->with(['user', 'projectMembers.user', 'boards.cards.user', 'boards.cards.assignments.user'])
            ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found or access denied'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $project
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load project details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
