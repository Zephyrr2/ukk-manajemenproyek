@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-user')
@endsection

@section('title', 'Edit Subtask')
@section('page-title', 'EDIT SUBTASK')
@section('page-subtitle', 'Edit subtask untuk: ' . $task->card_title)

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
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Edit Subtask</h1>
                <p class="text-gray-500 text-sm">{{ $task->card_title }}</p>
            </div>
        </div>
    </div>

    <!-- Subtask Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="{{ route('user.subtasks.update', [$task->id, $subtask->id]) }}" method="POST" id="editSubtaskForm">
                @csrf
                @method('PUT')

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
                               value="{{ old('subtask_title', $subtask->subtask_title) }}">
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
                                  placeholder="Describe the subtask in detail (optional)">{{ old('description', $subtask->description) }}</textarea>
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
                               value="{{ old('estimated_hours', $subtask->estimated_hours) }}">
                        @error('estimated_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Leave empty if you don't want to set an estimate</p>
                    </div>

                    <!-- Current Status Display -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                        <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50">
                            @if($subtask->status === 'done')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    In Progress
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Status can be changed from the subtasks list page</p>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div>
                                <h4 class="text-sm font-medium text-green-900 mb-1">Edit Information</h4>
                                <ul class="text-sm text-green-800 space-y-1">
                                    <li>• Changes will be saved to this subtask</li>
                                    <li>• Status can be toggled from the main subtasks page</li>
                                    <li>• Estimated hours help with workload planning</li>
                                    <li>• All fields except title are optional</li>
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
                                class="px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                            Update Subtask
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
