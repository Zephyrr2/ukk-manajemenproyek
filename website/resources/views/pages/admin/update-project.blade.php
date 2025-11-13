@extends('layouts.admin')

@section('title', 'Update Project')
@section('page-title', 'Update Project')

@section('content')
<div class="py-6">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('admin.projects') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Projects</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Create Project</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Content -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-white">Update Project</h2>
                            <p class="text-green-100">Modify the project details and team assignments</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.projects') }}"
                       class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-100 bg-green-500 hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-300 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Projects
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.projects.update', $project->slug) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="team_lead_id" id="team_lead_id" value="{{ old('team_lead_id', $project->user_id) }}">

                <!-- Project Details Card -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Project Details
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Project Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="project_name"
                                   value="{{ old('project_name', $project->project_name) }}"
                                   placeholder="Enter project name"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('project_name') border-red-500 ring-2 ring-red-500 @enderror">
                            @error('project_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Project Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Describe the project objectives, scope, and key requirements"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 ring-2 ring-red-500 @enderror">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date
                                </label>
                                <input type="date"
                                       id="due_date"
                                       name="due_date"
                                       value="{{ old('due_date', $project->deadline) }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('due_date') border-red-500 ring-2 ring-red-500 @enderror">
                                @error('due_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="team_lead_search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Team Leader
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           id="team_lead_search"
                                           name="team_lead_search"
                                           value="{{ old('team_lead_search', $project->user ? $project->user->name : '') }}"
                                           placeholder="Search for available leader..."
                                           autocomplete="off"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('team_lead_id') border-red-500 ring-2 ring-red-500 @enderror">

                                    <!-- Hidden field to store selected leader ID -->
                                    <input type="hidden" id="team_lead_id" name="team_lead_id" value="{{ old('team_lead_id', $project->user_id ?? '') }}">

                                    <!-- Search Results Dropdown -->
                                    <div id="team_lead_results"
                                         class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg overflow-y-scroll"
                                         style="height: 120px;">
                                        <!-- Results will be populated here -->
                                    </div>
                                </div>
                                @error('team_lead_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 mt-8 -mx-6 -mb-6">
                    <div class="flex items-center justify-end">
                        <div class="flex items-center">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105">
                                Update Project
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let searchTimeout;
const searchInput = document.getElementById('team_lead_search');
const resultsContainer = document.getElementById('team_lead_results');
const hiddenInput = document.getElementById('team_lead_id');

searchInput.addEventListener('input', function() {
    const query = this.value.trim();

    // Clear previous timeout
    clearTimeout(searchTimeout);

    if (query.length < 2) {
        resultsContainer.classList.add('hidden');
        hiddenInput.value = '';
        return;
    }

    // Debounce search requests
    searchTimeout = setTimeout(() => {
        searchTeamLeaders(query);
    }, 300);
});

function searchTeamLeaders(query) {
    // Show loading state
    resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Searching...</div>';
    resultsContainer.classList.remove('hidden');

    // Include project_id to allow current leader in results
    const projectId = '{{ $project->id }}';

    // Make request to search endpoint
    fetch(`{{ route('admin.search.leaders') }}?q=${encodeURIComponent(query)}&project_id=${projectId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        displaySearchResults(data);
    })
    .catch(error => {
        console.error('Search error:', error);
        resultsContainer.innerHTML = '<div class="p-3 text-red-500">Error searching leaders</div>';
    });
}

function displaySearchResults(leaders) {
    if (leaders.length === 0) {
        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">No leaders found</div>';
        return;
    }

    let html = '';
    leaders.forEach(leader => {
        const hasProject = leader.has_project;
        const projectName = leader.project_name;
        const isCurrentProject = leader.is_current_project;
        const isDisabled = hasProject && !isCurrentProject; // Disabled if has project and not current
        const bgColor = isDisabled ? 'bg-gray-100' : 'hover:bg-gray-100';
        const cursor = isDisabled ? 'cursor-not-allowed' : 'cursor-pointer';
        const opacity = isDisabled ? 'opacity-60' : '';

        html += `
            <div class="p-2 sm:p-3 ${bgColor} ${cursor} ${opacity} border-b border-gray-100 last:border-b-0"
                 ${!isDisabled ? `onclick="selectLeader(${leader.id}, '${leader.name}')"` : ''}>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 ${isDisabled ? 'bg-gray-400' : (isCurrentProject ? 'bg-blue-500' : 'bg-green-500')} rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                            <span class="text-white text-xs sm:text-sm font-semibold">${leader.name.charAt(0).toUpperCase()}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm sm:text-base font-medium text-gray-900 truncate">${leader.name}</div>
                            <div class="text-xs sm:text-sm text-gray-500 truncate">${leader.email}</div>
                        </div>
                    </div>
                    ${isCurrentProject ? `
                    <div class="ml-10 sm:ml-2 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="mr-1 h-3 w-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Current Leader
                        </span>
                    </div>
                    ` : hasProject ? `
                    <div class="ml-10 sm:ml-2 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <svg class="mr-1 h-3 w-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate max-w-[150px]">Sudah punya: ${projectName}</span>
                        </span>
                    </div>
                    ` : `
                    <div class="ml-10 sm:ml-2 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1 h-3 w-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Available
                        </span>
                    </div>
                    `}
                </div>
            </div>
        `;
    });

    resultsContainer.innerHTML = html;
}

function selectLeader(id, name) {
    searchInput.value = name;
    hiddenInput.value = id;
    resultsContainer.classList.add('hidden');
}

// Hide results when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('#team_lead_search') && !event.target.closest('#team_lead_results')) {
        resultsContainer.classList.add('hidden');
    }
});
</script>

@endsection
