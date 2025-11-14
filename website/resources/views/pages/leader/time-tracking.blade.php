@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-leader')
@endsection

@section('title', 'Team Time Tracking')
@section('page-title', 'TEAM TIME TRACKING')
@section('page-subtitle', 'Monitor your team\'s work time and productivity')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Time Logs</h3>

            <form method="GET" action="{{ route('leader.time-tracking') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                        <select name="project_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $selectedProject == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Team Member</label>
                        <select name="user_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Members</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}" {{ $selectedUser == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                        Apply Filter
                    </button>
                    <a href="{{ route('leader.time-tracking') }}"
                       class="text-center sm:text-left text-green-600 hover:text-green-800 text-sm font-medium py-2">
                        Reset all filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Hours</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @if($totalHours >= 1)
                                    {{ $totalHours }}h
                                @else
                                    {{ $totalMinutes }}m
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Hours</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @if($totalHours >= 1)
                                    {{ $totalHours }}h
                                @else
                                    {{ $totalMinutes }}m
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Members</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $userStats->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg per Member</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $userStats->count() > 0 ? round($totalHours / $userStats->count(), 1) : 0 }}h
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Statistics -->
        @if($userStats->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Time by Team Member</h3>

                <div class="space-y-3">
                    @foreach($userStats->sortByDesc('total_hours') as $stats)
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <img class="w-10 h-10 rounded-full flex-shrink-0"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($stats['user']->name) }}&background=random"
                                 alt="{{ $stats['user']->name }}">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-sm sm:text-base text-gray-900 break-words">{{ $stats['user']->name }}</h4>
                                <p class="text-xs sm:text-sm text-gray-500">
                                    {{ $stats['session_count'] }} sessions • {{ $stats['projects_count'] }} projects
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-3">
                            <p class="font-semibold text-sm sm:text-base text-gray-900">
                                @if($stats['total_hours'] >= 1)
                                    {{ $stats['total_hours'] }}h
                                @else
                                    {{ $stats['total_minutes'] }}m
                                @endif
                            </p>
                            <p class="text-xs sm:text-sm text-gray-500">{{ $stats['session_count'] }} sessions</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Project Statistics -->
        @if($projectStats->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Time by Project</h3>

                <div class="space-y-3">
                    @foreach($projectStats->sortByDesc('total_hours') as $stats)
                    @if($stats['project'])
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-sm sm:text-base text-gray-900 break-words">{{ $stats['project']->project_name }}</h4>
                                <p class="text-xs sm:text-sm text-gray-500">
                                    {{ $stats['session_count'] }} sessions • {{ $stats['users_count'] }} members
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-3">
                            <p class="font-semibold text-sm sm:text-base text-gray-900">
                                @if($stats['total_hours'] >= 1)
                                    {{ $stats['total_hours'] }}h
                                @else
                                    {{ $stats['total_minutes'] }}m
                                @endif
                            </p>
                            <p class="text-xs sm:text-sm text-gray-500">{{ $stats['session_count'] }} sessions</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Time Logs List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 py-4 sm:px-6 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">Team Time Logs</h3>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500">Detailed work time entries from all team members</p>
                </div>
                @if($timeLogs->count() > 0)
                <div class="text-xs sm:text-sm text-gray-500">
                    Showing {{ $timeLogs->count() }} entries
                </div>
                @endif
            </div>
        </div>

        @if($timeLogs->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($timeLogs as $log)
                    <div class="px-3 py-3 sm:px-6 sm:py-4">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <!-- Left side - User info and details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start space-x-3">
                                    <!-- Avatar - smaller on mobile -->
                                    <img class="w-10 h-10 rounded-full flex-shrink-0"
                                         src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&background=random"
                                         alt="{{ $log->user->name }}">

                                    <div class="flex-1 min-w-0">
                                        <!-- User name -->
                                        <div class="mb-1">
                                            <span class="text-sm sm:text-base font-medium text-gray-900 break-words">{{ $log->user->name }}</span>
                                        </div>

                                        <!-- Task info -->
                                        @if($log->card)
                                            <div class="mb-2">
                                                <h4 class="text-xs sm:text-sm text-gray-700 break-words mb-1">
                                                    {{ $log->card->card_title }}
                                                </h4>
                                                @if($log->card->board && $log->card->board->project)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $log->card->board->project->project_name }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Subtask info -->
                                        @if($log->subtask)
                                            <div class="flex items-start space-x-2 mb-2">
                                                <div class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <svg class="w-2 h-2 text-blue-600" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                </div>
                                                <p class="text-xs sm:text-sm text-blue-600 break-words">
                                                    {{ $log->subtask->subtask_title }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Description -->
                                        @if($log->description)
                                            <p class="text-xs sm:text-sm text-gray-600 mb-2 break-words">{{ $log->description }}</p>
                                        @endif

                                        <!-- Time info - stack on mobile -->
                                        <div class="flex flex-col sm:flex-row sm:items-center text-xs text-gray-500 gap-1 sm:gap-4">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $log->start_time->format('M d, Y H:i') }}
                                            </span>
                                            @if($log->end_time)
                                                <span class="pl-5 sm:pl-0">to {{ $log->end_time->format('H:i') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right side - Duration - align differently on mobile -->
                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-2 pl-13 sm:pl-0">
                                <div class="text-base sm:text-lg font-semibold text-green-600">
                                    {{ $log->formatted_duration }}
                                </div>
                                <div class="text-xs sm:text-sm text-gray-500">
                                    {{ $log->duration_hours }}h
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No time logs found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($selectedUser || $selectedProject)
                        No work time has been logged matching your filters.
                    @else
                        No work time has been logged for the selected period.
                    @endif
                </p>
                @if($selectedUser || $selectedProject)
                    <div class="mt-3">
                        <a href="{{ route('leader.time-tracking', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Clear filters
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change (optional)
    const filterInputs = document.querySelectorAll('select[name="project_id"], select[name="user_id"]');

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Optional: Auto-submit form on filter change
            // this.form.submit();
        });
    });
});
</script>
@endsection
