@extends('layouts.admin')

@section('title', 'Project Board - ' . $project->project_name)
@section('page-title', 'Project Board')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" onclick="this.parentElement.parentElement.style.display='none';">
                            <path d="M10 10l4-4m0 0l4 4m-4-4v8m0-8l-4 4"/>
                        </svg>
                    </span>
                </div>
            @endif

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.projects.show', $project->slug) }}"
                        class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Project</span>
                    </a>
                    <div class="w-px h-6 bg-gray-300"></div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $project->project_name }}</h1>
                        <p class="text-gray-500">Kanban Board</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-gray-100 px-4 py-2 rounded-lg font-medium text-gray-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Only
                    </div>
                </div>
            </div>

            <!-- Board Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">To Do</p>
                            <p class="text-2xl font-bold text-gray-600">{{ count($boardData['todo'] ?? []) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">In Progress</p>
                            <p class="text-2xl font-bold text-green-600">{{ count($boardData['in_progress']) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Review</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ count($boardData['review']) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Done</p>
                            <p class="text-2xl font-bold text-green-600">{{ count($boardData['done']) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanban Board -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="p-6 kanban-container" style="overflow-x: auto; overflow-y: hidden;">
                    <div class="flex gap-6 pb-4" id="kanban-board" style="min-width: 1400px; width: max-content;">

                        <!-- To Do Column -->
                        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4" data-status="todo">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                                    To Do
                                    <span class="ml-2 bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">{{ count($boardData['todo'] ?? []) }}</span>
                                </h3>
                            </div>
                            <div class="space-y-3" id="todo-cards">
                                @if(isset($boardData['todo']) && count($boardData['todo']) > 0)
                                    @foreach($boardData['todo'] as $task)
                                        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-gray-500"
                                             data-task-id="{{ $task->id }}">
                                            <div class="flex items-start justify-between mb-2">
                                                <h4 class="font-medium text-gray-900">{{ $task->card_title }}</h4>
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' :
                                                       ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3">{{ $task->description }}</p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <img class="w-6 h-6 rounded-full"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name ?? 'Unassigned') }}&size=24&background=random"
                                                        alt="{{ $task->user->name ?? 'Unassigned' }}">
                                                    <span class="text-xs text-gray-500">{{ $task->user->name ?? 'Unassigned' }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @if($task->estimated_hours)
                                                        <span class="text-xs text-gray-600 font-medium">{{ $task->estimated_hours }}h</span>
                                                    @endif
                                                    @if($task->due_date)
                                                        <span class="text-xs text-gray-400">{{ $task->due_date->format('M d') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <p class="mt-2 text-sm">No tasks in To Do</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- In Progress Column -->
                        <div class="flex-shrink-0 w-80 bg-green-50 rounded-lg p-4" data-status="in_progress">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    In Progress
                                    <span class="ml-2 bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">{{ count($boardData['in_progress']) }}</span>
                                </h3>
                            </div>
                            <div class="space-y-3" id="in_progress-cards">
                                @foreach($boardData['in_progress'] as $task)
                                    <div class="bg-white rounded-lg border border-blue-200 p-4 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-blue-500"
                                         data-task-id="{{ $task->id }}">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $task->card_title }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' :
                                                   ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">{{ $task->description }}</p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <img class="w-6 h-6 rounded-full"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name ?? 'Unassigned') }}&size=24&background=random"
                                                    alt="{{ $task->user->name ?? 'Unassigned' }}">
                                                <span class="text-xs text-gray-500">{{ $task->user->name ?? 'Unassigned' }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($task->estimated_hours)
                                                    <span class="text-xs text-green-600 font-medium">{{ $task->estimated_hours }}h</span>
                                                @endif
                                                @if($task->due_date)
                                                    <span class="text-xs text-gray-400">{{ $task->due_date->format('M d') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Review Column -->
                        <div class="flex-shrink-0 w-80 bg-yellow-50 rounded-lg p-4" data-status="review">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    Review
                                    <span class="ml-2 bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">{{ count($boardData['review']) }}</span>
                                </h3>
                            </div>
                            <div class="space-y-3" id="review-cards">
                                @foreach($boardData['review'] as $task)
                                    <div class="bg-white rounded-lg border border-yellow-200 p-4 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-yellow-500"
                                         data-task-id="{{ $task->id }}">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $task->card_title }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' :
                                                   ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">{{ $task->description }}</p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <img class="w-6 h-6 rounded-full"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name ?? 'Unassigned') }}&size=24&background=random"
                                                    alt="{{ $task->user->name ?? 'Unassigned' }}">
                                                <span class="text-xs text-gray-500">{{ $task->user->name ?? 'Unassigned' }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="text-xs text-yellow-600 font-medium">Review</span>
                                                @if($task->due_date)
                                                    <span class="text-xs text-gray-400 ml-2">{{ $task->due_date->format('M d') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Done Column -->
                        <div class="flex-shrink-0 w-80 bg-green-50 rounded-lg p-4" data-status="done">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    Done
                                    <span class="ml-2 bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">{{ count($boardData['done']) }}</span>
                                </h3>
                            </div>
                            <div class="space-y-3" id="done-cards">
                                @foreach($boardData['done'] as $task)
                                    <div class="bg-white rounded-lg border border-green-200 p-4 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-green-500 opacity-75"
                                         data-task-id="{{ $task->id }}">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-medium text-gray-900 line-through">{{ $task->card_title }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">{{ $task->description }}</p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <img class="w-6 h-6 rounded-full"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name ?? 'Completed') }}&size=24&background=random"
                                                    alt="{{ $task->user->name ?? 'Completed' }}">
                                                <span class="text-xs text-gray-500">{{ $task->user->name ?? 'Completed' }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-xs text-green-600 font-medium">Done</span>
                                                @if($task->updated_at)
                                                    <span class="text-xs text-gray-400 ml-2">{{ $task->updated_at->format('M d') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    let draggedElement = null;

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        draggedElement = ev.target;
        ev.dataTransfer.effectAllowed = "move";
        ev.target.style.opacity = "0.5";
    }

    function drop(ev) {
        ev.preventDefault();

        if (draggedElement) {
            const dropZone = ev.target.closest('[data-status]');
            if (dropZone) {
                const targetStatus = dropZone.getAttribute('data-status');
                const cardsContainer = dropZone.querySelector('[id$="-cards"]');

                if (cardsContainer) {
                    // Move the element
                    cardsContainer.appendChild(draggedElement);

                    // Update counters
                    updateCounters();

                    // Reset opacity
                    draggedElement.style.opacity = "1";

                    // Update visual styling based on status
                    updateTaskStyling(draggedElement, targetStatus);

                    // In a real application, you would send an AJAX request here
                    // to update the task status in the database
                    console.log('Task moved to:', targetStatus);
                }
            }
        }

        draggedElement = null;
    }

    function updateCounters() {
        const columns = ['todo', 'in_progress', 'review', 'done'];

        columns.forEach(status => {
            const cardsContainer = document.getElementById(status + '-cards');
            const count = cardsContainer.children.length;

            // Update counter in column header
            const counterElement = document.querySelector(`[data-status="${status}"] span.rounded-full`);
            if (counterElement) {
                counterElement.textContent = count;
            }

            // Update counter in stats section
            const statsCards = document.querySelectorAll('.bg-white.rounded-lg.border.border-gray-200.p-4');
            const statusNames = ['To Do', 'In Progress', 'Review', 'Done'];
            const statusIndex = columns.indexOf(status);

            if (statsCards[statusIndex]) {
                const countElement = statsCards[statusIndex].querySelector('.text-2xl.font-bold');
                if (countElement) {
                    countElement.textContent = count;
                }
            }
        });
    }

    function updateTaskStyling(taskElement, status) {
        // Remove all status-specific classes
        taskElement.classList.remove('border-l-4', 'border-l-blue-500', 'border-l-yellow-500', 'border-l-green-500', 'opacity-75');

        const titleElement = taskElement.querySelector('h4');
        const statusBadge = taskElement.querySelector('.px-2.py-1.text-xs.rounded-full');

        if (titleElement) {
            titleElement.classList.remove('line-through');
        }

        // Apply status-specific styling
        switch (status) {
            case 'todo':
                taskElement.classList.add('border-l-4', 'border-l-gray-500');
                break;
            case 'in_progress':
                taskElement.classList.add('border-l-4', 'border-l-blue-500');
                break;
            case 'review':
                taskElement.classList.add('border-l-4', 'border-l-yellow-500');
                break;
            case 'done':
                taskElement.classList.add('border-l-4', 'border-l-green-500', 'opacity-75');
                if (titleElement) {
                    titleElement.classList.add('line-through');
                }
                if (statusBadge && !statusBadge.textContent.includes('priority')) {
                    statusBadge.textContent = 'Completed';
                    statusBadge.className = 'px-2 py-1 text-xs rounded-full bg-green-100 text-green-800';
                }
                break;
        }
    }

    // Close success message after 5 seconds
    setTimeout(function() {
        const successMessage = document.querySelector('.bg-green-100');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000);

    // Initialize drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add custom CSS for better horizontal scroll
        const style = document.createElement('style');
        style.textContent = `
            #kanban-board {
                scrollbar-width: thin;
                scrollbar-color: #d1d5db #f9fafb;
            }

            #kanban-board::-webkit-scrollbar {
                height: 8px;
            }

            #kanban-board::-webkit-scrollbar-track {
                background: #f9fafb;
                border-radius: 4px;
            }

            #kanban-board::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 4px;
            }

            #kanban-board::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }

            .kanban-container {
                overflow-x: auto !important;
                overflow-y: hidden;
            }
        `;
        document.head.appendChild(style);

        // Make all task cards draggable
        const taskCards = document.querySelectorAll('[data-task-id]');
        taskCards.forEach(card => {
            card.draggable = true;
            card.addEventListener('dragstart', drag);
        });

        // Make all columns droppable
        const columns = document.querySelectorAll('[data-status]');
        columns.forEach(column => {
            column.addEventListener('dragover', allowDrop);
            column.addEventListener('drop', drop);
        });
    });
</script>
@endsection
