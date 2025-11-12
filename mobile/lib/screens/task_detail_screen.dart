import 'package:flutter/material.dart';
import '../models/task_card.dart';
import '../models/subtask.dart';
import '../models/comment.dart';
import '../services/api_service.dart';

class TaskDetailScreen extends StatefulWidget {
  final int taskId;
  
  const TaskDetailScreen({
    super.key,
    required this.taskId,
  });

  @override
  State<TaskDetailScreen> createState() => _TaskDetailScreenState();
}

class _TaskDetailScreenState extends State<TaskDetailScreen>
    with SingleTickerProviderStateMixin {
  TaskCard? _task;
  List<Subtask> _subtasks = [];
  List<Comment> _comments = [];
  bool _isLoading = true;
  TabController? _tabController;
  
  final _commentController = TextEditingController();
  final _subtaskTitleController = TextEditingController();
  final _subtaskDescController = TextEditingController();
  final _subtaskEstimatedHoursController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 3, vsync: this);
    _loadTaskDetails();
  }

  @override
  void dispose() {
    _tabController?.dispose();
    _commentController.dispose();
    _subtaskTitleController.dispose();
    _subtaskDescController.dispose();
    _subtaskEstimatedHoursController.dispose();
    super.dispose();
  }

  Future<void> _loadTaskDetails() async {
    if (!mounted) return;
    
    try {
      setState(() {
        _isLoading = true;
      });

      // Load task details
      final taskData = await ApiService.getTaskDetails(widget.taskId);
      
      if (!mounted) return;
      
      if (taskData != null && taskData['success'] == true) {
        final data = taskData['data'];
        if (data != null) {
          setState(() {
            _task = TaskCard.fromJson(data);
          });
        } else {
          throw Exception('Task data is null');
        }
      } else {
        throw Exception(taskData?['message'] ?? 'Failed to load task');
      }

      // Load subtasks
      final subtasksData = await ApiService.getSubtasks(widget.taskId);
      
      if (!mounted) return;
      
      if (subtasksData != null) {
        setState(() {
          _subtasks = subtasksData.map((json) => Subtask.fromJson(json)).toList();
        });
      }

      // Load comments
      final commentsData = await ApiService.getTaskComments(widget.taskId);
      
      if (!mounted) return;
      
      if (commentsData != null) {
        setState(() {
          _comments = commentsData.map((json) => Comment.fromJson(json)).toList();
        });
      }

      if (!mounted) return;
      
      setState(() {
        _isLoading = false;
      });
    } catch (e) {
      print('Error loading task details: $e');
      
      if (!mounted) return;
      
      setState(() {
        _isLoading = false;
        _task = null;
      });
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error loading task details: ${e.toString()}'),
          backgroundColor: Colors.red,
          duration: const Duration(seconds: 5),
        ),
      );
    }
  }

  Future<void> _onTaskAction(String action) async {
    bool success = false;
    
    switch (action) {
      case 'start':
        success = await ApiService.startTask(_task!.id);
        break;
      case 'submit':
        success = await ApiService.submitTask(_task!.id);
        break;
    }

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Task ${action}ed successfully'),
          backgroundColor: Colors.green,
        ),
      );
      _loadTaskDetails(); // Refresh data
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Failed to update task'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  Future<void> _addComment() async {
    if (_commentController.text.trim().isEmpty) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Comment cannot be empty'),
            backgroundColor: Colors.orange,
          ),
        );
      }
      return;
    }

    final result = await ApiService.addTaskComment(
      widget.taskId,
      _commentController.text.trim(),
    );

    if (!mounted) return;

    if (result['success'] == true) {
      _commentController.clear();
      _loadTaskDetails(); // Refresh data
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Comment added successfully'),
          backgroundColor: Colors.green,
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(result['message'] ?? 'Failed to add comment'),
          backgroundColor: Colors.red,
          duration: const Duration(seconds: 4),
        ),
      );
    }
  }

  Future<void> _addSubtask() async {
    if (_subtaskTitleController.text.trim().isEmpty) return;

    // Parse estimated hours
    double? estimatedHours;
    if (_subtaskEstimatedHoursController.text.trim().isNotEmpty) {
      estimatedHours = double.tryParse(_subtaskEstimatedHoursController.text.trim());
    }

    final result = await ApiService.createSubtask(
      taskId: widget.taskId,
      title: _subtaskTitleController.text.trim(),
      description: _subtaskDescController.text.trim(),
      estimatedHours: estimatedHours,
    );

    if (result != null && mounted) {
      _subtaskTitleController.clear();
      _subtaskDescController.clear();
      _subtaskEstimatedHoursController.clear();
      Navigator.of(context).pop();
      _loadTaskDetails(); // Refresh data
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Subtask added successfully'),
          backgroundColor: Colors.green,
        ),
      );
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Failed to add subtask'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  Future<void> _toggleSubtask(Subtask subtask) async {
    final success = await ApiService.toggleSubtaskStatus(
      widget.taskId,
      subtask.id,
    );

    if (success && mounted) {
      _loadTaskDetails(); // Refresh data
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Failed to update subtask'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text(
          'Task Details',
          style: TextStyle(
            color: Colors.black87,
            fontSize: 20,
            fontWeight: FontWeight.w600,
          ),
        ),
        actions: [
          IconButton(
            onPressed: _loadTaskDetails,
            icon: const Icon(Icons.refresh, color: Colors.black54),
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _task == null
              ? const Center(
                  child: Text(
                    'Task not found',
                    style: TextStyle(fontSize: 18, color: Colors.grey),
                  ),
                )
              : Column(
                  children: [
                    // Task Header
                    Container(
                      color: Colors.white,
                      padding: const EdgeInsets.all(16),
                      child: _buildTaskHeader(),
                    ),
                    
                    // Tabs
                    Container(
                      color: Colors.white,
                      child: TabBar(
                        controller: _tabController,
                        labelColor: const Color(0xFF1D4ED8),
                        unselectedLabelColor: Colors.grey,
                        indicatorColor: const Color(0xFF1D4ED8),
                        tabs: const [
                          Tab(text: 'Details'),
                          Tab(text: 'Subtasks'),
                          Tab(text: 'Comments'),
                        ],
                      ),
                    ),
                    
                    // Tab Content
                    Expanded(
                      child: TabBarView(
                        controller: _tabController,
                        children: [
                          _buildDetailsTab(),
                          _buildSubtasksTab(),
                          _buildCommentsTab(),
                        ],
                      ),
                    ),
                  ],
                ),
      // Action buttons at bottom
      bottomSheet: _task != null ? _buildActionButtons() : null,
    );
  }

  Widget _buildTaskHeader() {
    Color statusColor = Colors.grey;
    IconData statusIcon = Icons.circle;
    
    switch (_task!.status) {
      case 'todo':
        statusColor = Colors.blue;
        statusIcon = Icons.radio_button_unchecked;
        break;
      case 'in_progress':
        statusColor = Colors.orange;
        statusIcon = Icons.play_circle_outline;
        break;
      case 'review':
        statusColor = Colors.purple;
        statusIcon = Icons.rate_review;
        break;
      case 'done':
        statusColor = Colors.green;
        statusIcon = Icons.check_circle;
        break;
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Status and Priority
        Row(
          children: [
            Icon(statusIcon, color: statusColor, size: 20),
            const SizedBox(width: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: statusColor.withOpacity(0.1),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                _task!.statusText,
                style: TextStyle(
                  fontSize: 12,
                  color: statusColor,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
            const Spacer(),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: _task!.priority == 'high'
                    ? Colors.red.withOpacity(0.1)
                    : _task!.priority == 'medium'
                        ? Colors.orange.withOpacity(0.1)
                        : Colors.green.withOpacity(0.1),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                _task!.priorityText,
                style: TextStyle(
                  fontSize: 12,
                  color: _task!.priority == 'high'
                      ? Colors.red
                      : _task!.priority == 'medium'
                          ? Colors.orange
                          : Colors.green,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ],
        ),
        
        const SizedBox(height: 12),
        
        // Title
        Text(
          _task!.cardTitle,
          style: const TextStyle(
            fontSize: 20,
            fontWeight: FontWeight.bold,
            color: Colors.black87,
          ),
        ),
        
        const SizedBox(height: 8),
        
        // Meta info
        Row(
          children: [
            if (_task!.dueDate != null) ...[
              Icon(
                Icons.calendar_today,
                size: 16,
                color: Colors.grey[600],
              ),
              const SizedBox(width: 4),
              Text(
                'Due: ${_task!.dueDate!.day}/${_task!.dueDate!.month}/${_task!.dueDate!.year}',
                style: TextStyle(
                  fontSize: 12,
                  color: Colors.grey[600],
                ),
              ),
              const SizedBox(width: 16),
            ],
            if (_task!.user != null) ...[
              Icon(
                Icons.person,
                size: 16,
                color: Colors.grey[600],
              ),
              const SizedBox(width: 4),
              Text(
                'Assigned: ${_task!.user!.name}',
                style: TextStyle(
                  fontSize: 12,
                  color: Colors.grey[600],
                ),
              ),
            ],
          ],
        ),
      ],
    );
  }

  Widget _buildDetailsTab() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (_task!.description != null && _task!.description!.isNotEmpty) ...[
            const Text(
              'Description',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.w600,
                color: Colors.black87,
              ),
            ),
            const SizedBox(height: 8),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.05),
                    blurRadius: 4,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              child: Text(
                _task!.description!,
                style: const TextStyle(
                  fontSize: 14,
                  color: Colors.black87,
                  height: 1.5,
                ),
              ),
            ),
            const SizedBox(height: 24),
          ],
          
          // Task Information
          const Text(
            'Task Information',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.w600,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 12),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 4,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Column(
              children: [
                _buildInfoRow('Priority', _task!.priorityText),
                _buildInfoRow('Status', _task!.statusText),
                if (_task!.estimatedHours != null)
                  _buildInfoRow('Estimated Hours', '${_task!.estimatedHours} hours'),
                if (_task!.actualHours != null)
                  _buildInfoRow('Actual Hours', '${_task!.actualHours} hours'),
                if (_task!.startedAt != null)
                  _buildInfoRow('Started At', '${_task!.startedAt!.day}/${_task!.startedAt!.month}/${_task!.startedAt!.year}'),
                _buildInfoRow('Created At', '${_task!.createdAt.day}/${_task!.createdAt.month}/${_task!.createdAt.year}'),
              ],
            ),
          ),
          
          const SizedBox(height: 80), // Space for action buttons
        ],
      ),
    );
  }

  Widget _buildSubtasksTab() {
    return Column(
      children: [
        // Add Subtask Button
        Container(
          padding: const EdgeInsets.all(16),
          child: SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _showAddSubtaskDialog,
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF1D4ED8),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.all(12),
              ),
              child: const Text('Add Subtask'),
            ),
          ),
        ),
        
        // Subtasks List
        Expanded(
          child: _subtasks.isEmpty
              ? const Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(
                        Icons.checklist,
                        size: 64,
                        color: Colors.grey,
                      ),
                      SizedBox(height: 16),
                      Text(
                        'No subtasks found',
                        style: TextStyle(
                          fontSize: 18,
                          color: Colors.grey,
                        ),
                      ),
                    ],
                  ),
                )
              : ListView.builder(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  itemCount: _subtasks.length,
                  itemBuilder: (context, index) {
                    final subtask = _subtasks[index];
                    return _buildSubtaskItem(subtask);
                  },
                ),
        ),
        
        const SizedBox(height: 80), // Space for action buttons
      ],
    );
  }

  Widget _buildCommentsTab() {
    return Column(
      children: [
        // Add Comment Section
        Container(
          padding: const EdgeInsets.all(16),
          color: Colors.white,
          child: Row(
            children: [
              Expanded(
                child: TextField(
                  controller: _commentController,
                  decoration: const InputDecoration(
                    hintText: 'Add a comment...',
                    border: OutlineInputBorder(),
                  ),
                  maxLines: 2,
                ),
              ),
              const SizedBox(width: 8),
              IconButton(
                onPressed: _addComment,
                icon: const Icon(
                  Icons.send,
                  color: Color(0xFF1D4ED8),
                ),
              ),
            ],
          ),
        ),
        
        // Comments List
        Expanded(
          child: _comments.isEmpty
              ? const Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(
                        Icons.comment,
                        size: 64,
                        color: Colors.grey,
                      ),
                      SizedBox(height: 16),
                      Text(
                        'No comments yet',
                        style: TextStyle(
                          fontSize: 18,
                          color: Colors.grey,
                        ),
                      ),
                    ],
                  ),
                )
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _comments.length,
                  itemBuilder: (context, index) {
                    final comment = _comments[index];
                    return _buildCommentItem(comment);
                  },
                ),
        ),
        
        const SizedBox(height: 80), // Space for action buttons
      ],
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(
              label,
              style: TextStyle(
                fontSize: 14,
                color: Colors.grey[600],
              ),
            ),
          ),
          const Text(': '),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 14,
                color: Colors.black87,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSubtaskItem(Subtask subtask) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 2,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Row(
        children: [
          GestureDetector(
            onTap: () => _toggleSubtask(subtask),
            child: Container(
              width: 24,
              height: 24,
              decoration: BoxDecoration(
                border: Border.all(
                  color: subtask.status == 'done' 
                      ? Colors.green 
                      : Colors.grey,
                ),
                borderRadius: BorderRadius.circular(4),
                color: subtask.status == 'done' 
                    ? Colors.green 
                    : Colors.transparent,
              ),
              child: subtask.status == 'done'
                  ? const Icon(
                      Icons.check,
                      size: 16,
                      color: Colors.white,
                    )
                  : null,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  subtask.title,
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: Colors.black87,
                    decoration: subtask.status == 'done'
                        ? TextDecoration.lineThrough
                        : null,
                  ),
                ),
                if (subtask.description != null && subtask.description!.isNotEmpty) ...[
                  const SizedBox(height: 4),
                  Text(
                    subtask.description!,
                    style: TextStyle(
                      fontSize: 12,
                      color: Colors.grey[600],
                    ),
                  ),
                ],
                if (subtask.estimatedHours != null || subtask.actualHours != null) ...[
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      const Icon(
                        Icons.access_time,
                        size: 12,
                        color: Colors.grey,
                      ),
                      const SizedBox(width: 4),
                      Text(
                        subtask.actualHours != null
                            ? '${subtask.actualHours}h (actual)'
                            : '${subtask.estimatedHours}h (est.)',
                        style: TextStyle(
                          fontSize: 11,
                          color: Colors.grey[600],
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                ],
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
            decoration: BoxDecoration(
              color: subtask.status == 'done'
                  ? Colors.green.withOpacity(0.1)
                  : subtask.status == 'in_progress'
                      ? Colors.orange.withOpacity(0.1)
                      : Colors.blue.withOpacity(0.1),
              borderRadius: BorderRadius.circular(4),
            ),
            child: Text(
              subtask.statusText,
              style: TextStyle(
                fontSize: 10,
                color: subtask.status == 'done'
                    ? Colors.green
                    : subtask.status == 'in_progress'
                        ? Colors.orange
                        : Colors.blue,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCommentItem(Comment comment) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 4,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 32,
                height: 32,
                decoration: BoxDecoration(
                  color: const Color(0xFF1D4ED8).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Center(
                  child: Text(
                    comment.user?.name.isNotEmpty == true
                        ? comment.user!.name.substring(0, 1).toUpperCase()
                        : 'U',
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF1D4ED8),
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      comment.user?.name ?? 'Unknown User',
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: Colors.black87,
                      ),
                    ),
                    Text(
                      '${comment.createdAt.day}/${comment.createdAt.month}/${comment.createdAt.year}',
                      style: TextStyle(
                        fontSize: 12,
                        color: Colors.grey[600],
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            comment.content,
            style: const TextStyle(
              fontSize: 14,
              color: Colors.black87,
              height: 1.4,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildActionButtons() {
    if (_task!.status == 'done' || _task!.status == 'review') {
      return const SizedBox.shrink();
    }

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: const BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black12,
            blurRadius: 4,
            offset: Offset(0, -2),
          ),
        ],
      ),
      child: Row(
        children: [
          if (_task!.status == 'todo')
            Expanded(
              child: ElevatedButton(
                onPressed: () => _onTaskAction('start'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.blue,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.all(12),
                ),
                child: const Text('Start Task'),
              ),
            )
          else if (_task!.status == 'in_progress')
            Expanded(
              child: ElevatedButton(
                onPressed: () => _onTaskAction('submit'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.green,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.all(12),
                ),
                child: const Text('Submit for Review'),
              ),
            ),
        ],
      ),
    );
  }

  void _showAddSubtaskDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Add Subtask'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: _subtaskTitleController,
                decoration: const InputDecoration(
                  labelText: 'Title',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: _subtaskDescController,
                decoration: const InputDecoration(
                  labelText: 'Description (optional)',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
              const SizedBox(height: 16),
              TextField(
                controller: _subtaskEstimatedHoursController,
                decoration: const InputDecoration(
                  labelText: 'Estimated Hours',
                  border: OutlineInputBorder(),
                  hintText: 'e.g., 2',
                  suffixIcon: Icon(Icons.access_time),
                ),
                keyboardType: TextInputType.number,
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () {
              _subtaskTitleController.clear();
              _subtaskDescController.clear();
              _subtaskEstimatedHoursController.clear();
              Navigator.of(context).pop();
            },
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: _addSubtask,
            child: const Text('Add'),
          ),
        ],
      ),
    );
  }
}