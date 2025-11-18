@extends('layouts.dashboard')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('partials.sidebar-admin')
    @elseif(auth()->user()->role === 'leader')
        @include('partials.sidebar-leader')
    @else
        @include('partials.sidebar-user')
    @endif
@endsection

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Actions -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">All Notifications</h2>
            <div class="flex gap-2">
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                        Mark all as read
                    </button>
                </form>
                <form action="{{ route('notifications.clear-read') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all read notifications?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                        Clear read
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ $notification->is_read ? 'opacity-75' : '' }}">
                <div class="p-4">
                    <div class="flex items-start">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mr-4">
                            <span class="text-3xl">{{ $notification->icon }}</span>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        {{ $notification->title }}
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                New
                                            </span>
                                        @endif
                                    </h3>
                                    <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <p class="text-sm text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 ml-4">
                                    @if($notification->type === 'extension_request' && $notification->card && $notification->card->extension_status === 'pending')
                                        <form action="{{ route('leader.tasks.approve-extension', $notification->card_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('leader.tasks.reject-extension', $notification->card_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Task Details (if available) -->
                            @if($notification->data && isset($notification->data['task_title']))
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Task:</span> {{ $notification->data['task_title'] }}
                                    </p>
                                    @if(isset($notification->data['submitted_by']))
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">By:</span> {{ $notification->data['submitted_by'] }}
                                        </p>
                                    @endif
                                    @if(isset($notification->data['approved_by']))
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Approved by:</span> {{ $notification->data['approved_by'] }}
                                        </p>
                                    @endif
                                    @if(isset($notification->data['rejected_by']))
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-medium">Rejected by:</span> {{ $notification->data['rejected_by'] }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No notifications</h3>
                <p class="mt-1 text-gray-500">You're all caught up!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
