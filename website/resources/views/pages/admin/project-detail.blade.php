@extends('layouts.admin')

@section('title', 'Project Detail - ' . $project->project_name)
@section('page-title', 'Project Detail')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Back Button and Actions -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.projects') }}"
                            class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Back to Projects</span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.projects.edit', $project->slug) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Project
                        </a>
                        <a href="{{ route('admin.projects.board', $project->slug) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            View Board
                        </a>
                    </div>
                </div>

                <!-- Project Header -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d=" M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $project->project_name }}</h1>
                                        <p class="text-gray-500">Created on {{ $project->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <h3 class="font-medium text-gray-900 mb-2">Description</h3>
                                        <p class="text-gray-700 leading-relaxed">
                                            {{ $project->description ?? 'No description provided.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Details Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Project Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Progress Overview -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Overview</h3>

                                <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-4">
                                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-gray-200 shadow-sm">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Tasks</p>
                                                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalTasks }}</p>
                                            </div>
                                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-gray-200 shadow-sm">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Completed</p>
                                                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $completedTasks }}</p>
                                            </div>
                                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl p-4 sm:p-5 border border-gray-200 shadow-sm mb-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1">In Progress</p>
                                            <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $inProgressTasks }}</p>
                                        </div>
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                        <span>Overall Progress</span>
                                        <span>{{ $progressPercentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-green-600 h-3 rounded-full transition-all duration-300"
                                            style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>

                                <div class="space-y-4">
                                    @forelse($recentActivities as $activity)
                                        <div class="flex items-start space-x-3">
                                            @php
                                                $iconColor = match($activity->status) {
                                                    'completed' => ['bg-green-100', 'text-green-600'],
                                                    'in_progress' => ['bg-yellow-100', 'text-yellow-600'],
                                                    'todo' => ['bg-green-100', 'text-green-600'],
                                                    default => ['bg-gray-100', 'text-gray-600']
                                                };
                                                $statusText = match($activity->status) {
                                                    'completed' => 'completed',
                                                    'in_progress' => 'moved to in progress',
                                                    'todo' => 'created',
                                                    default => 'updated'
                                                };
                                            @endphp
                                            <div class="w-8 h-8 {{ $iconColor[0] }} rounded-full flex items-center justify-center flex-shrink-0">
                                                @if($activity->status === 'completed')
                                                    <svg class="w-4 h-4 {{ $iconColor[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @elseif($activity->status === 'in_progress')
                                                    <svg class="w-4 h-4 {{ $iconColor[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 {{ $iconColor[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900">
                                                    Task "{{ $activity->card_title }}" was {{ $statusText }}
                                                    @if($activity->user)
                                                        by {{ $activity->user->name }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $activity->updated_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8">
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 text-sm">No recent activity</p>
                                            <p class="text-gray-400 text-xs mt-1">Tasks will appear here when they are updated</p>
                                        </div>
                                    @endforelse
                                </div>

                                @if($recentActivities->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <a href="{{ route('admin.projects.board', $project->slug) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            View Project Board →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Project Details -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Details</h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Team Leader</label>
                                        <div class="mt-1 flex items-center space-x-2">
                                            @if($project->user)
                                                <img class="w-8 h-8 rounded-full"
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($project->user->name) }}&background=random"
                                                    alt="Team Leader">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $project->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $project->user->email }}</p>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-500">No team leader assigned</p>
                                                    <p class="text-xs text-gray-400">Click edit to assign</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Deadline</label>
                                        <div class="mt-1 flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z" />
                                            </svg>
                                            <span class="text-sm text-gray-900">
                                                {{ $project->deadline ? date('M d, Y', strtotime($project->deadline)) : 'No deadline set' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Created</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $project->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Members -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Team Members</h3>
                                    <button onclick="openAddMemberModal()" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        + Add Member
                                    </button>
                                </div>

                                <div class="space-y-3" id="members-list">
                                    <!-- Team Lead/Project Manager (always shown first) -->
                                    @if($project->user)
                                        <div class="flex items-center space-x-3 bg-green-50 rounded-lg p-3 border border-green-200">
                                            <img class="w-10 h-10 rounded-full"
                                                src="https://ui-avatars.com/api/?name={{ urlencode($project->user->name) }}&background=random"
                                                alt="{{ $project->user->name }}">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <p class="text-sm font-medium text-gray-900">{{ $project->user->name }}</p>
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        @if($project->user->role === 'leader')
                                                            Team Leader
                                                        @elseif($project->user->role === 'admin')
                                                            Project Manager
                                                        @else
                                                            {{ ucfirst($project->user->role) }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500">{{ $project->user->email }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs text-gray-500">{{ $project->created_at->format('M d, Y') }}</span>
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Other Members (excluding project manager) -->
                                    @forelse($project->membersWithUsers->where('user_id', '!=', $project->user_id ?? 0) as $member)
                                        <div class="flex items-center space-x-3 member-item" data-member-id="{{ $member->id }}">
                                            <img class="w-10 h-10 rounded-full"
                                                src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=random"
                                                alt="{{ $member->user->name }}">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $member->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ ucfirst($member->role) }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs text-gray-500">{{ $member->joined_at->format('M d, Y') }}</span>
                                                <button onclick="removeMember({{ $member->id }})" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-gray-500" id="no-members-message">
                                            No team members added yet. Click "Add Member" to start building your team.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div id="addMemberModal" class="fixed inset-0 bg-black/50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Add Team Member</h3>
                        <button onclick="closeAddMemberModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form id="addMemberForm" class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- User Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Search User
                            </label>
                            <div class="relative">
                                <input type="text"
                                       id="userSearch"
                                       placeholder="Type to search users..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div id="userSearchResults" class="absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 overflow-y-scroll hidden" style="height: 170px;"></div>
                            </div>
                            <input type="hidden" id="selectedUserId" name="user_id">
                            <div id="selectedUserDisplay" class="mt-2 hidden">
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-md">
                                    <img id="selectedUserAvatar" class="w-8 h-8 rounded-full" src="" alt="">
                                    <div>
                                        <p id="selectedUserName" class="text-sm font-medium text-gray-900"></p>
                                        <p id="selectedUserEmail" class="text-xs text-gray-500"></p>
                                    </div>
                                    <button type="button" onclick="clearSelectedUser()" class="text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Role
                            </label>
                            <select id="memberRole" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Role</option>
                                <option value="developer">Developer</option>
                                <option value="designer">Designer</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeAddMemberModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">
                            Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const projectId = {{ $project->id }};
    const projectSlug = '{{ $project->slug }}';
    let searchTimeout;

    // Open/Close Modal
    function openAddMemberModal() {
        document.getElementById('addMemberModal').classList.remove('hidden');
    }

    function closeAddMemberModal() {
        document.getElementById('addMemberModal').classList.add('hidden');
        resetForm();
    }

    function resetForm() {
        document.getElementById('addMemberForm').reset();
        document.getElementById('userSearch').value = '';
        document.getElementById('selectedUserId').value = '';
        document.getElementById('selectedUserDisplay').classList.add('hidden');
        document.getElementById('userSearchResults').classList.add('hidden');
    }

    // User Search
    document.getElementById('userSearch').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();

        if (query.length < 2) {
            document.getElementById('userSearchResults').classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            searchUsers(query);
        }, 300);
    });

    function searchUsers(query) {
        fetch(`/admin/search-users?q=${encodeURIComponent(query)}&project_id=${projectId}`)
            .then(response => response.json())
            .then(users => {
                const resultsDiv = document.getElementById('userSearchResults');

                if (users.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-2 text-sm text-gray-500">No users found</div>';
                } else {
                    resultsDiv.innerHTML = users.map(user => {
                        const isWorking = user.status === 'working';
                        const statusIcon = isWorking ? '🔴' : '✅';
                        const statusText = isWorking ? 'Working' : 'Free';
                        const statusClass = isWorking ? 'text-red-600' : 'text-green-600';
                        const userClass = isWorking ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer';
                        const clickHandler = isWorking ? '' : `onclick="selectUser(${user.id}, '${user.name}', '${user.email}', '${user.role}', '${user.status}')"`;

                        return `
                        <div class="p-2 ${userClass} user-result" ${clickHandler}>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <img class="w-6 h-6 rounded-full"
                                         src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random"
                                         alt="${user.name}">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">${user.name}</p>
                                        <p class="text-xs text-gray-500">${user.email} • ${user.role}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span>${statusIcon}</span>
                                    <span class="text-xs font-medium ${statusClass}">${statusText}</span>
                                </div>
                            </div>
                            ${isWorking ? '<p class="text-xs text-red-500 mt-1">Cannot be added - currently working on a task</p>' : ''}
                        </div>
                        `;
                    }).join('');
                }

                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error searching users:', error);
            });
    }

    function selectUser(userId, name, email, role, status) {
        document.getElementById('selectedUserId').value = userId;
        document.getElementById('userSearch').value = name;
        document.getElementById('selectedUserName').textContent = name;

        // Show status in selected user display
        const statusIcon = status === 'working' ? '🔴' : '✅';
        const statusText = status === 'working' ? 'Working' : 'Free';
        document.getElementById('selectedUserEmail').textContent = email + ' • ' + role + ' • ' + statusIcon + ' ' + statusText;
        document.getElementById('selectedUserAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random`;

        document.getElementById('selectedUserDisplay').classList.remove('hidden');
        document.getElementById('userSearchResults').classList.add('hidden');
    }

    function clearSelectedUser() {
        document.getElementById('selectedUserId').value = '';
        document.getElementById('userSearch').value = '';
        document.getElementById('selectedUserDisplay').classList.add('hidden');
    }

    // Add Member Form Submit
    document.getElementById('addMemberForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const userId = document.getElementById('selectedUserId').value;
        const role = document.getElementById('memberRole').value;

        if (!userId) {
            alert('Please select a user');
            return;
        }

        if (!role) {
            alert('Please select a role');
            return;
        }

        const formData = {
            user_id: userId,
            role: role,
            _token: '{{ csrf_token() }}'
        };

        fetch(`/admin/projects/${projectSlug}/members`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh the page to show new member
            } else {
                alert(data.message || 'Error adding member');
            }
        })
        .catch(error => {
            console.error('Error adding member:', error);
            alert('Error adding member');
        });
    });

    // Remove Member
    function removeMember(memberId) {
        if (!confirm('Are you sure you want to remove this member?')) {
            return;
        }

        fetch(`/admin/projects/${projectSlug}/members/${memberId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove member from DOM
                const memberElement = document.querySelector(`[data-member-id="${memberId}"]`);
                if (memberElement) {
                    memberElement.remove();
                }

                // Show "no members" message if no members left
                const membersList = document.getElementById('members-list');
                if (membersList.children.length === 0) {
                    membersList.innerHTML = `
                        <div class="text-center py-4 text-gray-500" id="no-members-message">
                            No members added yet. Click "Add Member" to start building your team.
                        </div>
                    `;
                }
            } else {
                alert(data.message || 'Error removing member');
            }
        })
        .catch(error => {
            console.error('Error removing member:', error);
            alert('Error removing member');
        });
    }

    // Close modal when clicking outside
    document.getElementById('addMemberModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddMemberModal();
        }
    });
</script>
@endsection
