<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') - Project Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Include Sidebar -->
        @yield('sidebar')

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile menu button -->
                    <button type="button" class="lg:hidden text-gray-500 hover:text-gray-600" onclick="toggleSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div class="flex-1">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-gray-600 text-sm mt-1">{!! $pageSubtitle ?? 'Selamat datang!' !!}</p>
                    </div>

                    <!-- Notification Bell -->
                    <div class="relative ml-4" x-data="{ open: false, unreadCount: 0, notifications: [] }" x-init="
                        // Fetch unread count on load
                        fetch('/notifications/unread-count')
                            .then(res => res.json())
                            .then(data => unreadCount = data.count);

                        // Poll for updates every 30 seconds
                        setInterval(() => {
                            fetch('/notifications/unread-count')
                                .then(res => res.json())
                                .then(data => unreadCount = data.count);
                        }, 30000);
                    " @click.away="open = false">
                        <button @click="open = !open; if(open) {
                            fetch('/notifications/recent')
                                .then(res => res.json())
                                .then(data => notifications = data);
                        }" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"></span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                    <a href="/notifications" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500">
                                        <p>No notifications</p>
                                    </div>
                                </template>
                                <template x-for="notif in notifications" :key="notif.id">
                                    <div @click="fetch('/notifications/' + notif.id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })"
                                       class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition cursor-pointer">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mr-3">
                                                <span x-text="notif.type === 'task_submitted' ? '📝' : notif.type === 'task_approved' ? '✅' : notif.type === 'task_rejected' ? '❌' : notif.type === 'extension_request' ? '⏰' : notif.type === 'extension_approved' ? '✅' : notif.type === 'extension_rejected' ? '❌' : '🔔'" class="text-2xl"></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900" x-text="notif.title"></p>
                                                <p class="text-sm text-gray-600 mt-1" x-text="notif.message"></p>
                                                <p class="text-xs text-gray-400 mt-1" x-text="new Date(notif.created_at).toLocaleString()"></p>
                                            </div>
                                            <span x-show="!notif.is_read" class="flex-shrink-0 ml-2 w-2 h-2 bg-blue-600 rounded-full"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Alpine.js -->
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript for sidebar toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuButton = event.target.closest('button[onclick="toggleSidebar()"]');

            if (!sidebar.contains(event.target) && !menuButton && window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>
