@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-leader')
@endsection

@section('title', 'Project Detail - ' . $project->project_name)
@section('page-title', 'PROJECT DETAIL')
@section('page-subtitle', $project->project_name)

@section('content')
<div class="space-y-6">
    <!-- Back Button and Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('leader.projects') }}"
                class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Projects</span>
            </a>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('leader.projects.board', $project->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                View Board
            </a>
            <a href="{{ route('leader.projects.create-task', $project->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create Task
            </a>
        </div>
    </div>

    <!-- Project Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $project->project_name }}</h1>
                            <p class="text-gray-500">Created on {{ $project->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700 leading-relaxed">
                                {{ $project->description ?? 'No description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Progress Overview -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Overview</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $totalCards }}</div>
                            <div class="text-sm text-gray-600">Total Tasks</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $completedCards }}</div>
                            <div class="text-sm text-gray-600">Completed</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ $inProgressCards }}</div>
                            <div class="text-sm text-gray-600">In Progress</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-600">{{ $todoCards }}</div>
                            <div class="text-sm text-gray-600">Todo</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Overall Progress</span>
                            <span>{{ $progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-600 h-3 rounded-full transition-all duration-300"
                                style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Overview -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tasks Overview</h3>
                        <a href="{{ route('leader.projects.board', $project->id) }}"
                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                            View All Tasks →
                        </a>
                    </div>

                    @if($project->boards->count() > 0)
                        <div class="space-y-4">
                            @foreach($project->boards as $board)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3">{{ $board->board_name }}</h4>

                                    @if($board->cards->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @foreach(['todo', 'in_progress', 'done'] as $status)
                                                @php
                                                    $statusCards = $board->cards->where('status', $status);
                                                    $statusLabel = ucfirst(str_replace('_', ' ', $status));
                                                    $statusColor = match($status) {
                                                        'todo' => 'gray',
                                                        'in_progress' => 'yellow',
                                                        'done' => 'green',
                                                        default => 'gray'
                                                    };
                                                @endphp
                                                <div class="bg-{{ $statusColor }}-50 rounded-lg p-3">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h5 class="text-sm font-medium text-{{ $statusColor }}-800">{{ $statusLabel }}</h5>
                                                        <span class="bg-{{ $statusColor }}-200 text-{{ $statusColor }}-700 px-2 py-1 rounded text-xs">{{ $statusCards->count() }}</span>
                                                    </div>

                                                    @if($statusCards->count() > 0)
                                                        <div class="space-y-2">
                                                            @foreach($statusCards->take(3) as $card)
                                                                <div class="bg-white rounded p-2 text-xs">
                                                                    <p class="font-medium text-gray-900 truncate">{{ $card->card_title }}</p>
                                                                    @if($card->user)
                                                                        <p class="text-gray-500 mt-1">{{ $card->user->name }}</p>
                                                                    @endif
                                                                </div>
                                                            @endforeach

                                                            @if($statusCards->count() > 3)
                                                                <p class="text-xs text-{{ $statusColor }}-600">+{{ $statusCards->count() - 3 }} more</p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-sm">No tasks in this board yet.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">No boards created yet</p>
                            <p class="text-gray-400 text-xs mt-1">Create boards and tasks to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Details</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Team Leader</label>
                            <div class="mt-1 flex items-center space-x-2">
                                @if($project->user)
                                    <img class="w-8 h-8 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($project->user->name) }}&background=random"
                                        alt="Team Leader">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $project->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $project->user->email }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">No team leader assigned</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Team Members</label>
                            <div class="mt-2">
                                @if($project->projectMembers->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($project->projectMembers as $member)
                                            <div class="flex items-center space-x-2">
                                                <img class="w-6 h-6 rounded-full"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=random"
                                                    alt="{{ $member->user->name }}">
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-900">{{ $member->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $member->user->email }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-xs text-gray-500">
                                            {{ $project->projectMembers->count() }} team member{{ $project->projectMembers->count() !== 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                @else
                                    <p class="text-gray-400 text-sm">No team members yet</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->created_at->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $project->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
