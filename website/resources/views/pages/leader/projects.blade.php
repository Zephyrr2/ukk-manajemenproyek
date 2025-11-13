@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-leader')
@endsection

@section('title', 'My Projects - Team Leader')
@section('page-title', 'MY PROJECTS')
@section('page-subtitle', 'Kelola dan pantau semua proyek Anda')

@section('content')
<div class="space-y-6">
    <!-- Project Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Active Projects</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalActiveProjects }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Completed Tasks</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalCompletedTasks }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Pending Tasks</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalPendingTasks }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="p-4 sm:p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Team Members</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalTeamMembers }}</dd>
                        </dl>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Your Projects</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage and monitor all your projects</p>
                </div>
            </div>
        </div>

        @if($userProjects->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($userProjects as $project)
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start space-x-3">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-green-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 break-words">
                                            {{ $project->project_name }}
                                        </h4>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                            Created by {{ $project->user->name }} • {{ $project->team_members_count }} team members
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-3 ml-0 sm:ml-13">
                                    <div class="flex flex-wrap items-center text-xs sm:text-sm text-gray-600 gap-2 sm:gap-4">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span>{{ $project->total_tasks }} tasks</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $project->completed_tasks }} completed</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $project->in_progress_tasks }} in progress</span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-500">Progress</span>
                                            <span class="font-medium text-green-600">{{ $project->progress_percentage }}%</span>
                                        </div>
                                        <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 lg:ml-4 lg:flex-shrink-0">
                                <a href="{{ route('leader.projects.show', $project->id) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    View Details
                                </a>
                                <a href="{{ route('leader.projects.board', $project->id) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    Open Board
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
            </div>
        @endif
    </div>
</div>
@endsection


