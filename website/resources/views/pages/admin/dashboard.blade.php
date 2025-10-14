@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-admin')
@endsection

@section('title', 'Admin Dashboard')
@section('page-title', 'ADMIN DASHBOARD')
@section('page-subtitle', 'Welcome, Admin!')

@section('content')
<div class="space-y-6">
    <!-- Overview Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">PROJECT OVERVIEW</h2>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Proyek -->
            <div class="bg-gray-50 rounded-lg p-4 border">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalProjects }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Projects</div>
                </div>
            </div>

            <!-- Tugas Selesai -->
            <div class="bg-gray-50 rounded-lg p-4 border">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $completedTasks }}</div>
                    <div class="text-sm text-gray-600 mt-1">Completed Tasks</div>
                </div>
            </div>

            <!-- Tugas Aktif -->
            <div class="bg-gray-50 rounded-lg p-4 border">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $activeTasks }}</div>
                    <div class="text-sm text-gray-600 mt-1">Active Tasks</div>
                </div>
            </div>

            <!-- Tugas Terlambat -->
            <div class="bg-gray-50 rounded-lg p-4 border">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $overdueTasks }}</div>
                    <div class="text-sm text-gray-600 mt-1">Overdue Tasks</div>
                </div>
            </div>

            <!-- Anggota Aktif -->
            <div class="bg-gray-50 rounded-lg p-4 border">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Users</div>
                </div>
            </div>
        </div>
    </div>    <!-- Projects Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">RECENT PROJECTS</h2>
        </div>

        <div class="space-y-4">
            @forelse($recentProjects as $project)
            <!-- Project Item -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">
                            <a href="{{ route('admin.projects.show', $project['slug']) }}" class="hover:text-green-600">
                                {{ $project['project_name'] }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">By {{ $project['creator'] }}</p>
                    </div>
                    <div class="flex items-center space-x-6 text-sm">
                        <div class="flex items-center">
                            <span class="text-gray-600">Progress: </span>
                            <span class="ml-1 font-medium {{ $project['progress'] > 75 ? 'text-green-600' : ($project['progress'] > 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $project['progress'] }}%
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600">Deadline: </span>
                            <span class="ml-1 font-medium text-gray-900">
                                {{ $project['deadline'] ? \Carbon\Carbon::parse($project['deadline'])->format('d M Y') : 'Not set' }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Progress Bar -->
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $project['progress'] > 75 ? 'bg-green-500' : ($project['progress'] > 40 ? 'bg-yellow-500' : 'bg-red-500') }}"
                             style="width: {{ $project['progress'] }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <div class="text-gray-500">No recent projects found</div>
                <a href="{{ route('admin.projects.create') }}" class="inline-block mt-2 text-green-600 hover:text-green-800">
                    Create your first project
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Team Members Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">TEAM MEMBERS</h2>
        </div>

        <div class="space-y-4">
            @forelse($teamMembers as $member)
            <!-- Team Member -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $member['role'] === 'Admin' ? 'bg-green-600' : ($member['role'] === 'Leader' ? 'bg-green-600' : 'bg-pink-600') }} rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-medium">{{ $member['avatar'] }}</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $member['name'] }} ({{ $member['role'] }})</h3>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6 text-sm">
                        <div class="text-gray-600">{{ $member['completed_tasks'] }} completed tasks</div>
                        <div class="font-medium {{ $member['productivity'] > 80 ? 'text-green-600' : ($member['productivity'] > 60 ? 'text-yellow-600' : 'text-red-600') }}">
                            Productivity: {{ $member['productivity'] }}%
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <div class="text-gray-500">No active team members found</div>
                <a href="{{ route('admin.users') }}" class="inline-block mt-2 text-green-600 hover:text-green-800">
                    Manage users
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
