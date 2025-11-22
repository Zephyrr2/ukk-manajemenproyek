<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Leader\TaskController;
use App\Http\Controllers\Leader\ProjectController as LeaderProjectController;
use App\Http\Controllers\Leader\CommentController as LeaderCommentController;
use App\Http\Controllers\Leader\TimeTrackingController as LeaderTimeTrackingController;
use App\Http\Controllers\Leader\DashboardController as LeaderDashboardController;
use App\Http\Controllers\User\SubtaskController;
use App\Http\Controllers\User\TaskController as UserTaskController;
use App\Http\Controllers\User\CommentController as UserCommentController;
use App\Http\Controllers\User\TimeTrackingController as UserTimeTrackingController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\AuthController;

// Public Routes
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login/process', [AuthController::class, 'loginProcess']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register/process', [AuthController::class, 'registerProcess']);
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password/process', [AuthController::class, 'forgotPasswordProcess'])->name('password.email');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

    // Profile Routes (accessible by all authenticated users)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Notification Routes (accessible by all authenticated users)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('recent');
        Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/clear-read', [\App\Http\Controllers\NotificationController::class, 'clearRead'])->name('clear-read');
    });

    // Admin Management Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');

        // Projects Management
        Route::get('/projects', [ProjectController::class, 'tampilProject'])->name('projects');
        Route::get('/projects/create', [ProjectController::class, 'tambahProject'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'storeProject'])->name('projects.store');
        Route::get('/search-leaders', [ProjectController::class, 'searchLeaders'])->name('search.leaders');
        Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{slug}/board', [ProjectController::class, 'board'])->name('projects.board');
        Route::get('/projects/{slug}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{slug}', [ProjectController::class, 'updateProject'])->name('projects.update');
        Route::delete('/projects/{slug}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        // Project Review Management
        Route::post('/projects/{slug}/approve', [ProjectController::class, 'approveProject'])->name('projects.approve');
        Route::post('/projects/{slug}/reject', [ProjectController::class, 'rejectProject'])->name('projects.reject');
        Route::post('/projects/{slug}/reset-status', [ProjectController::class, 'resetProjectStatus'])->name('projects.reset-status');

        // Project Members Management
        Route::post('/projects/{slug}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
        Route::delete('/projects/{slug}/members/{member}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');
        Route::get('/search-users', [ProjectController::class, 'searchUsers'])->name('search.users');

        // Task Management
        Route::get('/projects/{slug}/tasks/create', [ProjectController::class, 'createTask'])->name('projects.tasks.create');
        Route::post('/projects/{slug}/tasks', [ProjectController::class, 'storeTask'])->name('projects.tasks.store');

        // Team Management
        Route::get('/users', [UserController::class, 'tampilUser'])->name('users');
        Route::get('/users/create', [UserController::class, 'tambahUser'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Reports & Analytics
        Route::get('/reports', [MainController::class, 'reports'])->name('reports');
    });

    // Team Leader Management Routes
    Route::prefix('leader')->name('leader.')->middleware('role:leader,admin')->group(function () {
        Route::get('/dashboard', [LeaderDashboardController::class, 'index'])->name('dashboard');

        // Projects Management
        Route::get('/projects', [LeaderProjectController::class, 'index'])->name('projects');
        Route::get('/projects/{id}', [LeaderProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{id}/board', [LeaderProjectController::class, 'board'])->name('projects.board');
        Route::get('/projects/{id}/create-task', [LeaderProjectController::class, 'createTask'])->name('projects.create-task');
        Route::post('/projects/update-task-status', [LeaderProjectController::class, 'updateTaskStatus'])->name('projects.update-task-status');
        Route::post('/projects/store-task', [LeaderProjectController::class, 'storeTask'])->name('projects.store-task');
        Route::post('/tasks/{id}/submit', [LeaderProjectController::class, 'submitTask'])->name('tasks.submit');
        Route::get('/projects/{id}/members', [LeaderProjectController::class, 'getProjectMembers'])->name('projects.members');

        // Project Submission (Leader submits project to admin)
        Route::post('/projects/{id}/submit-project', [LeaderProjectController::class, 'submitProject'])->name('projects.submit');

        // Task Assignment - using dedicated TaskController
        Route::get('/task-assignment', [TaskController::class, 'index'])->name('task-assignment');
        Route::post('/tasks/{id}/approve', [TaskController::class, 'approve'])->name('tasks.approve');
        Route::post('/tasks/{id}/reject', [TaskController::class, 'reject'])->name('tasks.reject');

        // Comments
        Route::post('/tasks/{taskId}/comments', [LeaderCommentController::class, 'storeTaskComment'])->name('tasks.comments.store');
        Route::post('/tasks/{taskId}/subtasks/{subtaskId}/comments', [LeaderCommentController::class, 'storeSubtaskComment'])->name('subtasks.comments.store');
        Route::delete('/comments/{commentId}', [LeaderCommentController::class, 'destroy'])->name('comments.destroy');

        // Subtasks (for leaders to view and comment)
        Route::get('/tasks/{id}/subtasks', [SubtaskController::class, 'index'])->name('subtasks');

        // Time Tracking
        Route::get('/time-tracking', [LeaderTimeTrackingController::class, 'index'])->name('time-tracking');
        Route::get('/time-tracking/data', [LeaderTimeTrackingController::class, 'getTimeData'])->name('time-tracking.data');

        // Automatic Time Tracking (Leaders can also track time)
        Route::post('/time-tracking/start', [LeaderTimeTrackingController::class, 'startWork'])->name('time-tracking.start');
        Route::post('/time-tracking/stop', [LeaderTimeTrackingController::class, 'stopWork'])->name('time-tracking.stop');
        Route::post('/time-tracking/pause', [LeaderTimeTrackingController::class, 'pauseWork'])->name('time-tracking.pause');
        Route::post('/time-tracking/resume', [LeaderTimeTrackingController::class, 'resumeWork'])->name('time-tracking.resume');
        Route::get('/time-tracking/active', [LeaderTimeTrackingController::class, 'getActiveSession'])->name('time-tracking.active');

        // Progress Tracking
        Route::get('/progress', function () {
            return view('pages.leader.progress');
        })->name('progress');
    });

    // User Dashboard Routes
    Route::prefix('user')->name('user.')->middleware('role:user,leader,admin')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // My Tasks
        Route::get('/tasks', [UserTaskController::class, 'index'])->name('tasks');
        Route::post('/tasks/{id}/start', [UserTaskController::class, 'startTask'])->name('tasks.start');
        Route::post('/tasks/{id}/submit', [UserTaskController::class, 'submitTask'])->name('tasks.submit');
        Route::get('/tasks/{id}/history', [UserTaskController::class, 'getTaskHistory'])->name('tasks.history');

        // Subtasks
        Route::get('/tasks/{id}/subtasks', [SubtaskController::class, 'index'])->name('subtasks');
        Route::get('/tasks/{id}/subtasks/create', [SubtaskController::class, 'create'])->name('subtasks.create');
        Route::post('/tasks/{id}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
        Route::get('/tasks/{taskId}/subtasks/{subtaskId}/edit', [SubtaskController::class, 'edit'])->name('subtasks.edit');
        Route::put('/tasks/{taskId}/subtasks/{subtaskId}', [SubtaskController::class, 'update'])->name('subtasks.update');
        Route::patch('/tasks/{taskId}/subtasks/{subtaskId}/toggle', [SubtaskController::class, 'toggleStatus'])->name('subtasks.toggle');
        Route::delete('/tasks/{taskId}/subtasks/{subtaskId}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');

        // Subtask Time Tracking
        Route::post('/tasks/{taskId}/subtasks/{subtaskId}/start', [SubtaskController::class, 'start'])->name('subtasks.start');
        Route::post('/tasks/{taskId}/subtasks/{subtaskId}/pause', [SubtaskController::class, 'pause'])->name('subtasks.pause');
        Route::post('/tasks/{taskId}/subtasks/{subtaskId}/resume', [SubtaskController::class, 'resume'])->name('subtasks.resume');
        Route::post('/tasks/{taskId}/subtasks/{subtaskId}/complete', [SubtaskController::class, 'complete'])->name('subtasks.complete');

        // Comments
        Route::post('/tasks/{taskId}/comments', [UserCommentController::class, 'storeTaskComment'])->name('tasks.comments.store');
        Route::post('/tasks/{taskId}/subtasks/{subtaskId}/comments', [UserCommentController::class, 'storeSubtaskComment'])->name('subtasks.comments.store');
        Route::delete('/comments/{commentId}', [UserCommentController::class, 'destroy'])->name('comments.destroy');

        // Time Tracking
        Route::get('/time-tracking', [UserTimeTrackingController::class, 'index'])->name('time-tracking');
        Route::get('/time-tracking/data', [UserTimeTrackingController::class, 'getTimeData'])->name('time-tracking.data');

        // Automatic Time Tracking
        Route::post('/time-tracking/start', [UserTimeTrackingController::class, 'startWork'])->name('time-tracking.start');
        Route::post('/time-tracking/stop', [UserTimeTrackingController::class, 'stopWork'])->name('time-tracking.stop');
        Route::post('/time-tracking/pause', [UserTimeTrackingController::class, 'pauseWork'])->name('time-tracking.pause');
        Route::post('/time-tracking/resume', [UserTimeTrackingController::class, 'resumeWork'])->name('time-tracking.resume');
        Route::get('/time-tracking/active', [UserTimeTrackingController::class, 'getActiveSession'])->name('time-tracking.active');

        // Deadline Extension
        Route::post('/tasks/{id}/request-extension', [\App\Http\Controllers\User\DeadlineExtensionController::class, 'requestExtension'])->name('tasks.request-extension');
    });

    // Leader Extension Approval Routes
    Route::prefix('leader')->name('leader.')->middleware('role:leader,admin')->group(function () {
        Route::post('/tasks/{id}/approve-extension', [\App\Http\Controllers\User\DeadlineExtensionController::class, 'approveExtension'])->name('tasks.approve-extension');
        Route::post('/tasks/{id}/reject-extension', [\App\Http\Controllers\User\DeadlineExtensionController::class, 'rejectExtension'])->name('tasks.reject-extension');
    });
});
