@extends('layouts.dashboard')

@section('sidebar')
@include('partials.sidebar-leader')
@endsection

@section('title', 'Progress Tracking')
@section('page-title', 'PROGRESS TRACKING')
@section('page-subtitle', 'Monitor project and team performance')

@section('content')
<div class="space-y-6">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overall Progress</p>
                    <p class="text-2xl font-semibold text-green-600">78%</p>
                    <div class="flex items-center mt-1">
                        <svg class="w-3 h-3 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l5-5 5 5M7 7l5 5 5-5"/>
                        </svg>
                        <span class="text-xs text-green-600">+5% from last week</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Team Velocity</p>
                    <p class="text-2xl font-semibold text-green-600">24</p>
                    <p class="text-xs text-gray-500 mt-1">tasks/week</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg. Task Time</p>
                    <p class="text-2xl font-semibold text-yellow-600">2.5</p>
                    <p class="text-xs text-gray-500 mt-1">days</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Quality Score</p>
                    <p class="text-2xl font-semibold text-purple-600">92%</p>
                    <div class="flex items-center mt-1">
                        <svg class="w-3 h-3 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l5-5 5 5M7 7l5 5 5-5"/>
                        </svg>
                        <span class="text-xs text-green-600">+3% improvement</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
            <h3 class="text-lg font-semibold text-gray-900">Progress Overview</h3>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                <select class="w-full sm:w-auto border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>All Projects</option>
                    <option>E-Learning Platform</option>
                    <option>Mobile Banking App</option>
                    <option>E-Commerce Platform</option>
                </select>

                <select class="w-full sm:w-auto border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Last 30 days</option>
                    <option>Last 7 days</option>
                    <option>Last 3 months</option>
                    <option>Custom range</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                    Export Report
                </button>
                <button class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm">
                    Schedule Report
                </button>
            </div>
        </div>
    </div>

    <!-- Progress Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Project Progress Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Project Progress</h3>
                <button class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                </button>
            </div>

            <!-- Chart placeholder -->
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center mb-4">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-gray-500">Progress Chart will be displayed here</p>
                </div>
            </div>

            <!-- Legend -->
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Completed</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">In Progress</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">To Do</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Overdue</span>
                </div>
            </div>
        </div>

        <!-- Team Performance Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Team Performance</h3>
                <button class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                </button>
            </div>

            <!-- Chart placeholder -->
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center mb-4">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <p class="text-gray-500">Performance Chart will be displayed here</p>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tasks Completed</span>
                    <span class="text-sm font-medium text-gray-900">142/180</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Average Rating</span>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-900 mr-2">4.8</span>
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21L16.54 13.97L22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.46 13.97L5.82 21L12 17.27Z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Status Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Project Status</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Project 1 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">E-Learning Platform</div>
                                    <div class="text-sm text-gray-500">Development Phase</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">75%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>12/16 completed</div>
                            <div class="text-xs text-gray-500">4 in progress</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex -space-x-2">
                                <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=24&h=24&q=80" alt="">
                                <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1494790108755-2616b332c1e5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=24&h=24&q=80" alt="">
                                <div class="w-6 h-6 rounded-full border border-white bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">+6</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dec 15, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">On Track</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-green-600 hover:text-green-900 mr-2">View</button>
                            <button class="text-gray-600 hover:text-gray-900">Edit</button>
                        </td>
                    </tr>

                    <!-- Project 2 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Mobile Banking App</div>
                                    <div class="text-sm text-gray-500">Testing Phase</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">45%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>8/18 completed</div>
                            <div class="text-xs text-gray-500">5 in progress</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex -space-x-2">
                                <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=24&h=24&q=80" alt="">
                                <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=24&h=24&q=80" alt="">
                                <div class="w-6 h-6 rounded-full border border-white bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">+4</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jan 30, 2025</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">At Risk</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-green-600 hover:text-green-900 mr-2">View</button>
                            <button class="text-gray-600 hover:text-gray-900">Edit</button>
                        </td>
                    </tr>

                    <!-- Project 3 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">E-Commerce Platform</div>
                                    <div class="text-sm text-gray-500">Planning Phase</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">25%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gray-400 h-2 rounded-full" style="width: 25%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>3/12 completed</div>
                            <div class="text-xs text-gray-500">2 in progress</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex -space-x-2">
                                <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=24&h=24&q=80" alt="">
                                <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=24&h=24&q=80" alt="">
                                <div class="w-6 h-6 rounded-full border border-white bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">+2</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mar 20, 2025</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">On Hold</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-green-600 hover:text-green-900 mr-2">View</button>
                            <button class="text-gray-600 hover:text-gray-900">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Individual Team Member Performance -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Individual Performance</h3>
            <button class="text-green-600 hover:text-green-800 text-sm">View All</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Team Member 1 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center space-x-3 mb-4">
                    <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=40&h=40&q=80" alt="">
                    <div>
                        <h4 class="font-medium text-gray-900">John Doe</h4>
                        <p class="text-sm text-gray-500">Senior Developer</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Completed Tasks</span>
                        <span class="font-medium text-green-600">18/20</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Performance</span>
                        <span class="font-medium text-green-600">95%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Avg. Time</span>
                        <span class="font-medium text-gray-900">2.1 days</span>
                    </div>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center space-x-3 mb-4">
                    <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1494790108755-2616b332c1e5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=40&h=40&q=80" alt="">
                    <div>
                        <h4 class="font-medium text-gray-900">Jane Smith</h4>
                        <p class="text-sm text-gray-500">UI/UX Designer</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Completed Tasks</span>
                        <span class="font-medium text-green-600">15/18</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Performance</span>
                        <span class="font-medium text-green-600">88%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Avg. Time</span>
                        <span class="font-medium text-gray-900">2.8 days</span>
                    </div>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center space-x-3 mb-4">
                    <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=40&h=40&q=80" alt="">
                    <div>
                        <h4 class="font-medium text-gray-900">Mike Johnson</h4>
                        <p class="text-sm text-gray-500">Backend Developer</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Completed Tasks</span>
                        <span class="font-medium text-green-600">12/16</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Performance</span>
                        <span class="font-medium text-green-600">82%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Avg. Time</span>
                        <span class="font-medium text-gray-900">3.2 days</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
