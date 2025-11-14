<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Project Management</title>

    <!-- Tailwind CSS via Vite -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Additional CSS -->
    @yield('styles')
</head>
<body class="h-full bg-gray-100 font-sans antialiased">
    <!-- Sidebar -->
    @include('partials.sidebar-admin')

    <!-- Main Content Wrapper -->
    <div class="min-h-screen bg-gray-100 lg:ml-64">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <!-- Mobile menu button -->
                    <button type="button" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100" onclick="toggleSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate">
                            @yield('page-title', 'Admin Dashboard')
                        </h1>
                    </div>

                </div>
            </header>

        <!-- Main Content Area -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {
                const isHidden = sidebar.classList.contains('-translate-x-full');

                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuButton = event.target.closest('button[onclick*="toggleSidebar"]');

            if (overlay && !overlay.classList.contains('hidden') && !sidebar.contains(event.target) && !menuButton) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
