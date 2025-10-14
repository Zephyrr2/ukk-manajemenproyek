@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-user')
@endsection

@section('title', 'Add Subtask')
@section('page-title', 'ADD SUBTASK')
@section('page-subtitle', 'Tambah subtask untuk: ' . $task->card_title)

@section('content')
<div class="space-y-6">
    <!-- Back Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.subtasks', $task->id) }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Add New Subtask</h1>
                <p class="text-gray-500 text-sm">{{ $task->card_title }}</p>
            </div>
        </div>
    </div>

    <!-- Subtask Creation Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="{{ route('user.subtasks.store', $task->id) }}" method="POST" id="createSubtaskForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Subtask Title -->
                    <div class="lg:col-span-2">
                        <label for="subtask_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Subtask Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="subtask_title"
                               id="subtask_title"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subtask_title') border-red-500 @enderror"
                               placeholder="Enter subtask title"
                               value="{{ old('subtask_title') }}">
                        @error('subtask_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Describe the subtask in detail (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estimated Hours -->
                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">Estimated Hours</label>
                        <input type="number"
                               name="estimated_hours"
                               id="estimated_hours"
                               step="0.1"
                               min="0"
                               max="999.99"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estimated_hours') border-red-500 @enderror"
                               placeholder="0.0"
                               value="{{ old('estimated_hours') }}">
                        @error('estimated_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Leave empty if you don't want to set an estimate</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Initial Status</label>
                        <select name="status"
                                id="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="in_progress" {{ old('status', 'in_progress') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Card -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div>
                                <h4 class="text-sm font-medium text-green-900 mb-1">Subtask Information</h4>
                                <ul class="text-sm text-green-800 space-y-1">
                                    <li>• Subtask will be added to the selected task</li>
                                    <li>• Estimated hours help with workload planning</li>
                                    <li>• You can change the status later from the subtask list</li>
                                    <li>• Subtasks help break down complex tasks into manageable parts</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('user.subtasks', $task->id) }}"
                       class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Cancel
                    </a>

                    <div class="flex items-center space-x-4">
                        <button type="submit"
                                name="action"
                                value="create_and_continue"
                                class="px-6 py-3 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                            Add & Create Another
                        </button>

                        <button type="submit"
                                name="action"
                                value="create_and_view"
                                class="px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                            Add Subtask
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
            <span>Please check the form for errors</span>
        </div>
    </div>
@endif
@endsection
