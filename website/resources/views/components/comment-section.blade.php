<!-- Comments Section -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <svg class="inline w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.013 8.013 0 01-4.79-1.58l-4.42 1.58 1.58-4.42A8.013 8.013 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
            </svg>
            Komentar ({{ $comments->count() }})
        </h3>
        <p class="mt-1 text-sm text-gray-500">Diskusi dan catatan terkait {{ $commentType == 'card' ? 'task' : 'subtask' }} ini</p>
    </div>

    <div class="px-4 py-5 sm:px-6">
        <!-- Add Comment Form -->
        <div class="mb-6">
            @if($commentType == 'card')
                <form action="{{ route(auth()->user()->role . '.tasks.comments.store', $itemId) }}" method="POST" class="space-y-4">
            @else
                <form action="{{ route(auth()->user()->role . '.subtasks.comments.store', [$taskId, $itemId]) }}" method="POST" class="space-y-4">
            @endif
                @csrf
                <div>
                    <label for="comment_text" class="block text-sm font-medium text-gray-700 mb-2">
                        Tambah Komentar
                    </label>
                    <textarea
                        name="comment_text"
                        id="comment_text"
                        rows="3"
                        required
                        maxlength="1000"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Tulis komentar Anda..."
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 1000 karakter</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Kirim Komentar
                    </button>
                </div>
            </form>
        </div>

        <!-- Comments List -->
        <div class="space-y-4">
            @forelse($comments as $comment)
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-medium text-sm">
                                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $comment->user->role === 'leader' ? 'Project Leader' : 'Team Member' }} •
                                    {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <!-- Delete Comment (Only for comment owner or leader of the project) -->
                        @if(auth()->user()->id == $comment->user_id ||
                            (auth()->user()->role == 'leader' &&
                             (($commentType == 'card' && $comment->card->board->project->user_id == auth()->user()->id) ||
                              ($commentType == 'subtask' && $comment->subtask->card->board->project->user_id == auth()->user()->id))))
                            <form action="{{ route(auth()->user()->role . '.comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="mt-3">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->comment_text }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.013 8.013 0 01-4.79-1.58l-4.42 1.58 1.58-4.42A8.013 8.013 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada komentar</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai diskusi dengan menulis komentar pertama.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
