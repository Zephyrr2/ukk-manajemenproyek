<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\SubtaskController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\TaskActionController;

// Test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});

// Public routes (tidak memerlukan authentication)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (memerlukan authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);

    // Tasks (Cards)
    Route::get('/tasks', [CardController::class, 'index']);
    Route::get('/tasks/{id}', [CardController::class, 'show']);
    Route::patch('/tasks/{id}/status', [CardController::class, 'updateStatus']);

    // Card routes (for compatibility)
    Route::get('/cards', [CardController::class, 'index']);
    Route::get('/cards/{id}', [CardController::class, 'show']);
    Route::post('/cards', [CardController::class, 'store']);
    Route::put('/cards/{id}', [CardController::class, 'update']);
    Route::patch('/cards/{id}/status', [CardController::class, 'updateStatus']);
    Route::delete('/cards/{id}', [CardController::class, 'destroy']);

    // Task Actions
    Route::post('/tasks/{id}/start', [TaskActionController::class, 'startTask']);
    Route::post('/tasks/{id}/submit', [TaskActionController::class, 'submitTask']);

    // Task Subtasks
    Route::get('/tasks/{taskId}/subtasks', [SubtaskController::class, 'getTaskSubtasks']);
    Route::post('/tasks/{taskId}/subtasks', [SubtaskController::class, 'createTaskSubtask']);
    Route::patch('/tasks/{taskId}/subtasks/{subtaskId}/toggle', [SubtaskController::class, 'toggleSubtaskStatus']);

    // Task Comments
    Route::get('/tasks/{taskId}/comments', [CommentController::class, 'getTaskComments']);
    Route::post('/tasks/{taskId}/comments', [CommentController::class, 'addTaskComment']);

    // Subtask routes (legacy)
    Route::get('/subtasks', [SubtaskController::class, 'index']);
    Route::get('/subtasks/{id}', [SubtaskController::class, 'show']);
    Route::post('/subtasks', [SubtaskController::class, 'store']);
    Route::put('/subtasks/{id}', [SubtaskController::class, 'update']);
    Route::patch('/subtasks/{id}/status', [SubtaskController::class, 'updateStatus']);
    Route::delete('/subtasks/{id}', [SubtaskController::class, 'destroy']);
});
