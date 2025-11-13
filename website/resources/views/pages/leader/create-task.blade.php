@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-leader')
@endsection

@section('title', 'Create Task')
@section('page-title', 'CREATE TASK')
@section('page-subtitle', 'Add new task to project')

@section('content')
<div class="space-y-6">
    <!-- Back Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('leader.projects.board', $project->id) }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Create New Task</h1>
                <p class="text-gray-500 text-sm">{{ $project->project_name }}</p>
            </div>
        </div>
    </div>

    <!-- Task Creation Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="{{ route('leader.projects.store-task') }}" method="POST" id="createTaskForm">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="board_id" value="{{ $board->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Task Title -->
                    <div class="lg:col-span-2">
                        <label for="card_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Task Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="card_title"
                               id="card_title"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('card_title') border-red-500 @enderror"
                               placeholder="Enter task title"
                               value="{{ old('card_title') }}">
                        @error('card_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Describe the task in detail">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assign To -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Assign To <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id"
                                id="user_id"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-500 @enderror">
                            <option value="">Select team member</option>

                            @if(isset($projectUsers) && $projectUsers->count() > 0)
                                @foreach($projectUsers as $userData)
                                    <option value="{{ $userData['user']->id }}"
                                            {{ old('user_id') == $userData['user']->id ? 'selected' : '' }}
                                            @if($userData['is_working']) disabled class="text-gray-400" @endif>
                                        {{ $userData['user']->name }}
                                        @if($userData['is_working'])
                                            (work)
                                        @else
                                            ({{ ucfirst($userData['role']) }})
                                        @endif
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No project members found</option>
                            @endif
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Users with (work) status are currently working on a task</p>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority"
                                id="priority"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                🟢 Low Priority
                            </option>
                            <option value="medium" {{ old('priority') == 'medium' || old('priority') == '' ? 'selected' : '' }}>
                                🟡 Medium Priority
                            </option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                🔴 High Priority
                            </option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Initial Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                                id="status"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="todo" {{ (request('status') == 'todo' || old('status') == 'todo' || (!request('status') && !old('status'))) ? 'selected' : '' }}>
                                📋 To Do
                            </option>
                            <option value="in_progress" {{ (request('status') == 'in_progress' || old('status') == 'in_progress') ? 'selected' : '' }}>
                                🔄 In Progress
                            </option>
                            <option value="review" {{ (request('status') == 'review' || old('status') == 'review') ? 'selected' : '' }}>
                                👀 Review
                            </option>
                            <option value="done" {{ (request('status') == 'done' || old('status') == 'done') ? 'selected' : '' }}>
                                ✅ Done
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Due Date
                        </label>
                        <input type="date"
                               name="due_date"
                               id="due_date"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('due_date') border-red-500 @enderror"
                               value="{{ old('due_date') }}">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estimated Hours -->
                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            Estimated Hours
                        </label>
                        <input type="number"
                               name="estimated_hours"
                               id="estimated_hours"
                               step="0.5"
                               min="0"
                               max="999.99"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estimated_hours') border-red-500 @enderror"
                               placeholder="e.g., 8.5"
                               value="{{ old('estimated_hours') }}">
                        <p class="mt-1 text-sm text-gray-500">Enter estimated hours to complete this task</p>
                        @error('estimated_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-green-900 mb-1">Task Information</h4>
                                    <ul class="text-sm text-green-800 space-y-1">
                                        <li>• Task will be automatically positioned in the selected status column</li>
                                        <li>• Due date helps track project deadlines and priorities</li>
                                        <li>• Estimated hours help with project planning and resource allocation</li>
                                        <li>• Tasks can be moved between columns using drag and drop on the board</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('leader.projects.board', $project->id) }}"
                       class="w-full sm:w-auto px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors text-center">
                        Cancel
                    </a>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 sm:flex-1 sm:justify-end">
                        <button type="submit"
                                name="action"
                                value="create_and_continue"
                                class="w-full sm:w-auto px-6 py-3 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            Create & Add Another
                        </button>

                        <button type="submit"
                                name="action"
                                value="create_and_view"
                                class="w-full sm:w-auto px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                            Create Task
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div id="successMessage" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div id="errorMessage" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <div>
                <p class="font-semibold">Please correct the following errors:</p>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
@endsection
