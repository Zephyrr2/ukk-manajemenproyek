@extends('layouts.dashboard')

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('title', 'My Tasks')
@section('page-title', 'MY TASKS')

@section('content')
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Task Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-3 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                            <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $taskStats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-3 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">To Do</dt>
                            <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $taskStats['todo'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-3 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">In Progress</dt>
                            <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $taskStats['in_progress'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-3 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">In Review</dt>
                            <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $taskStats['review'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Tasks List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-3 py-4 sm:px-6 sm:py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">My Tasks</h3>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $myTasks->count() }} tasks
                </span>
            </div>
        </div>
        <div class="px-3 py-4 sm:px-6 sm:py-5">
            @if ($myTasks->count() > 0)
                <div class="space-y-4">
                    @foreach ($myTasks as $task)
                        <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                            <!-- Mobile & Desktop Layout -->
                            <div class="space-y-3">
                                <!-- Header with badges -->
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($task->status === 'todo')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">To Do</span>
                                    @elseif($task->status === 'in_progress')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">In Progress</span>
                                    @elseif($task->status === 'review')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">In Review</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Done</span>
                                    @endif

                                    @if ($task->priority)
                                        @if ($task->priority === 'high')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">High Priority</span>
                                        @elseif($task->priority === 'medium')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Medium Priority</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Low Priority</span>
                                        @endif
                                    @endif
                                </div>

                                <!-- Title and description -->
                                <div>
                                    <h4 class="text-sm sm:text-base font-medium text-gray-900 mb-1 break-words">{{ $task->card_title }}</h4>
                                    <p class="text-xs sm:text-sm text-gray-500 mb-2 break-words">{{ Str::limit($task->description, 100) }}</p>
                                </div>

                                <!-- Task metadata -->
                                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-4 gap-y-2 text-xs text-gray-500">
                                    <span class="font-medium break-words">{{ $task->board->project->project_name ?? 'N/A' }}</span>
                                    @if ($task->due_date)
                                        @php
                                            $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'done';
                                        @endphp
                                        <span class="flex items-center {{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                            @if($isOverdue)
                                                <span class="ml-1 text-xs">(OVERDUE)</span>
                                            @endif
                                        </span>
                                        @if ($task->extension_status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Extension Pending
                                            </span>
                                        @endif
                                    @endif
                                    @if ($task->estimated_hours)
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Est: {{ $task->estimated_hours }}h
                                        </span>
                                    @endif
                                    @if ($task->actual_hours)
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Actual: {{ $task->actual_hours }}h
                                        </span>
                                    @endif
                                    @if ($task->subtasks->count() > 0)
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Subtasks: {{ $task->subtasks->where('status', 'done')->count() }}/{{ $task->subtasks->count() }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Action buttons - wrap on mobile -->
                                <div class="flex flex-col sm:flex-row flex-wrap gap-2 pt-2 border-t border-gray-100">
                                    @if ($task->status === 'todo')
                                        <form action="{{ route('user.tasks.start', $task->id) }}" method="POST"
                                            class="w-full sm:w-auto"
                                            onsubmit="return confirm('Are you sure you want to start this task?')">
                                            @csrf
                                            <button type="submit"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                ▶ Start Task
                                            </button>
                                        </form>
                                    @elseif($task->status === 'in_progress')
                                        <!-- Time Tracking Buttons -->
                                        @if($user->status === 'paused' && $pausedSession && $pausedSession->card_id == $task->id)
                                            <!-- Resume Button (user paused on this task) -->
                                            <form action="{{ route('user.time-tracking.resume') }}" method="POST" class="w-full sm:w-auto">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    onclick="return confirm('Resume work? Timer will be reactivated.')">
                                                    ▶️ Resume
                                                </button>
                                            </form>
                                        @elseif($activeSession && $activeSession->card_id == $task->id)
                                            <!-- Pause Button (user working on this task) -->
                                            <form action="{{ route('user.time-tracking.pause') }}" method="POST" class="w-full sm:w-auto">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                                    onclick="return confirm('Pause work? Your time will be saved.')">
                                                    ⏸️ Pause
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Submit for Review Button -->
                                        <form action="{{ route('user.tasks.submit', $task->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Are you sure you want to submit this task for review?')">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                📤 Submit for Review
                                            </button>
                                        </form>
                                    @elseif($task->status === 'review')
                                        <span class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md">
                                            ⏳ Waiting for Review
                                        </span>
                                    @else
                                        <span class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md">
                                            ✅ Completed
                                        </span>
                                    @endif

                                    <a href="{{ route('user.subtasks', $task->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        📋 Subtasks
                                    </a>

                                    @php
                                        $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'done';
                                    @endphp
                                    @if($isOverdue && $task->extension_status !== 'pending' && $task->status === 'in_progress')
                                        <button onclick="openExtensionModal({{ $task->id }}, '{{ $task->card_title }}', '{{ $task->due_date }}')" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-orange-300 text-xs font-medium rounded-md text-orange-700 bg-orange-50 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                            ⏰ Request Extension
                                        </button>
                                    @endif

                                    <button onclick="openCommentModal('task', {{ $task->id }}, {{ $task->id }})" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        💬 Comment
                                        @if ($task->comments && $task->comments->count() > 0)
                                            <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $task->comments->count() }}
                                            </span>
                                        @endif
                                    </button>
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
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no tasks assigned to you at this time.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Comment Modal - Responsive -->
    <div id="commentModal" class="fixed inset-0 hidden z-50" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="flex items-center justify-center min-h-screen px-2 sm:px-4 py-4 sm:py-8">
            <!-- Modal backdrop click area -->
            <div class="fixed inset-0" onclick="closeCommentModal()"></div>

            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] sm:max-h-[85vh] overflow-hidden z-10">
                <div class="bg-white px-4 sm:px-6 pt-4 sm:pt-6 pb-4">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="sm:w-[30px] sm:h-[30px]" viewBox="0 0 24 24">
                                <path fill="#1E88E5" fill-rule="evenodd"
                                    d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z" />
                                <path fill="#1E88E5" fill-rule="evenodd"
                                    d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4 w-full">
                            <h3 class="text-lg sm:text-xl leading-6 font-semibold text-gray-900" id="modalTitle">
                                Komentar Task
                            </h3>
                            <p class="mt-1 text-xs sm:text-sm text-gray-500" id="modalSubtitle">
                                Diskusi dan catatan terkait task ini
                            </p>
                        </div>
                        <button type="button" class="ml-2 text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0"
                            onclick="closeCommentModal()">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 sm:mt-6 px-0 sm:px-6 pb-4 sm:pb-6">
                        <!-- Add Comment Form -->
                        @if (auth()->user()->role !== 'leader')
                            <form id="commentForm" method="POST" class="space-y-4 mb-6">
                                @csrf
                                <input type="hidden" name="task_id" id="modal_task_id" value="">
                                <div>
                                    <label for="modal_comment_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Add Comment
                                    </label>
                                    <textarea name="comment_text" id="modal_comment_text" rows="4" required maxlength="1000"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                        placeholder="Write your comment..."></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 inline"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z" />
                                            <path fill="currentColor" fill-rule="evenodd"
                                                d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Send Comment
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- Comments List -->
                        <div id="commentsList" class="space-y-4 max-h-80 overflow-y-auto border-t border-gray-200 pt-4">
                            <!-- Comments will be loaded here via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCommentType = '';
        let currentItemId = '';
        let currentTaskId = '';

        function openCommentModal(type, itemId, taskId) {
            currentCommentType = type;
            currentItemId = itemId;
            currentTaskId = taskId;

            const modal = document.getElementById('commentModal');
            modal.classList.remove('hidden');
            modal.style.display = 'block';

            // Update form action and task_id
            const form = document.getElementById('commentForm');
            const taskIdInput = document.getElementById('modal_task_id');
            if (form && taskIdInput) {
                form.action = `/user/tasks/${itemId}/comments`;
                taskIdInput.value = itemId;
            }

            // Load comments and check if user has existing comment
            loadComments(type, itemId, taskId);
        }

        function closeCommentModal() {
            const modal = document.getElementById('commentModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.getElementById('modal_comment_text').value = '';
        }

        function loadComments(type, itemId, taskId) {
            // Get task comments from the data (only task-level comments, not subtask comments)
            const allTasks = @json($myTasks);
            const task = allTasks.find(t => t.id == itemId);
            const comments = task && task.comments ? task.comments : [];

            displayComments(comments);
        }

        function displayComments(comments) {
            const commentsList = document.getElementById('commentsList');

            if (comments.length === 0) {
                commentsList.innerHTML = `
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto" width="60" height="60" viewBox="0 0 24 24"><path fill="#BDBDBD" d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z"/><path fill="#BDBDBD" fill-rule="evenodd" d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16" clip-rule="evenodd"/></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada komentar</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai diskusi dengan menulis komentar pertama.</p>
            </div>
        `;
                return;
            }

            const currentUserId = {{ auth()->user()->id }};

            // Sort comments by created_at descending
            comments.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            commentsList.innerHTML = comments.map(comment => `
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-medium text-sm">
                                ${comment.user.name.substring(0, 2).toUpperCase()}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${comment.user.name}</p>
                        <p class="text-xs text-gray-500">
                            ${comment.user.role === 'leader' ? 'Project Leader' : 'Team Member'} •
                            ${formatDate(comment.created_at)} ${comment.updated_at !== comment.created_at ? '(edited)' : ''}
                        </p>
                    </div>
                </div>

                ${comment.user_id == currentUserId ? `
                                <form action="/user/comments/${comment.id}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            ` : ''}
            </div>

            <div class="mt-3">
                <p class="text-sm text-gray-700 whitespace-pre-wrap">${comment.comment_text}</p>
            </div>
        </div>
    `).join('');
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'a few seconds ago';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
            return `${Math.floor(diffInSeconds / 86400)} days ago`;
        }

        // Extension Request Modal
        function openExtensionModal(taskId, taskTitle, currentDeadline) {
            const modal = document.getElementById('extensionModal');
            document.getElementById('extensionTaskTitle').textContent = taskTitle;
            document.getElementById('extensionCurrentDeadline').textContent = currentDeadline;
            document.getElementById('extensionForm').action = `/user/tasks/${taskId}/request-extension`;

            // Set min date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('new_deadline').min = tomorrow.toISOString().split('T')[0];

            modal.classList.remove('hidden');
        }

        function closeExtensionModal() {
            document.getElementById('extensionModal').classList.add('hidden');
        }
    </script>

    <!-- Extension Request Modal -->
    <div id="extensionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Request Deadline Extension</h3>

                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Task:</span> <span id="extensionTaskTitle"></span></p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Current Deadline:</span> <span id="extensionCurrentDeadline"></span></p>
                </div>

                <form id="extensionForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="new_deadline" class="block text-sm font-medium text-gray-700 mb-2">New Deadline</label>
                        <input type="date" name="new_deadline" id="new_deadline" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Extension</label>
                        <textarea name="reason" id="reason" rows="4" required maxlength="500"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Explain why you need more time..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeExtensionModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
