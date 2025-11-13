@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-user')
@endsection

@section('title', 'Subtasks')
@section('page-title', 'SUBTASKS')

@section('content')
<!-- Flash Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Task Information -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex-1">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Task Information</h3>
                <p class="mt-1 text-sm text-gray-500 break-words">{{ $task->card_title }}</p>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                @if($task->status === 'todo')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 w-fit">To Do</span>
                @elseif($task->status === 'in_progress')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 w-fit">In Progress</span>
                @elseif($task->status === 'review')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-fit">In Review</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-fit">Done</span>
                @endif
                <a href="{{ route('user.tasks') }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                    ← Back to Tasks
                </a>
            </div>
        </div>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $subtaskStats['total'] }}</div>
                <div class="text-xs sm:text-sm text-gray-500">Total</div>
            </div>
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-gray-600">{{ $subtaskStats['todo'] }}</div>
                <div class="text-xs sm:text-sm text-gray-500">To Do</div>
            </div>
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $subtaskStats['in_progress'] }}</div>
                <div class="text-xs sm:text-sm text-gray-500">In Progress</div>
            </div>
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-green-600">{{ $subtaskStats['done'] }}</div>
                <div class="text-xs sm:text-sm text-gray-500">Completed</div>
            </div>
            <div class="text-center col-span-2 sm:col-span-1">
                <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ number_format($subtaskStats['total_estimated'], 1) }}h</div>
                <div class="text-xs sm:text-sm text-gray-500">Est. Hours</div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Subtask -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Subtasks Management</h3>
            <a href="{{ route('user.subtasks.create', $task->id) }}"
               class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full sm:w-auto">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New Subtask
            </a>
        </div>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <p class="text-sm text-gray-600">
            Click "Add New Subtask" to create a new subtask for this task. You can manage all subtasks from this page.
        </p>
    </div>
</div>

<!-- Subtasks List -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Subtasks</h3>
    </div>
    <div class="px-4 py-5 sm:p-6">
        @if($subtasks->count() > 0)
            <div class="space-y-4">
                @foreach($subtasks as $subtask)
                <div class="border border-gray-200 rounded-lg p-4 {{ $subtask->status === 'done' ? 'bg-green-50' : '' }}">
                    <div class="flex flex-col lg:flex-row items-start space-y-4 lg:space-y-0">
                        <div class="flex items-start space-x-3 flex-1 w-full">
                            <!-- Status Icon -->
                            <div class="mt-0.5 flex-shrink-0">
                                @if($subtask->status === 'done')
                                    <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($subtask->status === 'in_progress')
                                    <svg class="h-6 w-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>

                            <!-- Subtask Content -->
                            <div class="flex-1 min-w-0 w-full">
                                <h4 class="text-sm font-medium text-gray-900 break-words {{ $subtask->status === 'done' ? 'line-through text-gray-500' : '' }}">
                                    {{ $subtask->subtask_title }}
                                </h4>
                                @if($subtask->description)
                                    <p class="text-sm text-gray-500 mt-1 break-words {{ $subtask->status === 'done' ? 'line-through' : '' }}">
                                        {{ $subtask->description }}
                                    </p>
                                @endif
                                <div class="flex flex-wrap items-center gap-3 sm:gap-4 mt-2 text-xs text-gray-500">
                                    @if($subtask->estimated_hours)
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Est: {{ $subtask->estimated_hours }}h
                                        </span>
                                    @endif
                                    @if($subtask->actual_hours)
                                        <span class="flex items-center text-green-600 font-medium">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Actual: {{ $subtask->actual_hours }}h
                                        </span>
                                    @endif
                                    <span class="flex items-center">
                                        @if($subtask->status === 'done')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">✓ Completed</span>
                                        @elseif($subtask->status === 'in_progress')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">⏱️ In Progress</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">To Do</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="w-full lg:w-auto lg:ml-4 flex-shrink-0 flex flex-wrap gap-2 justify-start lg:justify-end">
                            <!-- Time Tracking Buttons -->
                            @php
                                $activeSubtaskLog = \App\Models\Time_Log::where('user_id', $user->id)
                                    ->where('subtask_id', $subtask->id)
                                    ->whereNull('end_time')
                                    ->first();
                            @endphp

                            @if($subtask->status === 'todo')
                                <!-- Start Button -->
                                <form action="{{ route('user.subtasks.start', [$task->id, $subtask->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        onclick="return confirm('Mulai mengerjakan subtask ini?')">
                                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                        </svg>
                                        Start
                                    </button>
                                </form>
                            @elseif($subtask->status === 'in_progress')
                                <!-- Pause Button -->
                                @if($activeSubtaskLog)
                                    <form action="{{ route('user.subtasks.pause', [$task->id, $subtask->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                                            onclick="return confirm('Pause subtask? Time will be saved.')">
                                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Pause
                                        </button>
                                    </form>
                                @endif

                                <!-- Done Button -->
                                <form action="{{ route('user.subtasks.complete', [$task->id, $subtask->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        onclick="return confirm('Mark subtask as completed?')">
                                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Done
                                    </button>
                                </form>
                            @endif

                            <!-- Edit Button -->
                            @if($subtask->status !== 'done')
                                <a href="{{ route('user.subtasks.edit', [$task->id, $subtask->id]) }}"
                                   class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            @endif

                            <!-- Delete Button -->
                            <form action="{{ route('user.subtasks.destroy', [$task->id, $subtask->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this subtask?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-2 py-1 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Subtask Comments (Collapsible) -->
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <button type="button"
                                onclick="document.getElementById('subtask-comments-{{ $subtask->id }}').classList.toggle('hidden')"
                                class="flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium">
                            <span class="mr-2">💬</span>
                            Comments ({{ $subtask->comments->count() }})
                            <svg class="w-4 h-4 ml-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="subtask-comments-{{ $subtask->id }}" class="hidden mt-3 space-y-3">
                            <!-- Add Comment Form -->
                            <div class="bg-gray-50 rounded-lg p-3">
                                <form action="{{ route(auth()->user()->role . '.subtasks.comments.store', [$task->id, $subtask->id]) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Add Comment
                                        </label>
                                        <textarea
                                            name="comment_text"
                                            rows="2"
                                            required
                                            maxlength="1000"
                                            class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Write a comment for this subtask..."
                                        ></textarea>
                                        <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z"/>
                                                <path fill="currentColor" fill-rule="evenodd" d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16" clip-rule="evenodd"/>
                                            </svg>
                                            Send
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Comments List -->
                            <div class="space-y-2">
                                @forelse($subtask->comments->sortByDesc('created_at') as $comment)
                                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                                    <span class="text-green-600 font-medium text-xs">
                                                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-900">{{ $comment->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>

                                            @if(auth()->user()->id == $comment->user_id ||
                                                (auth()->user()->role == 'leader' && $task->board->project->user_id == auth()->user()->id))
                                                <form action="{{ route(auth()->user()->role . '.comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-700 whitespace-pre-wrap">{{ $comment->comment_text }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <p class="text-xs text-gray-500">No comments for this subtask yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No subtasks yet</h3>
                <p class="mt-1 text-sm text-gray-500">Start by adding your first subtask for this task.</p>
            </div>
        @endif
    </div>
</div>

@endsection
