@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-user')
@endsection

@section('title', 'Task History')
@section('page-title', 'TASK HISTORY')
@section('page-subtitle', 'History assignment untuk task: ' . $task->card_title)

@section('content')
<!-- Task Information -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Task Information</h3>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Task Title</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->card_title }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                <dd class="mt-1">
                    @if($task->status === 'todo')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">To Do</span>
                    @elseif($task->status === 'in_progress')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">In Progress</span>
                    @elseif($task->status === 'review')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">In Review</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Done</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Project</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->board->project->project_name ?? 'N/A' }}</dd>
            </div>
            @if($task->estimated_hours)
            <div>
                <dt class="text-sm font-medium text-gray-500">Estimated Hours</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->estimated_hours }} hours</dd>
            </div>
            @endif
            @if($task->actual_hours)
            <div>
                <dt class="text-sm font-medium text-gray-500">Actual Hours</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->actual_hours }} hours</dd>
            </div>
            @endif
            @if($task->description)
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->description }}</dd>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Assignment History -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Assignment History</h3>
            <a href="{{ route('user.tasks') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                ← Back to Tasks
            </a>
        </div>
    </div>
    <div class="px-4 py-5 sm:p-6">
        @if($history->count() > 0)
            <div class="space-y-4">
                @foreach($history as $assignment)
                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg">
                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-medium">
                        {{ strtoupper(substr($assignment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ $assignment->user->name }}</h4>
                            @if($assignment->assignment_status === 'completed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                            @elseif($assignment->assignment_status === 'in_progress')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">In Progress</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($assignment->assignment_status) }}</span>
                            @endif
                        </div>

                        <div class="text-sm text-gray-500 space-y-1">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Assigned: {{ $assignment->assigned_at ? $assignment->assigned_at->format('M d, Y H:i') : 'N/A' }}</span>
                            </div>

                            @if($assignment->started_at)
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Started: {{ $assignment->started_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif

                            @if($assignment->completed_at)
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Completed: {{ $assignment->completed_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="text-xs text-gray-400">
                        {{ $assignment->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada history</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada assignment history untuk task ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
