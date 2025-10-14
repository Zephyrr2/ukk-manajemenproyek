@extends('layouts.admin')

@section('title', 'Generate Report')
@section('page-title', 'Generate Report')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <a href="{{ route('admin.reports') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Reports</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Generate Report</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Content -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="p-2 bg-amber-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-white">Generate Project Report</h2>
                            <p class="text-amber-100">Create comprehensive project reports and analytics</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-amber-300 rounded-md shadow-sm text-sm font-medium text-amber-100 bg-amber-500 hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-300 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Reports
                    </a>
                </div>
            </div>


            <!-- Form -->
            <form action="{{ route('admin.reports.generate') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Report Type <span class="text-red-500">*</span>
                            </label>
                            <select id="report_type"
                                    name="report_type"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Select Report Type</option>
                                <option value="project_summary">Project Summary</option>
                                <option value="team_performance">Team Performance</option>
                                <option value="financial">Financial Report</option>
                                <option value="progress">Progress Report</option>
                                <option value="task_completion">Task Completion</option>
                            </select>
                        </div>

                        <div>
                            <label for="project_ids" class="block text-sm font-medium text-gray-700 mb-2">Select Projects</label>
                            <select id="project_ids"
                                    name="project_ids[]"
                                    multiple
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 h-32">
                                <!-- Sample data -->
                                <option value="1">E-commerce Platform</option>
                                <option value="2">Mobile Banking App</option>
                                <option value="3">CRM System</option>
                                <option value="4">Website Redesign</option>
                            </select>
                            <p class="mt-2 text-sm text-gray-500">Leave empty to include all projects. Hold Ctrl/Cmd to select multiple.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       id="start_date"
                                       name="start_date"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       id="end_date"
                                       name="end_date"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div class="bg-amber-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Report Configuration</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Report Sections</label>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   id="overview"
                                                   name="sections[]"
                                                   value="overview"
                                                   checked
                                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                            <label for="overview" class="ml-3 block text-sm text-gray-700">Project Overview</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   id="timeline"
                                                   name="sections[]"
                                                   value="timeline"
                                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                            <label for="timeline" class="ml-3 block text-sm text-gray-700">Timeline & Milestones</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   id="budget"
                                                   name="sections[]"
                                                   value="budget"
                                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                            <label for="budget" class="ml-3 block text-sm text-gray-700">Budget Analysis</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   id="team"
                                                   name="sections[]"
                                                   value="team"
                                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                            <label for="team" class="ml-3 block text-sm text-gray-700">Team Performance</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   id="issues"
                                                   name="sections[]"
                                                   value="issues"
                                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                            <label for="issues" class="ml-3 block text-sm text-gray-700">Issues & Risks</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="format" class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                                    <select id="format"
                                            name="format"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                        <option value="csv">CSV</option>
                                    </select>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox"
                                           id="include_charts"
                                           name="include_charts"
                                           value="1"
                                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                    <label for="include_charts" class="ml-3 block text-sm text-gray-700">Include Charts & Graphs</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea id="notes"
                              name="notes"
                              rows="3"
                              placeholder="Additional notes or specific requirements for the report"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"></textarea>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 pt-6 mt-8">
                    <div class="flex items-center justify-end space-x-4">
                        <button type="reset"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Generate Project Report</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Reports
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.reports.generate') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="report_type" class="form-label">Report Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('report_type') is-invalid @enderror"
                                            id="report_type" name="report_type" required>
                                        <option value="">Select Report Type</option>
                                        <option value="project_summary" {{ old('report_type') == 'project_summary' ? 'selected' : '' }}>Project Summary</option>
                                        <option value="team_performance" {{ old('report_type') == 'team_performance' ? 'selected' : '' }}>Team Performance</option>
                                        <option value="financial" {{ old('report_type') == 'financial' ? 'selected' : '' }}>Financial Report</option>
                                        <option value="progress" {{ old('report_type') == 'progress' ? 'selected' : '' }}>Progress Report</option>
                                        <option value="task_completion" {{ old('report_type') == 'task_completion' ? 'selected' : '' }}>Task Completion</option>
                                    </select>
                                    @error('report_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="project_ids" class="form-label">Select Projects</label>
                                    <select class="form-control @error('project_ids') is-invalid @enderror"
                                            id="project_ids" name="project_ids[]" multiple>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Leave empty to include all projects</small>
                                    @error('project_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Report Sections</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="sections[]" value="overview" id="overview" checked>
                                        <label class="form-check-label" for="overview">Project Overview</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="sections[]" value="timeline" id="timeline">
                                        <label class="form-check-label" for="timeline">Timeline & Milestones</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="sections[]" value="budget" id="budget">
                                        <label class="form-check-label" for="budget">Budget Analysis</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="sections[]" value="team" id="team">
                                        <label class="form-check-label" for="team">Team Performance</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="sections[]" value="issues" id="issues">
                                        <label class="form-check-label" for="issues">Issues & Risks</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="format" class="form-label">Export Format</label>
                                    <select class="form-control @error('format') is-invalid @enderror"
                                            id="format" name="format">
                                        <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                        <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>Excel</option>
                                        <option value="csv" {{ old('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                                    </select>
                                    @error('format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="include_charts" class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="include_charts" value="1" id="include_charts">
                                        Include Charts & Graphs
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Additional notes or specific requirements for the report"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('notes') border-red-500 ring-2 ring-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 mt-8 -mx-6 -mb-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span class="text-red-500">*</span> Required fields
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="reset"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset Form
                            </button>
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Preview Report
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Generate Report
                            </button>
                        </div>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>
@endsection
