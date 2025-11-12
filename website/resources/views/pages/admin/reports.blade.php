@extends('layouts.dashboard')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('title', 'Laporan & Analytics')
@section('page-title', 'LAPORAN & ANALYTICS')
@section('page-subtitle', 'Analisis performa dan insight proyek')

@section('content')
    <div class="space-y-6">
        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('admin.reports') }}" id="reportFilterForm">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between space-y-4 lg:space-y-0 lg:space-x-6">
                    <!-- Date Range Filters -->
                    <div class="flex flex-col sm:flex-row sm:items-end space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="min-w-0">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            >
                        </div>

                        <div class="min-w-0">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                value="{{ request('end_date', now()->format('Y-m-d')) }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            >
                        </div>

                        <div class="min-w-0">
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Filter Proyek</label>
                            <select
                                name="project_id"
                                id="project_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            >
                                <option value="">Semua Proyek</option>
                                @foreach($projects ?? [] as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end space-x-3">
                        <button
                            type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium flex items-center transition-colors duration-200 shadow-sm hover:shadow-md"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>

                        <button
                            type="button"
                            onclick="resetFilters()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="no-print bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>

                <!-- Period Info -->
                <div class="mt-4 p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-emerald-800 font-medium">Periode Laporan:</span>
                        <span class="text-emerald-700 ml-2">
                            {{ \Carbon\Carbon::parse(request('start_date', now()->subMonth()))->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse(request('end_date', now()))->format('d M Y') }}
                        </span>
                        <span class="ml-4 text-emerald-600">•</span>
                        <span class="text-emerald-700 ml-2">Update: {{ now()->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>
            </form>
        </div>
        <!-- Key Metrics -->
        <div class="print-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="keyMetrics"
             data-period="{{ \Carbon\Carbon::parse(request('start_date', now()->subMonth()))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date', now()))->format('d M Y') }}">
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Total Proyek</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalProjects ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1 font-medium">Dalam periode ini</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Proyek Selesai</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $completedProjects ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1 font-medium">
                            {{ $totalProjects > 0 ? round(($completedProjects ?? 0) / $totalProjects * 100, 1) : 0 }}% completion
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Total Task</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalTasks ?? 0 }}</p>
                        <p class="text-xs text-orange-600 mt-1 font-medium">{{ $completedTasks ?? 0 }} selesai</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Tim Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $activeUsers ?? 0 }}</p>
                        <p class="text-xs text-purple-600 mt-1 font-medium">Active members</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Table (Hidden on Screen, Visible on Print) -->
        <div class="print-only hidden">
            <div class="mb-8">
                <!-- Header -->
                <div class="text-center mb-6 pb-4 border-b-2 border-gray-800">
                    <h1 class="text-2xl font-bold mb-2">LAPORAN MANAJEMEN PROYEK</h1>
                    <p class="text-lg font-semibold">
                        Periode: {{ $dateRange['start'] ?? '' }} s/d {{ $dateRange['end'] ?? '' }}
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        Dicetak: {{ now()->format('d F Y, H:i') }} WIB
                    </p>
                </div>

                <!-- Executive Summary -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 bg-gray-200 px-3 py-2">RINGKASAN EKSEKUTIF</h3>
                    <table class="w-full border-collapse border border-gray-400 mb-6">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-400 px-4 py-2 text-left font-bold">Metrik</th>
                                <th class="border border-gray-400 px-4 py-2 text-center font-bold">Jumlah</th>
                                <th class="border border-gray-400 px-4 py-2 text-left font-bold">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2 font-medium">Total Proyek</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $totalProjects ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2">Dalam periode laporan</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2 font-medium">Proyek Selesai</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold text-green-700">{{ $completedProjects ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2">{{ $totalProjects > 0 ? round(($completedProjects ?? 0) / $totalProjects * 100, 1) : 0 }}% tingkat penyelesaian</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2 font-medium">Total Task</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $totalTasks ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2">{{ $completedTasks ?? 0 }} task selesai ({{ $overallCompletionRate ?? 0 }}%)</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2 font-medium">Task Overdue</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold text-red-700">{{ $overdueTasks ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2">Task melewati deadline</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2 font-medium">Rata-rata Penyelesaian</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $avgCompletionDays ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2">Hari per task</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2 font-medium">Tim Aktif</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $activeUsers ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2">Anggota tim yang berkontribusi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Task Status Breakdown -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 bg-gray-200 px-3 py-2">BREAKDOWN STATUS TASK</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <table class="w-full border-collapse border border-gray-400">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-4 py-2 text-left font-bold">Status</th>
                                    <th class="border border-gray-400 px-4 py-2 text-center font-bold">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2">To Do</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $tasksByStatus['todo'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2">In Progress</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $tasksByStatus['in_progress'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2">Review</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $tasksByStatus['review'] ?? 0 }}</td>
                                </tr>
                                <tr class="bg-green-50">
                                    <td class="border border-gray-400 px-4 py-2 font-bold">Done</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold text-green-700">{{ $tasksByStatus['done'] ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="w-full border-collapse border border-gray-400">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-4 py-2 text-left font-bold">Prioritas</th>
                                    <th class="border border-gray-400 px-4 py-2 text-center font-bold">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2">Low</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $tasksByPriority['low'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2">Medium</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $tasksByPriority['medium'] ?? 0 }}</td>
                                </tr>
                                <tr class="bg-red-50">
                                    <td class="border border-gray-400 px-4 py-2 font-bold">High</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold text-red-700">{{ $tasksByPriority['high'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2 font-bold">Total</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $totalTasks ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Project Status Breakdown -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 bg-gray-200 px-3 py-2">BREAKDOWN STATUS PROYEK</h3>
                    <table class="w-full border-collapse border border-gray-400">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-400 px-4 py-2 text-left font-bold">Status Proyek</th>
                                <th class="border border-gray-400 px-4 py-2 text-center font-bold">Jumlah</th>
                                <th class="border border-gray-400 px-4 py-2 text-center font-bold">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2">Planning</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $projectsByStatus['planning'] ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2 text-center">
                                    {{ $totalProjects > 0 ? round((($projectsByStatus['planning'] ?? 0) / $totalProjects) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-400 px-4 py-2">In Progress</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold">{{ $projectsByStatus['in_progress'] ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2 text-center">
                                    {{ $totalProjects > 0 ? round((($projectsByStatus['in_progress'] ?? 0) / $totalProjects) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            <tr class="bg-green-50">
                                <td class="border border-gray-400 px-4 py-2 font-bold">Completed</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold text-green-700">{{ $projectsByStatus['completed'] ?? 0 }}</td>
                                <td class="border border-gray-400 px-4 py-2 text-center font-bold text-green-700">
                                    {{ $totalProjects > 0 ? round((($projectsByStatus['completed'] ?? 0) / $totalProjects) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Project Details Table -->
                <div class="mb-8" style="page-break-before: always;">
                    <h3 class="text-lg font-bold mb-4 bg-gray-200 px-3 py-2">RINGKASAN PROYEK</h3>
                    <table class="w-full border-collapse border border-gray-400">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-400 px-3 py-2 text-left font-bold">No</th>
                                <th class="border border-gray-400 px-3 py-2 text-left font-bold">Nama Proyek</th>
                                <th class="border border-gray-400 px-3 py-2 text-center font-bold">Status</th>
                                <th class="border border-gray-400 px-3 py-2 text-center font-bold">Progress</th>
                                <th class="border border-gray-400 px-3 py-2 text-left font-bold">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportProjects ?? [] as $index => $project)
                                <tr>
                                    <td class="border border-gray-400 px-3 py-2 text-center">{{ $index + 1 }}</td>
                                    <td class="border border-gray-400 px-3 py-2 font-medium">{{ $project->project_name }}</td>
                                    <td class="border border-gray-400 px-3 py-2 text-center">
                                        {{ $project->status == 'completed' ? 'Selesai' :
                                           ($project->status == 'in_progress' ? 'Progress' : 'Planning') }}
                                    </td>
                                    <td class="border border-gray-400 px-3 py-2 text-center font-bold">{{ $project->progress ?? 0 }}%</td>
                                    <td class="border border-gray-400 px-3 py-2 text-sm">{{ Str::limit($project->description, 100) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="border border-gray-400 px-4 py-8 text-center text-gray-500">
                                        Tidak ada data proyek dalam periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Project Management Report -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 bg-gray-200 px-3 py-2">DETAIL MANAJEMEN PROYEK</h3>
                    @forelse($reportProjects ?? [] as $index => $project)
                        <div class="mb-6 border border-gray-400">
                            <!-- Project Header -->
                            <div class="bg-gray-100 px-4 py-3 border-b border-gray-400">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-base font-bold">{{ $index + 1 }}. {{ $project->project_name }}</h4>
                                    <span class="px-3 py-1 bg-white border border-gray-400 text-sm font-semibold">
                                        {{ $project->status == 'completed' ? 'SELESAI' : ($project->status == 'in_progress' ? 'BERJALAN' : 'PERENCANAAN') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Project Details -->
                            <div class="p-4">
                                <table class="w-full border-collapse mb-3">
                                    <tr>
                                        <td class="py-1 text-sm font-semibold" style="width: 150px;">Leader Proyek:</td>
                                        <td class="py-1 text-sm">{{ $project->user->name ?? 'N/A' }} ({{ $project->user->email ?? 'N/A' }})</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-sm font-semibold">Tanggal Mulai:</td>
                                        <td class="py-1 text-sm">{{ $project->created_at ? \Carbon\Carbon::parse($project->created_at)->format('d F Y') : 'Belum ditentukan' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-sm font-semibold">Target Selesai:</td>
                                        <td class="py-1 text-sm">{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d F Y') : 'Belum ditentukan' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-sm font-semibold">Progress:</td>
                                        <td class="py-1 text-sm font-bold">{{ $project->progress ?? 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-sm font-semibold">Deskripsi:</td>
                                        <td class="py-1 text-sm">{{ $project->description ?? '-' }}</td>
                                    </tr>
                                </table>

                                <!-- Team Members & Tasks -->
                                @php
                                    $projectTasks = collect();
                                    $projectMembers = collect();
                                    foreach ($project->boards as $board) {
                                        foreach ($board->cards as $card) {
                                            $projectTasks->push($card);
                                            if ($card->user) {
                                                $projectMembers->push($card->user);
                                            }
                                            foreach ($card->assignments as $assignment) {
                                                if ($assignment->user) {
                                                    $projectMembers->push($assignment->user);
                                                }
                                            }
                                        }
                                    }
                                    $projectMembers = $projectMembers->unique('id');

                                    // Calculate time spent
                                    $totalTimeMinutes = 0;
                                    foreach ($projectTasks as $task) {
                                        $timeLogs = \App\Models\Time_Log::where('card_id', $task->id)->get();
                                        foreach ($timeLogs as $log) {
                                            if ($log->end_time) {
                                                $totalTimeMinutes += $log->duration_minutes ?? 0;
                                            } else {
                                                $currentDuration = abs($log->start_time->diffInMinutes(now(), false));
                                                $totalTimeMinutes += ($log->duration_minutes ?? 0) + $currentDuration;
                                            }
                                        }
                                    }
                                    $totalHours = floor($totalTimeMinutes / 60);
                                    $totalMinutes = $totalTimeMinutes % 60;
                                @endphp

                                <!-- Team Performance Table -->
                                <div class="mt-3">
                                    <h5 class="text-sm font-bold mb-2 bg-gray-50 px-2 py-1 border-t border-b border-gray-300">TIM PROYEK ({{ $projectMembers->count() }} Orang)</h5>
                                    <table class="w-full border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="border border-gray-300 px-2 py-1 text-left">No</th>
                                                <th class="border border-gray-300 px-2 py-1 text-left">Nama</th>
                                                <th class="border border-gray-300 px-2 py-1 text-center">Total Task</th>
                                                <th class="border border-gray-300 px-2 py-1 text-center">Selesai</th>
                                                <th class="border border-gray-300 px-2 py-1 text-center">Progress</th>
                                                <th class="border border-gray-300 px-2 py-1 text-center">Waktu Kerja</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($projectMembers as $idx => $member)
                                                @php
                                                    $memberTasks = $projectTasks->filter(function($task) use ($member) {
                                                        return $task->user_id == $member->id ||
                                                               $task->assignments->where('user_id', $member->id)->isNotEmpty();
                                                    });
                                                    $memberCompleted = $memberTasks->where('status', 'done')->count();
                                                    $memberInProgress = $memberTasks->where('status', 'in_progress')->count();

                                                    // Calculate member work time
                                                    $memberTimeMinutes = 0;
                                                    foreach ($memberTasks as $task) {
                                                        $timeLogs = \App\Models\Time_Log::where('card_id', $task->id)
                                                                                        ->where('user_id', $member->id)
                                                                                        ->get();
                                                        foreach ($timeLogs as $log) {
                                                            if ($log->end_time) {
                                                                $memberTimeMinutes += $log->duration_minutes ?? 0;
                                                            } else {
                                                                $currentDuration = abs($log->start_time->diffInMinutes(now(), false));
                                                                $memberTimeMinutes += ($log->duration_minutes ?? 0) + $currentDuration;
                                                            }
                                                        }
                                                    }
                                                    $memberHours = floor($memberTimeMinutes / 60);
                                                    $memberMinutes = $memberTimeMinutes % 60;
                                                @endphp
                                                <tr>
                                                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $idx + 1 }}</td>
                                                    <td class="border border-gray-300 px-2 py-1">{{ $member->name }}</td>
                                                    <td class="border border-gray-300 px-2 py-1 text-center font-semibold">{{ $memberTasks->count() }}</td>
                                                    <td class="border border-gray-300 px-2 py-1 text-center font-semibold">{{ $memberCompleted }}</td>
                                                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $memberInProgress }}</td>
                                                    <td class="border border-gray-300 px-2 py-1 text-center font-semibold">{{ $memberHours }}h {{ $memberMinutes }}m</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="border border-gray-300 px-2 py-2 text-center text-gray-500">
                                                        Belum ada anggota tim
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Project Summary -->
                                <div class="mt-3 grid grid-cols-3 gap-2 text-sm">
                                    <div class="border border-gray-300 px-3 py-2">
                                        <div class="font-semibold">Total Task:</div>
                                        <div class="text-lg font-bold">{{ $projectTasks->count() }}</div>
                                    </div>
                                    <div class="border border-gray-300 px-3 py-2">
                                        <div class="font-semibold">Task Selesai:</div>
                                        <div class="text-lg font-bold text-green-700">{{ $projectTasks->where('status', 'done')->count() }}</div>
                                    </div>
                                    <div class="border border-gray-300 px-3 py-2">
                                        <div class="font-semibold">Total Waktu:</div>
                                        <div class="text-lg font-bold">{{ $totalHours }}h {{ $totalMinutes }}m</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="border border-gray-400 px-4 py-8 text-center text-gray-500">
                            Tidak ada data proyek dalam periode ini
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-4 border-t-2 border-gray-300 text-sm text-gray-600">
                    <p class="text-center">
                        <strong>Laporan ini digenerate secara otomatis oleh Sistem Manajemen Proyek</strong><br>
                        Tanggal cetak: {{ now()->format('d F Y, H:i:s') }} WIB
                    </p>
                </div>
            </div>
        </div>

        <!-- Detailed Reports -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Project Summary -->
            <div class="print-section bg-white rounded-xl p-6 border border-gray-200 shadow-sm" id="projectSummary">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan Proyek</h3>
                    <span class="text-sm text-gray-500">{{ count($reportProjects ?? []) }} proyek</span>
                </div>

                <div class="space-y-4">
                    @forelse($reportProjects ?? [] as $project)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3
                                    {{ $project->status == 'completed' ? 'bg-green-500' :
                                       ($project->status == 'in_progress' ? 'bg-yellow-500' : 'bg-green-500') }}">
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $project->project_name }}</p>
                                    <p class="text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mb-1
                                    {{ $project->status == 'completed' ? 'bg-green-100 text-green-800' :
                                       ($project->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                                <p class="text-sm font-medium text-gray-900">{{ $project->progress ?? 0 }}%</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Tidak ada data proyek dalam periode ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Team Performance -->
            <div class="no-print bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Performa Tim</h3>
                    <span class="text-sm text-gray-500">Top performers</span>
                </div>

                <div class="space-y-4">
                    @forelse($topPerformers ?? [] as $user)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white font-medium text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ ucfirst($user->role) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">{{ $user->task_count ?? 0 }} tasks</p>
                                <p class="text-sm font-medium
                                    {{ ($user->completion_rate ?? 0) >= 80 ? 'text-green-600' :
                                       (($user->completion_rate ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $user->completion_rate ?? 0 }}% complete
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p>Tidak ada data performa dalam periode ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Detailed Task Table -->
        <div class="no-print bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Task</h3>
                    <span class="text-sm text-gray-500">{{ count($reportTasks ?? []) }} tasks dalam periode</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reportTasks ?? [] as $task)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($task->description, 60) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $task->board->project->project_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->assignedUsers && $task->assignedUsers->count() > 0)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                                <span class="text-xs font-medium text-gray-600">
                                                    {{ strtoupper(substr($task->assignedUsers->first()->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-900">{{ $task->assignedUsers->first()->name }}</div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $task->status == 'completed' ? 'bg-green-100 text-green-800' :
                                           ($task->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500">Tidak ada data task dalam periode ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript Functions -->
    <script>
        function resetFilters() {
            document.getElementById('start_date').value = '{{ now()->subMonth()->format('Y-m-d') }}';
            document.getElementById('end_date').value = '{{ now()->format('Y-m-d') }}';
            document.getElementById('project_id').value = '';
            document.getElementById('reportFilterForm').submit();
        }

        function exportToExcel() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const projectId = document.getElementById('project_id').value;

            const params = new URLSearchParams();
            params.append('start_date', startDate);
            params.append('end_date', endDate);
            if (projectId) params.append('project_id', projectId);
            params.append('export', 'excel');

            window.location.href = '{{ route("admin.reports") }}?' + params.toString();
        }
    </script>

    <!-- Print Styles -->
    <style>
        /* Hide print-only content on screen */
        .print-only {
            display: none;
        }

        @media print {
            /* Force hide all potential sidebar elements */
            * {
                box-sizing: border-box !important;
            }

            /* Hide all non-print elements */
            .no-print {
                display: none !important;
            }

            /* Hide sidebar completely - target ALL possible selectors */
            .fixed,
            #sidebar,
            .z-50,
            .bg-emerald-900,
            aside,
            nav:not(.print-nav),
            div[class*="fixed"],
            div[class*="sidebar"],
            div[class*="emerald-900"],
            .inset-y-0,
            [class*="inset-y-0"],
            [class*="left-0"],
            [class*="w-64"] {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
                position: static !important;
            }

            /* Hide cards on print */
            .print-hide-cards,
            .grid.grid-cols-1,
            .bg-white.rounded-xl {
                display: none !important;
            }

            /* Show print table */
            .print-only {
                display: block !important;
                visibility: visible !important;
            }

            /* Also hide any div that might be sidebar */
            div.fixed.inset-y-0.left-0 {
                display: none !important;
            }

            /* Hide header */
            header {
                display: none !important;
            }

            /* Reset layout for print - remove sidebar padding */
            .lg\\:pl-64 {
                padding-left: 0 !important;
            }

            .min-h-screen {
                margin: 0 !important;
                padding: 0 !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                font-size: 11px;
                margin: 0 !important;
                padding: 0 !important;
                line-height: 1.4;
            }

            /* Table styling for print */
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 15px;
                font-size: 10px;
            }

            th, td {
                border: 1px solid #444;
                padding: 6px 8px;
                text-align: left;
                vertical-align: top;
            }

            th {
                background-color: #f0f0f0;
                font-weight: bold;
                font-size: 10px;
            }

            /* Spacing adjustments */
            .mb-8 {
                margin-bottom: 15px !important;
            }

            .mb-6 {
                margin-bottom: 10px !important;
            }

            .mb-4 {
                margin-bottom: 8px !important;
            }

            .mb-3 {
                margin-bottom: 6px !important;
            }

            .mt-8 {
                margin-top: 15px !important;
            }

            .mt-4 {
                margin-top: 8px !important;
            }

            .mt-3 {
                margin-top: 6px !important;
            }

            .py-3, .py-2, .py-1 {
                padding-top: 4px !important;
                padding-bottom: 4px !important;
            }

            .px-4, .px-3, .px-2 {
                padding-left: 6px !important;
                padding-right: 6px !important;
            }

            /* Section headers */
            h3 {
                font-size: 13px !important;
                font-weight: bold !important;
                margin-bottom: 8px !important;
                padding: 6px 8px !important;
            }

            h4 {
                font-size: 11px !important;
                font-weight: bold !important;
            }

            h5 {
                font-size: 10px !important;
                font-weight: bold !important;
            }

            /* Hide everything except print sections ONLY when printing */
            .space-y-6 > div:not(.print-section):not(#keyMetrics):not([class*="print-section"]):not(.print-only) {
                display: none !important;
            }

            /* Show only the sections we want when printing */
            .print-section,
            #keyMetrics,
            #projectSummary {
                display: block !important;
                page-break-inside: avoid;
            }

            /* Ensure proper layout for print */
            .grid {
                display: grid !important;
            }

            .bg-white {
                background: white !important;
            }

            .shadow-sm, .shadow-md {
                box-shadow: none !important;
            }

            .border {
                border: 1px solid #ddd !important;
            }

            .rounded-xl, .rounded-lg {
                border-radius: 0 !important;
            }

            .space-y-6 > * + * {
                margin-top: 12px !important;
            }

            /* Grid spacing for print */
            .grid-cols-2 {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
            }

            .grid-cols-3 {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 6px !important;
            }

            /* Print header */
            @page {
                margin: 1.2cm;
                size: A4;
            }

            /* Prevent page breaks inside important sections */
            .border.border-gray-400 {
                page-break-inside: avoid;
                margin-bottom: 10px;
            }

            /* Compact text */
            .text-sm {
                font-size: 9px !important;
            }

            .text-xs {
                font-size: 8px !important;
            }

            .text-lg {
                font-size: 11px !important;
            }

            .text-base {
                font-size: 10px !important;
            }

            /* Header kompak */
            .text-center.mb-6 {
                margin-bottom: 12px !important;
            }

            .text-2xl {
                font-size: 16px !important;
            }
        }
    </style>
@endsection
