import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/task_card.dart';
import '../utils/constants.dart';
import 'task_detail_screen.dart';

class TasksScreen extends StatefulWidget {
  const TasksScreen({super.key});

  @override
  State<TasksScreen> createState() => _TasksScreenState();
}

class _TasksScreenState extends State<TasksScreen> {
  List<TaskCard> _tasks = [];
  bool _isLoading = true;
  String _errorMessage = '';

  // Task statistics
  int _totalTasks = 0;
  int _todoTasks = 0;
  int _inProgressTasks = 0;
  int _completedTasks = 0;

  @override
  void initState() {
    super.initState();
    _loadTasks();
  }

  Future<void> _loadTasks() async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final tasks = await ApiService.getUserCards();
      if (mounted) {
        setState(() {
          _tasks = tasks;
          _calculateStats();
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _errorMessage = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  void _calculateStats() {
    _totalTasks = _tasks.length;
    _todoTasks = _tasks.where((t) => t.status == 'todo').length;
    _inProgressTasks = _tasks.where((t) => t.status == 'in_progress').length;
    _completedTasks = _tasks.where((t) => t.status == 'done').length;
  }

  Color _getPriorityColor(String? priority) {
    switch (priority?.toLowerCase()) {
      case 'high':
        return AppColors.red500;
      case 'medium':
        return AppColors.yellow500;
      case 'low':
        return AppColors.green600;
      default:
        return AppColors.gray500;
    }
  }

  Color _getStatusColor(String? status) {
    switch (status?.toLowerCase()) {
      case 'in_progress':
        return AppColors.yellow500;
      case 'review':
        return AppColors.blue600;
      case 'done':
        return AppColors.green600;
      default:
        return AppColors.gray500;
    }
  }

  Color _getStatusBgColor(String? status) {
    switch (status?.toLowerCase()) {
      case 'in_progress':
        return AppColors.yellow100;
      case 'review':
        return AppColors.blue100;
      case 'done':
        return AppColors.green100;
      default:
        return AppColors.gray100;
    }
  }

  String _getStatusLabel(String? status) {
    switch (status?.toLowerCase()) {
      case 'in_progress':
        return 'In Progress';
      case 'review':
        return 'Review';
      case 'done':
        return 'Done';
      case 'todo':
        return 'To Do';
      default:
        return status ?? 'Unknown';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: RefreshIndicator(
        onRefresh: _loadTasks,
        color: AppColors.primary,
        child: CustomScrollView(
          slivers: [
            // Page Header
            SliverToBoxAdapter(
              child: Container(
                padding: const EdgeInsets.all(20),
                color: Colors.white,
                child: const Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'MY TASKS',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: AppColors.gray900,
                      ),
                    ),
                    SizedBox(height: 4),
                    Text(
                      'Kelola task yang assigned kepada Anda',
                      style: TextStyle(
                        fontSize: 14,
                        color: AppColors.gray600,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Statistics Cards
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Row(
                  children: [
                    Expanded(
                      child: _buildStatCard(
                        'Total Tasks',
                        '$_totalTasks',
                        Icons.content_paste,
                        AppColors.gray400,
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: _buildStatCard(
                        'To Do',
                        '$_todoTasks',
                        Icons.schedule,
                        AppColors.gray400,
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: _buildStatCard(
                        'In Progress',
                        '$_inProgressTasks',
                        Icons.pending_actions,
                        AppColors.yellow500,
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: _buildStatCard(
                        'Completed',
                        '$_completedTasks',
                        Icons.check_circle,
                        AppColors.green600,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Tasks List
            if (_isLoading)
              const SliverFillRemaining(
                child: Center(
                  child: CircularProgressIndicator(
                    valueColor: AlwaysStoppedAnimation<Color>(AppColors.primary),
                  ),
                ),
              )
            else if (_errorMessage.isNotEmpty)
              SliverFillRemaining(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.error_outline, size: 48, color: AppColors.error),
                      const SizedBox(height: 16),
                      Text(
                        'Error: $_errorMessage',
                        style: const TextStyle(color: AppColors.error),
                      ),
                      const SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: _loadTasks,
                        child: const Text('Retry'),
                      ),
                    ],
                  ),
                ),
              )
            else if (_tasks.isEmpty)
              SliverFillRemaining(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.inbox_outlined, size: 64, color: AppColors.gray400),
                      const SizedBox(height: 16),
                      const Text(
                        'Tidak ada task',
                        style: TextStyle(
                          fontSize: 16,
                          color: AppColors.gray600,
                        ),
                      ),
                    ],
                  ),
                ),
              )
            else
              SliverPadding(
                padding: const EdgeInsets.all(16),
                sliver: SliverList(
                  delegate: SliverChildBuilderDelegate(
                    (context, index) {
                      final task = _tasks[index];
                      return _buildTaskCard(task);
                    },
                    childCount: _tasks.length,
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildStatCard(String label, String value, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: AppColors.gray200),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 4,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 20),
          const SizedBox(height: 8),
          Text(
            label,
            style: const TextStyle(
              fontSize: 11,
              color: AppColors.gray600,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: AppColors.gray900,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTaskCard(TaskCard task) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: AppColors.gray200),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 4,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: InkWell(
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => TaskDetailScreen(taskId: task.id),
            ),
          );
        },
        borderRadius: BorderRadius.circular(8),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Title row with priority indicator
              Row(
                children: [
                  Container(
                    width: 12,
                    height: 12,
                    decoration: BoxDecoration(
                      color: _getPriorityColor(task.priority),
                      shape: BoxShape.circle,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      task.cardTitle,
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w600,
                        color: AppColors.gray900,
                      ),
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                    decoration: BoxDecoration(
                      color: _getStatusBgColor(task.status),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      _getStatusLabel(task.status),
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.w500,
                        color: _getStatusColor(task.status),
                      ),
                    ),
                  ),
                ],
              ),
              
              if (task.description != null && task.description!.isNotEmpty) ...[
                const SizedBox(height: 8),
                Text(
                  task.description!,
                  style: const TextStyle(
                    fontSize: 14,
                    color: AppColors.gray600,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
              
              const SizedBox(height: 12),
              
              // Task info row
              Row(
                children: [
                  Icon(Icons.flag_outlined, size: 16, color: _getPriorityColor(task.priority)),
                  const SizedBox(width: 4),
                  Text(
                    task.priority.toUpperCase(),
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w500,
                      color: _getPriorityColor(task.priority),
                    ),
                  ),
                  if (task.dueDate != null) ...[
                    const SizedBox(width: 16),
                    const Icon(Icons.calendar_today, size: 16, color: AppColors.gray500),
                    const SizedBox(width: 4),
                    Text(
                      task.dueDate != null ? '${task.dueDate!.day}/${task.dueDate!.month}/${task.dueDate!.year}' : '-',
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.gray600,
                      ),
                    ),
                  ],
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
