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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center mb-4 sm:mb-6">
            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">PROJECT OVERVIEW</h2>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-6 mb-8">
            <!-- Total Proyek -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border">
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalProjects }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Total Projects</div>
                </div>
            </div>

            <!-- Tugas Selesai -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border">
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $completedTasks }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Completed Tasks</div>
                </div>
            </div>

            <!-- Tugas Aktif -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border">
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $activeTasks }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Active Tasks</div>
                </div>
            </div>

            <!-- Tugas Terlambat -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border">
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-red-600">{{ $overdueTasks }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Overdue Tasks</div>
                </div>
            </div>

            <!-- Anggota Aktif -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border col-span-2 sm:col-span-1">
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalUsers }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Total Users</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Statistics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Task Completion Trend -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Task Completion Trend (Last 7 Days)</h2>
            </div>
            <div class="h-64">
                <canvas id="taskCompletionChart"></canvas>
            </div>
        </div>

        <!-- Task Status Distribution -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Task Status Distribution</h2>
            </div>
            <div class="h-64">
                <canvas id="taskStatusChart"></canvas>
            </div>
        </div>

        <!-- Team Productivity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Top Performers (Completed Tasks)</h2>
            </div>
            <div class="h-64">
                <canvas id="teamProductivityChart"></canvas>
            </div>
        </div>

        <!-- Project Status Overview -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Project Status Overview</h2>
            </div>
            <div class="h-64">
                <canvas id="projectStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center mb-4 sm:mb-6">
            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">RECENT PROJECTS</h2>
        </div>

        <div class="space-y-4">
            @forelse($recentProjects as $project)
            <!-- Project Item -->
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                <div class="flex flex-col space-y-3">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0 mr-2">
                            <h3 class="font-medium text-sm sm:text-base text-gray-900 break-words">
                                <a href="{{ route('admin.projects.show', $project['slug']) }}" class="hover:text-green-600">
                                    {{ $project['project_name'] }}
                                </a>
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">By {{ $project['creator'] }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0 text-xs sm:text-sm">
                        <div class="flex items-center">
                            <span class="text-gray-600">Progress: </span>
                            <span class="ml-1 font-medium {{ $project['progress'] > 75 ? 'text-green-600' : ($project['progress'] > 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $project['progress'] }}%
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600">Deadline: </span>
                            <span class="ml-1 font-medium text-gray-900 break-words">
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center mb-4 sm:mb-6">
            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">TEAM MEMBERS</h2>
        </div>

        <div class="space-y-4">
            @forelse($teamMembers as $member)
            <!-- Team Member -->
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $member['role'] === 'Admin' ? 'bg-green-600' : ($member['role'] === 'Leader' ? 'bg-green-600' : 'bg-pink-600') }} rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <span class="text-white font-medium text-sm">{{ $member['avatar'] }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-medium text-sm sm:text-base text-gray-900 break-words">{{ $member['name'] }} <span class="text-gray-500">({{ $member['role'] }})</span></h3>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0 text-xs sm:text-sm pl-13 sm:pl-0">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Task Completion Trend Chart (Line Chart)
    const taskCompletionCtx = document.getElementById('taskCompletionChart').getContext('2d');
    new Chart(taskCompletionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($completionTrendLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
            datasets: [{
                label: 'Tasks Completed',
                data: {!! json_encode($completionTrendData ?? [12, 19, 15, 25, 22, 30, 28]) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    // Task Status Distribution (Doughnut Chart)
    const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
    new Chart(taskStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'In Progress', 'Pending', 'Overdue'],
            datasets: [{
                data: {!! json_encode($taskStatusData ?? [45, 30, 15, 10]) !!},
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(251, 191, 36)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Team Productivity Chart (Horizontal Bar Chart)
    const teamProductivityCtx = document.getElementById('teamProductivityChart').getContext('2d');
    new Chart(teamProductivityCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topPerformersNames ?? ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Williams', 'Tom Brown']) !!},
            datasets: [{
                label: 'Completed Tasks',
                data: {!! json_encode($topPerformersData ?? [35, 28, 25, 22, 18]) !!},
                backgroundColor: 'rgb(16, 185, 129)',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    // Project Status Overview (Bar Chart)
    const projectStatusCtx = document.getElementById('projectStatusChart').getContext('2d');
    new Chart(projectStatusCtx, {
        type: 'bar',
        data: {
            labels: ['Completed', 'In Progress', 'Pending', 'On Hold'],
            datasets: [{
                label: 'Projects',
                data: {!! json_encode($projectStatusData ?? [8, 15, 5, 2]) !!},
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(251, 191, 36)',
                    'rgb(107, 114, 128)'
                ],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 2
                    }
                }
            }
        }
    });
</script>
@endpush
