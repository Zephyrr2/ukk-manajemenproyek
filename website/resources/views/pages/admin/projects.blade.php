@extends('layouts.admin')

@section('title', 'Project Management')
@section('page-title', 'Project Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
            <!-- Header Actions -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.projects.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Project
                    </a>

                    <div class="flex items-center space-x-2">
                        <div class="relative">
                            <input type="text" placeholder="Search projects..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Completed</option>
                        <option>On Hold</option>
                    </select>
                </div>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Project Card 1 -->
                @foreach ($projects as $p)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                         onclick="window.location.href='{{ route('admin.projects.show', $p->slug) }}'">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="font-semibold text-gray-900">{{ $p->project_name }}</h3>
                                        <p class="text-sm text-gray-500">{{ strlen($p->description ?? '') > 50 ? substr($p->description, 0, 50) . '...' : ($p->description ?? 'No description') }}</p>
                                    </div>
                                </div>
                                <div class="relative">
                                    <button onclick="event.stopPropagation(); toggleDropdown('dropdown-{{ $p->id }}')" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div id="dropdown-{{ $p->id }}"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                        <div class="py-1">
                                            <a href="{{ route('admin.projects.edit', $p->slug) }}"
                                                onclick="event.stopPropagation()"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit Project
                                            </a>
                                            <form action="{{ route('admin.projects.destroy', $p->slug) }}" method="POST" class="block"
                                                onclick="event.stopPropagation()"
                                                onsubmit="event.stopPropagation(); return confirm('Apakah Anda yakin ingin menghapus project {{ $p->project_name }}? Semua data terkait akan terhapus!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus Project
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                    <span>Progress</span>
                                    <span>{{ $p->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $p->progress_percentage }}%"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>
                                        <span>{{ $p->membersWithUsers->where('user_id', '!=', $p->user_id ?? 0)->count() + ($p->user ? 1 : 0) }} members</span>
                                    </div>
                                </div>
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z" />
                                    </svg>
                                    <span>{{ $p->deadline ? \Carbon\Carbon::parse($p->deadline)->format('M d, Y') : 'No deadline' }}</span>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center">
                                <div class="flex -space-x-2">
                                    @if($p->user)
                                        <img class="w-8 h-8 rounded-full border-2 border-white"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($p->user->name) }}&background=random"
                                            alt="{{ $p->user->name }}"
                                            title="{{ $p->user->name }} ({{ ucfirst($p->user->role) }})">
                                    @endif

                                    @php
                                        // Get members excluding the team leader to avoid duplication
                                        $otherMembers = $p->membersWithUsers->where('user_id', '!=', $p->user_id ?? 0)->take(3);
                                    @endphp

                                    @foreach($otherMembers as $member)
                                        <img class="w-8 h-8 rounded-full border-2 border-white"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=random"
                                            alt="{{ $member->user->name }}"
                                            title="{{ $member->user->name }} ({{ ucfirst($member->role) }})">
                                    @endforeach

                                    @php
                                        $totalMembers = $p->membersWithUsers->where('user_id', '!=', $p->user_id ?? 0)->count() + ($p->user ? 1 : 0);
                                        $displayedMembers = min(4, ($p->user ? 1 : 0) + $otherMembers->count());
                                        $remainingMembers = $totalMembers - $displayedMembers;
                                    @endphp

                                    @if($remainingMembers > 0)
                                        <div class="w-8 h-8 bg-gray-200 rounded-full border-2 border-white flex items-center justify-center">
                                            <span class="text-xs text-gray-600 font-medium">+{{ $remainingMembers }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-auto flex items-center space-x-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="text-sm text-gray-500">{{ $p->tasks_count }} tasks</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

    <!-- Scripts for dropdown functionality -->
    <script>
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('hidden');

            // Close other dropdowns
            document.querySelectorAll('[id^="dropdown-"]:not(#' + dropdownId + ')').forEach(otherDropdown => {
                otherDropdown.classList.add('hidden');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleDropdown"]')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });
    </script>
@endsection
