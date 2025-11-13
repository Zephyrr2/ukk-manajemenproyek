@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-user')
@endsection

@section('title', 'My Time Tracking')
@section('page-title', 'TIME LOG')

@section('content')
<div class="space-y-6">
    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-3 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Filter Time Logs</h3>

            <form method="GET" action="{{ route('user.time-tracking') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                        Apply Filter
                    </button>
                    <a href="{{ route('user.time-tracking') }}"
                       class="w-full sm:w-auto text-center bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-3 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Hours</dt>
                            <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $totalHours }}h</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-3 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Sessions</dt>
                            <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $timeLogs->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-3 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Avg per Day</dt>
                            <dd class="text-base sm:text-lg font-medium text-gray-900">
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
        <div class="p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Time by Project</h3>

            <div class="space-y-3">
                @foreach($projectStats as $projectName => $stats)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 bg-gray-50 rounded-lg gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm sm:text-base font-medium text-gray-900 break-words">{{ $projectName }}</h4>
                            <p class="text-xs sm:text-sm text-gray-500">{{ $stats['session_count'] }} sessions</p>
                        </div>
                    </div>
                    <div class="text-left sm:text-right pl-13 sm:pl-0">
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $stats['total_hours'] }}h</p>
                        <p class="text-xs sm:text-sm text-gray-500">{{ $stats['total_minutes'] }} minutes</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Subtask Time Logs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-3 py-4 sm:px-6 sm:py-5 border-b border-gray-200">
            <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">Subtask Time Logs</h3>
            <p class="mt-1 max-w-2xl text-xs sm:text-sm text-gray-500">Your detailed work time entries on subtasks</p>
        </div>

        @if($timeLogs->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($timeLogs as $log)
                    <div class="px-3 py-3 sm:px-6 sm:py-4">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            @if($log->card)
                                                <h4 class="text-sm sm:text-base font-medium text-gray-900 break-words">
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
                                            <p class="text-xs sm:text-sm text-blue-600 mb-1 break-words">
                                                <span class="font-medium">Subtask:</span> {{ $log->subtask->subtask_title }}
                                            </p>
                                        @endif

                                        @if($log->description)
                                            <p class="text-xs sm:text-sm text-gray-600 mb-2 break-words">{{ $log->description }}</p>
                                        @endif

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

                            <div class="pl-11 sm:pl-0 sm:ml-4 flex-shrink-0 sm:text-right">
                                <div class="text-base sm:text-lg font-semibold text-blue-600">
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
                <p class="mt-1 text-sm text-gray-500">No work time has been logged for subtasks in the selected period.</p>
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
