@extends('layouts.master')

@section('title', 'Create Team Assignment')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">Create Team Assignment</h1>
                    <a href="{{ route('leader.assignments.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Assignments
                    </a>
                </div>
            </div>

            <form action="{{ route('leader.assignments.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Project <span class="text-red-500">*</span>
                            </label>
                            <select id="project_id"
                                    name="project_id"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Project</option>
                                <!-- Sample data -->
                                <option value="1">CRM System Development</option>
                                <option value="2">Marketing Website</option>
                            </select>
                        </div>

                        <div>
                            <label for="assignment_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Assignment Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="assignment_title"
                                   name="assignment_title"
                                   placeholder="Enter assignment title"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Assignment Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Describe the assignment requirements and objectives"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="assignment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Assignment Type <span class="text-red-500">*</span>
                                </label>
                                <select id="assignment_type"
                                        name="assignment_type"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Type</option>
                                    <option value="development">Development</option>
                                    <option value="design">Design</option>
                                    <option value="testing">Testing</option>
                                    <option value="documentation">Documentation</option>
                                    <option value="research">Research</option>
                                    <option value="meeting">Meeting/Review</option>
                                    <option value="training">Training</option>
                                </select>
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                <select id="priority"
                                        name="priority"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       id="start_date"
                                       name="start_date"
                                       min="{{ date('Y-m-d') }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       id="due_date"
                                       name="due_date"
                                       min="{{ date('Y-m-d') }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="meeting_schedule" class="block text-sm font-medium text-gray-700 mb-2">Meeting Schedule (if applicable)</label>
                            <input type="datetime-local"
                                   id="meeting_schedule"
                                   name="meeting_schedule"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <div class="bg-green-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Team Assignment</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Assign Team Members <span class="text-red-500">*</span>
                                    </label>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-2">Developers</label>
                                            <div class="space-y-2 max-h-32 overflow-y-auto">
                                                <!-- Sample data -->
                                                <div class="flex items-center">
                                                    <input type="checkbox"
                                                           id="dev_1"
                                                           name="assigned_members[]"
                                                           value="1"
                                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                                    <label for="dev_1" class="ml-3 block text-sm text-gray-700">
                                                        John Smith
                                                        <span class="text-gray-500 block text-xs">john@example.com</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox"
                                                           id="dev_2"
                                                           name="assigned_members[]"
                                                           value="2"
                                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                                    <label for="dev_2" class="ml-3 block text-sm text-gray-700">
                                                        Alice Johnson
                                                        <span class="text-gray-500 block text-xs">alice@example.com</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-600 mb-2">Designers</label>
                                            <div class="space-y-2 max-h-32 overflow-y-auto">
                                                <!-- Sample data -->
                                                <div class="flex items-center">
                                                    <input type="checkbox"
                                                           id="des_1"
                                                           name="assigned_members[]"
                                                           value="3"
                                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                                    <label for="des_1" class="ml-3 block text-sm text-gray-700">
                                                        Sarah Wilson
                                                        <span class="text-gray-500 block text-xs">sarah@example.com</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Assignment Documents</label>
                                    <input type="file"
                                           id="attachment"
                                           name="attachment[]"
                                           multiple
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <p class="mt-2 text-sm text-gray-500">
                                        Requirements, specs, or reference docs<br>
                                        Max: 50MB per file
                                    </p>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select id="status"
                                            name="status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="assigned">Assigned</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="review">Under Review</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 mt-8">
                    <div>
                        <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">Requirements & Deliverables</label>
                        <textarea id="requirements"
                                  name="requirements"
                                  rows="3"
                                  placeholder="List specific requirements and expected deliverables"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="2"
                                  placeholder="Additional instructions or important notes"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-200 pt-6 mt-8">
                    <div class="flex items-center justify-end space-x-4">
                        <button type="reset"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Create Assignment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const dueDateInput = document.getElementById('due_date');
    if (startDate) {
        dueDateInput.min = startDate;
    }
});

// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('start_date').min = today;
document.getElementById('due_date').min = today;
</script>
@endsection
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Team Assignment</h3>
                    <div class="card-tools">
                        <a href="{{ route('leader.assignments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Assignments
                        </a>
                    </div>
                </div>
                <form action="{{ route('leader.assignments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                                    <select class="form-control @error('project_id') is-invalid @enderror"
                                            id="project_id" name="project_id" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="assignment_title" class="form-label">Assignment Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('assignment_title') is-invalid @enderror"
                                           id="assignment_title" name="assignment_title" value="{{ old('assignment_title') }}"
                                           placeholder="Enter assignment title" required>
                                    @error('assignment_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">Assignment Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4"
                                              placeholder="Describe the assignment requirements and objectives">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="assignment_type" class="form-label">Assignment Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('assignment_type') is-invalid @enderror"
                                                    id="assignment_type" name="assignment_type" required>
                                                <option value="">Select Type</option>
                                                <option value="development" {{ old('assignment_type') == 'development' ? 'selected' : '' }}>Development</option>
                                                <option value="design" {{ old('assignment_type') == 'design' ? 'selected' : '' }}>Design</option>
                                                <option value="testing" {{ old('assignment_type') == 'testing' ? 'selected' : '' }}>Testing</option>
                                                <option value="documentation" {{ old('assignment_type') == 'documentation' ? 'selected' : '' }}>Documentation</option>
                                                <option value="research" {{ old('assignment_type') == 'research' ? 'selected' : '' }}>Research</option>
                                                <option value="meeting" {{ old('assignment_type') == 'meeting' ? 'selected' : '' }}>Meeting/Review</option>
                                                <option value="training" {{ old('assignment_type') == 'training' ? 'selected' : '' }}>Training</option>
                                            </select>
                                            @error('assignment_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="priority" class="form-label">Priority</label>
                                            <select class="form-control @error('priority') is-invalid @enderror"
                                                    id="priority" name="priority">
                                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                                   id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}" required>
                                            @error('due_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="meeting_schedule" class="form-label">Meeting Schedule (if applicable)</label>
                                    <input type="datetime-local" class="form-control @error('meeting_schedule') is-invalid @enderror"
                                           id="meeting_schedule" name="meeting_schedule" value="{{ old('meeting_schedule') }}">
                                    @error('meeting_schedule')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Assign Team Members <span class="text-danger">*</span></label>

                                    <div class="mb-3">
                                        <label class="form-label">Developers</label>
                                        @foreach($developers as $developer)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                       name="assigned_members[]" value="{{ $developer->id }}"
                                                       id="dev_{{ $developer->id }}">
                                                <label class="form-check-label" for="dev_{{ $developer->id }}">
                                                    {{ $developer->name }}
                                                    <small class="text-muted">({{ $developer->email }})</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Designers</label>
                                        @foreach($designers as $designer)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                       name="assigned_members[]" value="{{ $designer->id }}"
                                                       id="des_{{ $designer->id }}">
                                                <label class="form-check-label" for="des_{{ $designer->id }}">
                                                    {{ $designer->name }}
                                                    <small class="text-muted">({{ $designer->email }})</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('assigned_members')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="attachment" class="form-label">Assignment Documents</label>
                                    <input type="file" class="form-control-file @error('attachment') is-invalid @enderror"
                                           id="attachment" name="attachment[]" multiple
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                                    <small class="form-text text-muted">
                                        Requirements, specs, or reference docs<br>
                                        Max: 50MB per file
                                    </small>
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status">
                                        <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>Under Review</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="requirements" class="form-label">Requirements & Deliverables</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror"
                                      id="requirements" name="requirements" rows="3"
                                      placeholder="List specific requirements and expected deliverables">{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="2"
                                      placeholder="Additional instructions or important notes">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-tasks"></i> Create Assignment
                                </button>
                                <button type="reset" class="btn btn-secondary ml-2">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const dueDateInput = document.getElementById('due_date');
    if (startDate) {
        dueDateInput.min = startDate;
    }
});

// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('start_date').min = today;
document.getElementById('due_date').min = today;
</script>
@endsection
