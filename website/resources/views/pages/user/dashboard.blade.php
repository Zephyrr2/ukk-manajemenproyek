@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-user')
@endsection

@section('title', 'Dashboard Developer')
@section('page-title', 'DASHBOARD DEVELOPER')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Total Tugas</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalTasks }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Selesai</p>
                    <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $completedTasks }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Hari Ini</p>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ number_format($todayWorkTime, 1) }}h</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Progress</p>
                    <p class="text-xl sm:text-2xl font-bold text-orange-600">{{ $overallProgress }}%</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Task Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center mb-4 sm:mb-6">
            <div class="w-8 h-8 bg-pink-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">TUGAS SAAT INI</h2>
        </div>

        @if($currentTask)
        <!-- Current Task Card -->
        <div class="border border-gray-200 rounded-lg p-4 sm:p-6">
            <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 mb-4">
                <div class="flex items-center flex-1">
                    <div class="w-3 h-3
                        @if($currentTask->priority === 'high') bg-red-500
                        @elseif($currentTask->priority === 'medium') bg-yellow-500
                        @else bg-green-500 @endif rounded-full mr-3 flex-shrink-0"></div>
                    <h3 class="font-medium text-gray-900 text-base sm:text-lg break-words">{{ $currentTask->card_title }}</h3>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium w-fit
                    @if($currentTask->status === 'in_progress') bg-yellow-100 text-yellow-800
                    @elseif($currentTask->status === 'review') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $currentTask->status)) }}
                </span>
            </div>

            @if($currentTask->description)
            <p class="text-gray-600 mb-4">{{ $currentTask->description }}</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <span class="text-sm text-gray-600">Priority:</span>
                    <div class="font-medium
                        @if($currentTask->priority === 'high') text-red-600
                        @elseif($currentTask->priority === 'medium') text-yellow-600
                        @else text-green-600 @endif">
                        {{ ucfirst($currentTask->priority) }}
                    </div>
                </div>
                @if($currentTask->estimated_hours)
                <div>
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <div class="font-medium text-gray-900">{{ $currentTask->estimated_hours }} jam</div>
                </div>
                @endif
                <div>
                    <span class="text-sm text-gray-600">Telah bekerja:</span>
                    <div class="font-medium text-gray-900">{{ number_format($currentTaskTimeSpent, 1) }} jam</div>
                </div>
                @if($currentTask->due_date)
                <div>
                    <span class="text-sm text-gray-600">Deadline:</span>
                    @php
                        $isOverdue = \Carbon\Carbon::parse($currentTask->due_date)->isPast();
                        $isToday = \Carbon\Carbon::parse($currentTask->due_date)->isToday();
                    @endphp
                    <div class="font-medium
                        @if($isOverdue) text-red-600
                        @elseif($isToday) text-orange-600
                        @else text-gray-900 @endif">
                        {{ \Carbon\Carbon::parse($currentTask->due_date)->format('d M Y') }}
                        @if($isOverdue) (Terlambat) @elseif($isToday) (Hari ini) @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Progress ({{ $currentTask->subtasks->where('status', 'done')->count() }}/{{ $currentTask->subtasks->count() }} subtasks):</span>
                    <span class="text-sm font-medium text-gray-900">{{ $currentTaskProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full transition-all" style="width: {{ $currentTaskProgress }}%"></div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3">
                @if($user->status === 'paused')
                <!-- Resume Work Form (when paused) -->
                <form action="{{ route('user.time-tracking.resume') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-200" onclick="return confirm('Resume work? Timer will be reactivated.')">
                        ▶️ Resume Work
                    </button>
                </form>
                @elseif($activeSession && $activeSession->card_id == $currentTask->id)
                <!-- Pause Work Form -->
                <form action="{{ route('user.time-tracking.pause') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors duration-200" onclick="return confirm('Pause work? Your time will be saved.')">
                        ⏸️ Pause
                    </button>
                </form>
                @else
                <!-- Start Work Form -->
                <form action="{{ route('user.tasks.start', $currentTask->id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="from" value="dashboard">
                    <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-200" onclick="return confirm('Start working on this task?\n\n• Task status: In Progress\n• Timer will start\n• Status: Working')">
                        🕐 Start Work
                    </button>
                </form>
                @endif

                <a href="{{ route('user.tasks') }}" class="w-full sm:w-auto text-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-200">
                    📋 View Details
                </a>
            </div>
        </div>
        @else
        <div class="border border-gray-200 rounded-lg p-6 text-center">
            <p class="text-gray-500 mb-4">No active tasks at the moment.</p>
            @if($todoTasks->count() > 0)
            <p class="text-sm text-gray-600">Anda memiliki {{ $todoTasks->count() }} tugas menunggu.</p>
            @endif
        </div>
        @endif
    </div>

    <!-- My Task List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">MY TASK LIST</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Showing {{ $recentTasks->count() }} of {{ $totalTasks }} tasks</p>
                </div>
            </div>
            <a href="{{ route('user.tasks') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                📋 View All ({{ $totalTasks }})
            </a>
        </div>

        <!-- Task List Table - Desktop -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Task</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Priority</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Project</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTasks as $task)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3
                                    @if($task->status === 'in_progress') bg-yellow-500
                                    @elseif($task->status === 'review') bg-blue-500
                                    @elseif($task->status === 'done') bg-green-500
                                    @else bg-gray-400 @endif rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-900">{{ $task->card_title }}</div>
                            @if($task->subtasks->count() > 0)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $task->subtasks->where('status', 'done')->count() }}/{{ $task->subtasks->count() }} subtasks
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($task->priority === 'high') bg-red-100 text-red-700
                                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700 @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $task->board->project->project_name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            @if($task->due_date)
                                @php
                                    $daysUntil = \Carbon\Carbon::parse($task->due_date)->diffInDays(now(), false);
                                    $isOverdue = $daysUntil > 0;
                                    $isToday = \Carbon\Carbon::parse($task->due_date)->isToday();
                                @endphp
                                <span class="text-sm
                                    @if($isOverdue) text-red-600 font-medium
                                    @elseif($isToday) text-orange-600 font-medium
                                    @else text-gray-600 @endif">
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            No tasks at the moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Task List Cards - Mobile -->
        <div class="md:hidden space-y-3">
            @forelse($recentTasks as $task)
            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-sm text-gray-900 mb-1 break-words">{{ $task->card_title }}</div>
                        <div class="text-xs text-gray-500 break-words">{{ $task->board->project->project_name ?? 'N/A' }}</div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full flex-shrink-0
                        @if($task->priority === 'high') bg-red-100 text-red-700
                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3
                            @if($task->status === 'in_progress') bg-yellow-500
                            @elseif($task->status === 'review') bg-blue-500
                            @elseif($task->status === 'done') bg-green-500
                            @else bg-gray-400 @endif rounded-full mr-2 flex-shrink-0"></div>
                        <span class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    </div>
                    @if($task->due_date)
                        @php
                            $daysUntil = \Carbon\Carbon::parse($task->due_date)->diffInDays(now(), false);
                            $isOverdue = $daysUntil > 0;
                            $isToday = \Carbon\Carbon::parse($task->due_date)->isToday();
                        @endphp
                        <span class="text-xs
                            @if($isOverdue) text-red-600 font-medium
                            @elseif($isToday) text-orange-600 font-medium
                            @else text-gray-600 @endif">
                            {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                        </span>
                    @endif
                </div>
                @if($task->subtasks->count() > 0)
                <div class="text-xs text-gray-500 mt-2">
                    {{ $task->subtasks->where('status', 'done')->count() }}/{{ $task->subtasks->count() }} subtasks
                </div>
                @endif
            </div>
            @empty
            <div class="py-8 text-center text-gray-500">
                No tasks at the moment.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Time Tracking Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <!-- Today's Work -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center mb-4 sm:mb-6">
                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">TODAY</h2>
            </div>

            <div class="space-y-3 sm:space-y-4">
                <div class="flex items-center justify-between py-2 sm:py-3 border-b border-gray-100">
                    <div class="text-sm sm:text-base text-gray-600">Work Time</div>
                    <div class="font-semibold text-sm sm:text-base text-gray-900">{{ number_format($todayWorkTime, 1) }} hours</div>
                </div>
                <div class="flex items-center justify-between py-2 sm:py-3 border-b border-gray-100">
                    <div class="text-sm sm:text-base text-gray-600">Active Tasks</div>
                    <div class="font-semibold text-sm sm:text-base text-gray-900">{{ $inProgressTasks->count() }}</div>
                </div>
                <div class="flex items-center justify-between py-2 sm:py-3">
                    <div class="text-sm sm:text-base text-gray-600">Tasks Completed Today</div>
                    <div class="font-semibold text-sm sm:text-base text-green-600">{{ $doneTasks->where('updated_at', '>=', \Carbon\Carbon::today())->count() }}</div>
                </div>

                @if($activeSession)
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-900">Active Timer</p>
                            <p class="text-xs text-green-700 mt-1">{{ $activeSession->card->card_title ?? 'Unknown Task' }}</p>
                        </div>
                        <div class="text-lg font-bold text-green-600" id="activeTimer">
                            {{ floor($activeSession->start_time->diffInMinutes(now()) / 60) }}:{{ str_pad($activeSession->start_time->diffInMinutes(now()) % 60, 2, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Weekly Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center mb-4 sm:mb-6">
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">THIS WEEK</h2>
            </div>

            <div class="space-y-3 sm:space-y-4">
                <div class="flex items-center justify-between py-2 sm:py-3 border-b border-gray-100">
                    <div class="text-sm sm:text-base text-gray-600">Total Hours</div>
                    <div class="font-semibold text-sm sm:text-base text-gray-900">{{ number_format($weekWorkTime, 1) }} hours</div>
                </div>
                <div class="flex items-center justify-between py-2 sm:py-3 border-b border-gray-100">
                    <div class="text-sm sm:text-base text-gray-600">Average/day</div>
                    <div class="font-semibold text-sm sm:text-base text-gray-900">{{ number_format($weekWorkTime / max(1, now()->dayOfWeek), 1) }} hours</div>
                </div>
                <div class="flex items-center justify-between py-2 sm:py-3 border-b border-gray-100">
                    <div class="text-sm sm:text-base text-gray-600">Completed Tasks</div>
                    <div class="font-semibold text-sm sm:text-base text-green-600">{{ $doneTasks->where('updated_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</div>
                </div>
                <div class="flex items-center justify-between py-2 sm:py-3">
                    <div class="text-sm sm:text-base text-gray-600">Overall Progress</div>
                    <div class="font-semibold text-sm sm:text-base text-blue-600">{{ $overallProgress }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-update active timer
@if($activeSession)
function updateTimer() {
    const startTime = new Date('{{ $activeSession->start_time->toIso8601String() }}');
    const now = new Date();
    const diffMinutes = Math.floor((now - startTime) / 1000 / 60);
    const hours = Math.floor(diffMinutes / 60);
    const minutes = diffMinutes % 60;

    const timerEl = document.getElementById('activeTimer');
    if (timerEl) {
        timerEl.textContent = hours + ':' + String(minutes).padStart(2, '0');
    }
}

// Update every minute
updateTimer();
setInterval(updateTimer, 60000);
@endif
// JavaScript removed - using HTML forms instead
</script>
@endpush
