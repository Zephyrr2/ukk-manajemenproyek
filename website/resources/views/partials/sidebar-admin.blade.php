<!-- Admin Sidebar -->
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-emerald-800 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-800' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                    </svg>
                    Dashboard
                </a>

                <!-- Kelola Proyek -->
                <a href="{{ route('admin.projects') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-emerald-800 transition-colors duration-200 {{ request()->routeIs('admin.projects*') ? 'bg-emerald-800' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Manage Projects
                </a>

                <!-- Kelola Tim -->
                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-emerald-800 transition-colors duration-200 {{ request()->routeIs('admin.teams') ? 'bg-emerald-800' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    User Management
                </a>

                <!-- Laporan & Analytics -->
                <a href="{{ route('admin.reports') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-emerald-800 transition-colors duration-200 {{ request()->routeIs('admin.reports') ? 'bg-emerald-800' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Reports
                </a>
            </div>
        </div>

        <!-- User Profile Section - Fixed at bottom -->
        <div class="px-4 pb-6">
            <div class="border-t border-emerald-800 pt-4">
                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-emerald-800 transition-colors duration-200">
                    <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-white font-medium">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-emerald-200 text-sm">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</p>
                    </div>
                </a>

                <!-- Logout -->
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

<!-- Sidebar overlay for mobile -->
<div class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>
