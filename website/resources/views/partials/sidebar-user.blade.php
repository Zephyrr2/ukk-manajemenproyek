<!-- User Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-emerald-900 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0" id="sidebar">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-emerald-800">
        <h1 class="text-xl font-bold text-white">ProjectManager</h1>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col h-full">
        <!-- Main Navigation -->
        <div class="flex-1 mt-8 px-4">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-emerald-800 transition-colors duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-emerald-800' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                    </svg>
                    Dashboard
                </a>

                <!-- My Tasks -->
                <a href="{{ route('user.tasks') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-emerald-800 transition-colors duration-200 {{ request()->routeIs('user.tasks*') || request()->routeIs('user.subtasks*') ? 'bg-emerald-800' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    My Tasks
                </a>

                <!-- Time Log -->
                <a href="{{ route('user.time-tracking') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-emerald-800 transition-colors duration-200 {{ request()->routeIs('user.time-tracking*') ? 'bg-emerald-800' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Time Log
                </a>
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="p-4 border-t border-emerald-800">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-emerald-300 truncate">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
            </div>

            <!-- Logout -->
            <div class="mt-3 mb-16">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 mt-2 mb-16 text-white rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>

<!-- Sidebar Overlay for Mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden hidden"></div>
