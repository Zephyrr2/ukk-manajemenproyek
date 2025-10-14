@extends('layouts.dashboard')

@section('sidebar')
    @include('partials.sidebar-leader')
@endsection

@section('title', 'Project Board')
@section('page-title', 'PROJECT BOARD')

@section('content')
    <div class="space-y-6">
        <!-- Project Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">{{ $project->project_name }}</h1>
                        <p class="text-gray-500 text-sm">{{ $project->description ?? 'No description available' }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">Progress: <span
                            class="font-medium text-gray-900">{{ number_format($progressPercentage, 1) }}%</span></div>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Board Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search tasks..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <select
                        class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option>All Members</option>
                        @foreach ($teamMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('leader.projects.create-task', $project->id) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Add Task</span>
                </a>
            </div>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'done' => 'Done'] as $status => $statusLabel)
                <!-- {{ $statusLabel }} Column -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <div
                                class="w-3 h-3
                        @if ($status === 'todo') bg-gray-400
                        @elseif($status === 'in_progress') bg-yellow-400
                        @elseif($status === 'review') bg-green-400
                        @else bg-green-400 @endif rounded-full">
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $statusLabel }}</h3>
                        </div>
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-sm font-medium">
                            {{ count($boardData[$status]) }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($boardData[$status] as $task)
                            <!-- Task Card -->
                            <div
                                class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 cursor-pointer hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $task->card_title }}</h4>
                                    @if ($task->priority)
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            @if ($task->priority === 'high') bg-red-100 text-red-800
                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    @endif
                                </div>

                                @if ($task->description)
                                    <p class="text-gray-600 text-xs mb-3">{{ Str::limit($task->description, 80) }}</p>
                                @endif

                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    @if ($task->due_date)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                        </div>
                                    @endif

                                    @if ($task->estimated_hours)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $task->estimated_hours }}h
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <img class="w-6 h-6 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name) }}&size=24&background=random"
                                            alt="{{ $task->user->name }}" title="{{ $task->user->name }}">

                                        <!-- Comment Button -->
                                        <button
                                            onclick="openCommentModal('task', {{ $task->id }}, {{ $task->id }})"
                                            class="flex items-center text-xs text-gray-500 hover:text-green-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z" />
                                                <path fill="currentColor" fill-rule="evenodd"
                                                    d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $task->comments->where('subtask_id', null)->count() }}
                                        </button>
                                    </div>

                                    <!-- Status indicator dots -->
                                    <div class="flex space-x-1">
                                        @for ($i = 1; $i <= 3; $i++)
                                            <div
                                                class="w-2 h-2
                                @if ($status === 'done') bg-green-600
                                @elseif($status === 'review') bg-green-600
                                @elseif($status === 'in_progress') bg-yellow-600
                                @else bg-gray-300 @endif rounded-full">
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- Empty State -->
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm">No tasks</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- <!-- Add Task Button for each column -->
                    <div class="mt-4">
                        <a href="{{ route('leader.projects.create-task', $project->id) }}?status={{ $status }}"
                            class="w-full flex items-center justify-center px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Task
                        </a>
                    </div> --}}
                </div>
            @endforeach
        </div>
    </div>

    <!-- Comment Modal -->
    <div id="commentModal" class="fixed inset-0 hidden z-50" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <!-- Modal backdrop click area -->
            <div class="fixed inset-0" onclick="closeCommentModal()"></div>

            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden z-10">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                                <path fill="#16A34A" fill-rule="evenodd"
                                    d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z" />
                                <path fill="#16A34A" fill-rule="evenodd"
                                    d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4 w-full">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900" id="modalTitle">
                                Komentar Task
                            </h3>
                            <p class="mt-1 text-sm text-gray-500" id="modalSubtitle">
                                Diskusi dan catatan terkait task ini
                            </p>
                        </div>
                        <button type="button" class="ml-auto text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="closeCommentModal()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-6 px-6 pb-6">
                        <!-- Add Comment Form -->
                        <form id="commentForm" method="POST" class="space-y-4 mb-6">
                            @csrf
                            <input type="hidden" name="task_id" id="modal_task_id" value="">
                            <div>
                                <label for="modal_comment_text" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tambah Komentar
                                </label>
                                <textarea name="comment_text" id="modal_comment_text" rows="4" required maxlength="1000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                    placeholder="Tulis komentar Anda..."></textarea>
                                <p class="mt-1 text-xs text-gray-500">Maksimal 1000 karakter</p>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 inline"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z" />
                                        <path fill="currentColor" fill-rule="evenodd"
                                            d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Kirim Komentar
                                </button>
                            </div>
                        </form>

                        <!-- Comments List -->
                        <div id="commentsList" class="space-y-4 max-h-80 overflow-y-auto border-t border-gray-200 pt-4">
                            <!-- Comments will be loaded here via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    <script>
        let currentCommentType = '';
        let currentItemId = null;
        let currentTaskId = null;

        function openCommentModal(type, itemId, taskId) {
            currentCommentType = type;
            currentItemId = itemId;
            currentTaskId = taskId;

            const modal = document.getElementById('commentModal');
            modal.classList.remove('hidden');
            modal.style.display = 'block';

            // Update form action and task_id
            const form = document.getElementById('commentForm');
            const taskIdInput = document.getElementById('modal_task_id');
            if (form && taskIdInput) {
                form.action = `/leader/tasks/${itemId}/comments`;
                taskIdInput.value = itemId;
            }

            // Load comments
            loadComments(type, itemId, taskId);
        }

        function closeCommentModal() {
            const modal = document.getElementById('commentModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.getElementById('modal_comment_text').value = '';
        }

        function loadComments(type, itemId, taskId) {
            // Get task comments from the board data
            const boardData = @json($boardData);
            let task = null;

            // Find task across all status columns
            Object.values(boardData).forEach(tasks => {
                const foundTask = tasks.find(t => t.id == itemId);
                if (foundTask) task = foundTask;
            });

            const comments = task && task.comments ? task.comments.filter(c => c.subtask_id === null) : [];
            displayComments(comments);
        }

        function displayComments(comments) {
            const commentsList = document.getElementById('commentsList');

            if (comments.length === 0) {
                commentsList.innerHTML = `
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto" width="60" height="60" viewBox="0 0 24 24"><path fill="#BDBDBD" d="M7 10.597a.75.75 0 0 1 .75-.75h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1-.75-.75m.75 2.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z"/><path fill="#BDBDBD" fill-rule="evenodd" d="M2.5 12.096a9.5 9.5 0 1 1 9.5 9.5H3.25a.75.75 0 0 1-.53-1.28l2.053-2.054A9.47 9.47 0 0 1 2.5 12.096m9.5-8a8 8 0 0 0-5.657 13.657a.75.75 0 0 1 0 1.06l-1.282 1.283H12a8 8 0 1 0 0-16" clip-rule="evenodd"/></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada komentar</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai diskusi dengan menulis komentar pertama.</p>
            </div>
        `;
                return;
            }

            const currentUserId = {{ auth()->id() }};

            // Sort comments by created_at descending
            comments.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            commentsList.innerHTML = comments.map(comment => `
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-medium text-sm">
                                ${comment.user.name.substring(0, 2).toUpperCase()}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${comment.user.name}</p>
                        <p class="text-xs text-gray-500">
                            ${comment.user.role === 'leader' ? 'Project Leader' : 'Team Member'} •
                            ${formatDate(comment.created_at)} ${comment.updated_at !== comment.created_at ? '(edited)' : ''}
                        </p>
                    </div>
                </div>

                ${comment.user_id == currentUserId ? `
                                    <form action="/leader/comments/${comment.id}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                ` : ''}
            </div>

            <div class="mt-3">
                <p class="text-sm text-gray-700 whitespace-pre-wrap">${comment.comment_text}</p>
            </div>
        </div>
    `).join('');
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'beberapa detik yang lalu';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} menit yang lalu`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} jam yang lalu`;
            return `${Math.floor(diffInSeconds / 86400)} hari yang lalu`;
        }

        // Close modal when clicking outside
        document.getElementById('commentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCommentModal();
            }
        });
    </script>
@endsection
