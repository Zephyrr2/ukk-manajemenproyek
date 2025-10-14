@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-user')
@endsection

@section('title', 'My Time Tracking')
@section('page-title', 'TIME TRACKING')
@section('page-subtitle', 'Track your work time and productivity')

@section('content')
<div class="space-y-6">
    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Time Logs</h3>

            <form method="GET" action="{{ route('user.time-tracking') }}" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('user.time-tracking') }}"
                       class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                            <dd class="text-lg font-medium text-gray-900">{{ $totalHours }}h</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Sessions</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $timeLogs->count() }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg per Day</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $dailyStats->count() > 0 ? round($totalHours / $dailyStats->count(), 1) : 0 }}h
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Statistics -->
    @if($projectStats->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Time by Project</h3>

            <div class="space-y-4">
                @foreach($projectStats as $projectName => $stats)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $projectName }}</h4>
                            <p class="text-sm text-gray-500">{{ $stats['session_count'] }} sessions</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ $stats['total_hours'] }}h</p>
                        <p class="text-sm text-gray-500">{{ $stats['total_minutes'] }} minutes</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Time Logs List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Time Logs</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Your detailed work time entries</p>
        </div>

        @if($timeLogs->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($timeLogs as $log)
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($log->subtask_id)
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            @if($log->card)
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $log->card->card_title }}
                                                </h4>
                                                @if($log->card->board && $log->card->board->project)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $log->card->board->project->project_name }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>

                                        @if($log->subtask)
                                            <p class="text-sm text-blue-600 mb-1">
                                                Subtask: {{ $log->subtask->subtask_title }}
                                            </p>
                                        @endif

                                        @if($log->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ $log->description }}</p>
                                        @endif

                                        <div class="flex items-center text-xs text-gray-500 space-x-4">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $log->start_time->format('M d, Y H:i') }}
                                            </span>
                                            @if($log->end_time)
                                                <span>to {{ $log->end_time->format('H:i') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-4 flex-shrink-0 text-right">
                                <div class="text-lg font-semibold text-green-600">
                                    {{ $log->formatted_duration }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $log->duration_hours }}h
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination would go here if needed -->
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No time logs found</h3>
                <p class="mt-1 text-sm text-gray-500">No work time has been logged for the selected period.</p>
            </div>
        @endif
    </div>
</div>

<script>
// Auto-submit form when dates change
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');

    if (startDateInput && endDateInput) {
        [startDateInput, endDateInput].forEach(input => {
            input.addEventListener('change', function() {
                // Optional: Auto-submit form on date change
                // this.form.submit();
            });
        });
    }
});
</script>
@endsection
