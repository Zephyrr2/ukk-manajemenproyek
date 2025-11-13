@extends('layouts.admin')

@section('title', 'Add Task - ' . $project->project_name)
@section('page-title', 'Add New Task')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.projects.board', $project->slug) }}"
                        class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Board</span>
                    </a>
                    <div class="w-px h-6 bg-gray-300"></div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Add New Task</h1>
                        <p class="text-gray-500">{{ $project->project_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="p-6">
                    <form action="{{ route('admin.projects.tasks.store', $project->slug) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Task Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                   placeholder="Enter task title"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Enter task description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority and Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select id="priority"
                                        name="priority"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror"
                                        required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' || old('priority') == '' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status"
                                        name="status"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                        required>
                                    <option value="">Select Status</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' || old('status') == '' ? 'selected' : '' }}>In Progress</option>
                                    <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>Review</option>
                                    <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Assignee and Due Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="assignee" class="block text-sm font-medium text-gray-700 mb-2">
                                    Assignee
                                </label>
                                <select id="assignee"
                                        name="assignee"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('assignee') border-red-500 @enderror">
                                    <option value="">Select Assignee</option>

                                    @if(isset($projectUsers) && $projectUsers->count() > 0)
                                        @foreach($projectUsers as $userData)
                                            <option value="{{ $userData['user']->id }}"
                                                    {{ old('assignee') == $userData['user']->id ? 'selected' : '' }}
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
                                @error('assignee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Users with (work) status are currently working on a task</p>
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date
                                </label>
                                <input type="date"
                                       id="due_date"
                                       name="due_date"
                                       value="{{ old('due_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('due_date') border-red-500 @enderror">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Labels/Tags -->
                        <div>
                            <label for="labels" class="block text-sm font-medium text-gray-700 mb-2">
                                Labels/Tags
                            </label>
                            <input type="text"
                                   id="labels"
                                   name="labels"
                                   value="{{ old('labels') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('labels') border-red-500 @enderror"
                                   placeholder="Enter labels separated by comma (e.g., frontend, urgent, bug)">
                            @error('labels')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Separate multiple labels with commas</p>
                        </div>

                        <!-- Estimated Hours -->
                        <div>
                            <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Estimated Hours
                            </label>
                            <input type="number"
                                   id="estimated_hours"
                                   name="estimated_hours"
                                   value="{{ old('estimated_hours') }}"
                                   min="0"
                                   step="0.5"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estimated_hours') border-red-500 @enderror"
                                   placeholder="Enter estimated hours">
                            @error('estimated_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.projects.board', $project->slug) }}"
                               class="text-gray-600 hover:text-gray-800 font-medium">
                                Cancel
                            </a>

                            <div class="flex space-x-3">
                                <button type="button"
                                        onclick="saveAsDraft()"
                                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                                    Save as Draft
                                </button>
                                <button type="submit"
                                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                                    Create Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function saveAsDraft() {
        // Add draft functionality here
        alert('Save as draft functionality would be implemented here!');
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const priority = document.getElementById('priority').value;
        const status = document.getElementById('status').value;

        if (!title) {
            e.preventDefault();
            alert('Please enter a task title');
            document.getElementById('title').focus();
            return;
        }

        if (!priority) {
            e.preventDefault();
            alert('Please select a priority');
            document.getElementById('priority').focus();
            return;
        }

        if (!status) {
            e.preventDefault();
            alert('Please select a status');
            document.getElementById('status').focus();
            return;
        }
    });
</script>
@endsection
