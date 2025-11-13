@extends('layouts.dashboard')

@section('sidebar')
    @include('partials.sidebar-leader')
@endsection

@section('title', 'Task Assignment & Review')
@section('page-title', 'TASK ASSIGNMENT & REVIEW')
@section('page-subtitle', 'Kelola dan review task yang disubmit oleh tim')

@section('content')
    <!-- Task Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total Tasks</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $taskStats['total'] }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">In Progress</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $taskStats['in_progress'] }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Needs Review</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $taskStats['review'] }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Completed</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $taskStats['done'] }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Tasks for Review -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Tasks for Review</h3>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $tasksForReview->count() }} tasks
                        </span>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if ($tasksForReview->count() > 0)
                        <div class="space-y-4">
                            @foreach ($tasksForReview as $task)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-start sm:justify-between sm:space-y-0">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 break-words">{{ $task->card_title }}
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1 break-words">{{ Str::limit($task->description, 80) }}
                                            </p>
                                            <div class="mt-2 flex flex-wrap items-center gap-2 sm:gap-4">
                                                <span
                                                    class="text-xs text-gray-500 break-all">{{ $task->board->project->project_name ?? 'N/A' }}</span>
                                                @if ($task->user)
                                                    <div class="flex items-center">
                                                        <div
                                                            class="h-6 w-6 flex-shrink-0 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs">
                                                            {{ substr($task->user->name, 0, 1) }}
                                                        </div>
                                                        <span
                                                            class="ml-2 text-xs text-gray-500">{{ $task->user->name }}</span>
                                                    </div>
                                                @endif
                                                @if ($task->due_date)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ now()->gt($task->due_date) ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-row gap-2 sm:ml-4 sm:flex-shrink-0">
                                            <!-- Approve Form -->
                                            <form action="{{ route('leader.tasks.approve', $task->id) }}" method="POST"
                                                class="flex-1 sm:flex-initial">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                    onclick="return confirm('Apakah Anda yakin ingin approve task ini?')">
                                                    ✓ Approve
                                                </button>
                                            </form>

                                            <!-- Reject Form -->
                                            <form action="{{ route('leader.tasks.reject', $task->id) }}" method="POST"
                                                class="flex-1 sm:flex-initial">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    onclick="return confirm('Apakah Anda yakin ingin reject task ini?')">
                                                    ✗ Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v4M6 13h2m8 0V9a2 2 0 00-2-2H8a2 2 0 00-2 2v4m8 0h2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada task untuk direview</h3>
                            <p class="mt-1 text-sm text-gray-500">Semua task sudah diproses atau belum ada yang disubmit.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- My Tasks & History -->
        <div class="space-y-8">
            <!-- My Tasks -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">My Tasks</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if ($myTasks->count() > 0)
                        <div class="space-y-3">
                            @foreach ($myTasks->take(5) as $task)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            @if ($task->status === 'todo')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">To
                                                    Do</span>
                                            @elseif($task->status === 'in_progress')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">In
                                                    Progress</span>
                                            @elseif($task->status === 'review')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Review</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Done</span>
                                            @endif
                                        </div>
                                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $task->card_title }}
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $task->board->project->project_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($myTasks->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('leader.tasks') }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    View All Tasks ({{ $myTasks->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada task</h3>
                            <p class="mt-1 text-sm text-gray-500">Tidak ada task yang assigned saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Assignment History -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Assignment History</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if ($assignmentHistory->count() > 0)
                        <div class="space-y-3">
                            @foreach ($assignmentHistory as $assignment)
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="h-8 w-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-sm">
                                        {{ substr($assignment->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $assignment->card->card_title }}</p>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">{{ $assignment->user->name }}</span>
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $assignment->assignment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($assignment->assignment_status) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $assignment->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada history</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada history assignment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50"
            role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
@endsection
