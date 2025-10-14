@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-leader')
@endsection

@section('title', 'Dashboard Team Lead')
@section('page-title', 'DASHBOARD TEAM LEAD')
@section('page-subtitle', 'Halo, {{ $user->name }}!')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Proyek</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProjects }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Tugas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTasks }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">In Progress</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $inProgressTasks }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold text-green-600">{{ $completedTasks }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Project Section -->
    @if($activeProject)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-pink-500 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">PROYEK: {{ $activeProject->project_name }} (Progress: {{ $progressPercentage }}%)</h2>
            </div>
            <a href="{{ route('leader.projects.board', $activeProject->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                Lihat Board
            </a>
        </div>

        <!-- Kanban Board Preview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- To Do Column -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900">To Do</h3>
                    <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm">{{ $boardData['todo']->count() }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($boardData['todo']->take(3) as $task)
                    <div class="bg-white rounded p-2 border border-gray-200 text-sm">
                        <p class="font-medium text-gray-900 truncate">{{ $task->card_title }}</p>
                        <p class="text-xs text-gray-600">{{ $task->user->name ?? 'Unassigned' }}</p>
                    </div>
                    @endforeach
                    @if($boardData['todo']->count() > 3)
                    <p class="text-xs text-gray-500 text-center">+{{ $boardData['todo']->count() - 3 }} lainnya</p>
                    @endif
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900">In Progress</h3>
                    <span class="bg-yellow-200 text-yellow-700 px-2 py-1 rounded text-sm">{{ $boardData['in_progress']->count() }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($boardData['in_progress']->take(3) as $task)
                    <div class="bg-white rounded p-2 border border-yellow-200 text-sm">
                        <p class="font-medium text-gray-900 truncate">{{ $task->card_title }}</p>
                        <p class="text-xs text-gray-600">{{ $task->user->name ?? 'Unassigned' }}</p>
                    </div>
                    @endforeach
                    @if($boardData['in_progress']->count() > 3)
                    <p class="text-xs text-gray-500 text-center">+{{ $boardData['in_progress']->count() - 3 }} lainnya</p>
                    @endif
                </div>
            </div>

            <!-- Review Column -->
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900">Review</h3>
                    <span class="bg-blue-200 text-blue-700 px-2 py-1 rounded text-sm">{{ $boardData['review']->count() }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($boardData['review']->take(3) as $task)
                    <div class="bg-white rounded p-2 border border-blue-200 text-sm">
                        <p class="font-medium text-gray-900 truncate">{{ $task->card_title }}</p>
                        <p class="text-xs text-gray-600">{{ $task->user->name ?? 'Unassigned' }}</p>
                    </div>
                    @endforeach
                    @if($boardData['review']->count() > 3)
                    <p class="text-xs text-gray-500 text-center">+{{ $boardData['review']->count() - 3 }} lainnya</p>
                    @endif
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900">Done</h3>
                    <span class="bg-green-200 text-green-700 px-2 py-1 rounded text-sm">{{ $boardData['done']->count() }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($boardData['done']->take(3) as $task)
                    <div class="bg-white rounded p-2 border border-green-200 text-sm">
                        <p class="font-medium text-gray-900 truncate">{{ $task->card_title }}</p>
                        <p class="text-xs text-gray-600">{{ $task->user->name ?? 'Unassigned' }}</p>
                    </div>
                    @endforeach
                    @if($boardData['done']->count() > 3)
                    <p class="text-xs text-gray-500 text-center">+{{ $boardData['done']->count() - 3 }} lainnya</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
        <p class="text-gray-500">Tidak ada proyek aktif saat ini.</p>
        <a href="{{ route('leader.projects') }}" class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Buat Proyek Baru
        </a>
    </div>
    @endif

    <!-- High Priority Tasks -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">TUGAS PRIORITAS TINGGI</h2>
        </div>

        <div class="space-y-4">
            @forelse($highPriorityTasks as $task)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-300 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <h3 class="font-medium text-gray-900">{{ $task->card_title }}</h3>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full
                                @if($task->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($task->status === 'review') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span>👤 {{ $task->user->name ?? 'Unassigned' }}</span>
                            <span>📁 {{ $task->project_name }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($task->due_date)
                            @php
                                $daysUntilDue = \Carbon\Carbon::parse($task->due_date)->diffInDays(now(), false);
                                $isOverdue = $daysUntilDue > 0;
                                $isToday = \Carbon\Carbon::parse($task->due_date)->isToday();
                                $isTomorrow = \Carbon\Carbon::parse($task->due_date)->isTomorrow();
                            @endphp
                            <p class="text-sm font-medium
                                @if($isOverdue) text-red-600
                                @elseif($isToday) text-orange-600
                                @elseif($isTomorrow) text-yellow-600
                                @else text-gray-900 @endif">
                                @if($isOverdue)
                                    Terlambat {{ abs($daysUntilDue) }} hari
                                @elseif($isToday)
                                    Deadline: Hari Ini
                                @elseif($isTomorrow)
                                    Deadline: Besok
                                @else
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-500">Tanpa deadline</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada tugas prioritas tinggi saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Team Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">ANGGOTA TIM ({{ $teamMembers->count() }})</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($teamMembers->take(6) as $member)
            @php
                $memberTasks = collect();
                foreach($projects as $project) {
                    foreach($project->boards as $board) {
                        $userTasks = $board->cards->where('user_id', $member->id);
                        $memberTasks = $memberTasks->merge($userTasks);
                    }
                }
                $activeTasks = $memberTasks->whereIn('status', ['in_progress', 'review'])->count();
                $completedTasks = $memberTasks->where('status', 'done')->count();
            @endphp
            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition-colors">
                <div class="flex items-center mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-3">
                        <span class="text-white font-medium">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $member->name }}</h3>
                        <p class="text-xs text-gray-500">{{ ucfirst($member->role) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm mt-3 pt-3 border-t border-gray-100">
                    <span class="text-gray-600">Aktif: {{ $activeTasks }}</span>
                    <span class="text-green-600">✓ {{ $completedTasks }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-4 text-gray-500">
                <p>Tidak ada anggota tim.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Updates -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">UPDATE TERBARU</h2>
        </div>

        <div class="space-y-3">
            @forelse($recentActivity as $activity)
            @php
                $borderColor = match($activity->status) {
                    'done' => 'border-green-500',
                    'in_progress' => 'border-yellow-500',
                    'review' => 'border-blue-500',
                    default => 'border-gray-300'
                };
            @endphp
            <div class="border-l-4 {{ $borderColor }} pl-4 py-2 hover:bg-gray-50 rounded-r transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $activity->card_title }}</p>
                        <div class="flex items-center space-x-3 mt-1 text-sm text-gray-600">
                            <span>👤 {{ $activity->user->name ?? 'Unassigned' }}</span>
                            <span>📁 {{ $activity->project_name }}</span>
                            <span class="px-2 py-0.5 rounded text-xs
                                @if($activity->status === 'done') bg-green-100 text-green-700
                                @elseif($activity->status === 'in_progress') bg-yellow-100 text-yellow-700
                                @elseif($activity->status === 'review') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 whitespace-nowrap ml-2">
                        {{ $activity->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-gray-500">
                <p>Belum ada aktivitas terbaru.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
