import 'package:flutter/material.dart';
import '../models/subtask.dart';
import '../models/task_card.dart';
import '../services/api_service.dart';
import '../utils/constants.dart';

class SubtaskScreen extends StatefulWidget {
  final TaskCard task;

  const SubtaskScreen({super.key, required this.task});

  @override
  State<SubtaskScreen> createState() => _SubtaskScreenState();
}

class _SubtaskScreenState extends State<SubtaskScreen> {
  List<Subtask> _subtasks = [];
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _loadSubtasks();
  }

  Future<void> _loadSubtasks() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final data = await ApiService.getTaskSubtasks(widget.task.id);
      if (data != null) {
        setState(() {
          _subtasks = data.map((json) => Subtask.fromJson(json)).toList();
          _isLoading = false;
        });
      } else {
        setState(() {
          _errorMessage = 'Gagal memuat subtask';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Error: $e';
        _isLoading = false;
      });
    }
  }

  Future<void> _toggleSubtask(Subtask subtask) async {
    final success = await ApiService.toggleSubtaskStatus(widget.task.id, subtask.id);
    if (success) {
      _loadSubtasks(); // Reload data
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Gagal mengubah status subtask'),
            backgroundColor: AppColors.error,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final totalSubtasks = _subtasks.length;
    final inProgressSubtasks = _subtasks.where((s) => s.isInProgress).length;
    final doneSubtasks = _subtasks.where((s) => s.isDone).length;
    final totalEstimated = _subtasks.fold<double>(
      0,
      (sum, s) => sum + (s.estimatedHours ?? 0),
    );

    return Scaffold(
      appBar: AppBar(
        title: const Text('Subtasks'),
        backgroundColor: AppColors.primary,
        foregroundColor: AppColors.onPrimary,
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _showAddSubtaskDialog,
        backgroundColor: AppColors.primary,
        child: const Icon(Icons.add, color: AppColors.onPrimary),
      ),
      body: Column(
        children: [
          // Task Information Card
          Container(
            width: double.infinity,
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: [
                BoxShadow(
                  color: Colors.grey.withOpacity(0.1),
                  spreadRadius: 1,
                  blurRadius: 4,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Padding(
              padding: const EdgeInsets.all(AppSpacing.lg),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Text(
                        'Task Information',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const Spacer(),
                      _buildStatusChip(widget.task.status),
                    ],
                  ),
                  const SizedBox(height: AppSpacing.sm),
                  Text(
                    widget.task.title,
                    style: const TextStyle(
                      fontSize: 14,
                      color: Colors.grey,
                    ),
                  ),
                ],
              ),
            ),
          ),

          // Statistics Card
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(AppSpacing.lg),
            decoration: BoxDecoration(
              color: Colors.white,
              border: Border(
                top: BorderSide(color: Colors.grey.shade200),
              ),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildStatItem('Total', totalSubtasks.toString(), Colors.grey),
                _buildStatItem('In Progress', inProgressSubtasks.toString(), Colors.orange),
                _buildStatItem('Completed', doneSubtasks.toString(), Colors.green),
                _buildStatItem('Est. Hours', totalEstimated.toStringAsFixed(1) + 'h', Colors.blue),
              ],
            ),
          ),

          const SizedBox(height: AppSpacing.md),

          // Subtasks List
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _errorMessage != null
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.error_outline, size: 64, color: Colors.grey[400]),
                            const SizedBox(height: AppSpacing.md),
                            Text(
                              _errorMessage!,
                              style: TextStyle(color: Colors.grey[600]),
                            ),
                            const SizedBox(height: AppSpacing.md),
                            ElevatedButton.icon(
                              onPressed: _loadSubtasks,
                              icon: const Icon(Icons.refresh),
                              label: const Text('Coba Lagi'),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: AppColors.primary,
                                foregroundColor: AppColors.onPrimary,
                              ),
                            ),
                          ],
                        ),
                      )
                    : _subtasks.isEmpty
                        ? Center(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.inbox_outlined, size: 64, color: Colors.grey[400]),
                                const SizedBox(height: AppSpacing.md),
                                Text(
                                  'Tidak ada subtask',
                                  style: TextStyle(
                                    fontSize: 16,
                                    color: Colors.grey[600],
                                  ),
                                ),
                              ],
                            ),
                          )
                        : RefreshIndicator(
                            onRefresh: _loadSubtasks,
                            child: ListView.separated(
                              padding: const EdgeInsets.all(AppSpacing.md),
                              itemCount: _subtasks.length,
                              separatorBuilder: (context, index) => const SizedBox(height: AppSpacing.sm),
                              itemBuilder: (context, index) {
                                final subtask = _subtasks[index];
                                return _buildSubtaskCard(subtask);
                              },
                            ),
                          ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatItem(String label, String value, Color color) {
    return Column(
      children: [
        Text(
          value,
          style: TextStyle(
            fontSize: 24,
            fontWeight: FontWeight.bold,
            color: color,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          label,
          style: const TextStyle(
            fontSize: 12,
            color: Colors.grey,
          ),
        ),
      ],
    );
  }

  Widget _buildStatusChip(String status) {
    Color color;
    String text;
    
    switch (status) {
      case 'todo':
        color = Colors.grey;
        text = 'To Do';
        break;
      case 'in_progress':
        color = Colors.orange;
        text = 'In Progress';
        break;
      case 'review':
        color = Colors.blue;
        text = 'Review';
        break;
      case 'done':
        color = Colors.green;
        text = 'Done';
        break;
      default:
        color = Colors.grey;
        text = status;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Text(
        text,
        style: TextStyle(
          fontSize: 12,
          fontWeight: FontWeight.w600,
          color: color,
        ),
      ),
    );
  }

  Widget _buildSubtaskCard(Subtask subtask) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(AppSizes.borderRadius),
        side: BorderSide(
          color: subtask.isDone ? Colors.green.shade200 : Colors.grey.shade200,
          width: 1,
        ),
      ),
      color: subtask.isDone ? Colors.green.shade50 : Colors.white,
      child: Padding(
        padding: const EdgeInsets.all(AppSpacing.md),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Checkbox
            InkWell(
              onTap: () => _toggleSubtask(subtask),
              child: Container(
                width: 28,
                height: 28,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(
                    color: subtask.isDone ? Colors.green : Colors.grey,
                    width: 2,
                  ),
                  color: subtask.isDone ? Colors.green : Colors.transparent,
                ),
                child: subtask.isDone
                    ? const Icon(
                        Icons.check,
                        color: Colors.white,
                        size: 18,
                      )
                    : null,
              ),
            ),
            const SizedBox(width: AppSpacing.md),

            // Content
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    subtask.title,
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      decoration: subtask.isDone ? TextDecoration.lineThrough : null,
                      color: subtask.isDone ? Colors.grey : Colors.black87,
                    ),
                  ),
                  if (subtask.description != null && subtask.description!.isNotEmpty) ...[
                    const SizedBox(height: AppSpacing.xs),
                    Text(
                      subtask.description!,
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.grey[600],
                        decoration: subtask.isDone ? TextDecoration.lineThrough : null,
                      ),
                    ),
                  ],
                  const SizedBox(height: AppSpacing.sm),
                  
                  // Info Row
                  Wrap(
                    spacing: AppSpacing.md,
                    runSpacing: AppSpacing.xs,
                    children: [
                      if (subtask.estimatedHours != null)
                        Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Icon(Icons.access_time, size: 14, color: Colors.grey),
                            const SizedBox(width: 4),
                            Text(
                              'Est: ${subtask.estimatedHours}h',
                              style: const TextStyle(
                                fontSize: 12,
                                color: Colors.grey,
                              ),
                            ),
                          ],
                        ),
                      if (subtask.actualHours != null)
                        Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Icon(Icons.check_circle_outline, size: 14, color: Colors.grey),
                            const SizedBox(width: 4),
                            Text(
                              'Actual: ${subtask.actualHours}h',
                              style: const TextStyle(
                                fontSize: 12,
                                color: Colors.grey,
                              ),
                            ),
                          ],
                        ),
                      _buildStatusChip(subtask.status),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showAddSubtaskDialog() {
    final titleController = TextEditingController();
    final descriptionController = TextEditingController();
    final hoursController = TextEditingController();

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Tambah Subtask'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: titleController,
                decoration: const InputDecoration(
                  labelText: 'Judul Subtask *',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: descriptionController,
                decoration: const InputDecoration(
                  labelText: 'Deskripsi',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
              const SizedBox(height: 16),
              TextField(
                controller: hoursController,
                decoration: const InputDecoration(
                  labelText: 'Estimasi Jam',
                  border: OutlineInputBorder(),
                  suffixText: 'jam',
                ),
                keyboardType: TextInputType.number,
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            onPressed: () async {
              if (titleController.text.trim().isEmpty) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Judul subtask harus diisi')),
                );
                return;
              }

              // Simpan context sebelum async operation
              final navigator = Navigator.of(context);
              final scaffoldMessenger = ScaffoldMessenger.of(context);

              navigator.pop();

              // Show loading
              showDialog(
                context: context,
                barrierDismissible: false,
                builder: (context) => const Center(
                  child: CircularProgressIndicator(),
                ),
              );

              final result = await ApiService.createSubtask(
                taskId: widget.task.id,
                title: titleController.text.trim(),
                description: descriptionController.text.trim().isEmpty
                    ? null
                    : descriptionController.text.trim(),
                estimatedHours: hoursController.text.trim().isEmpty
                    ? null
                    : double.tryParse(hoursController.text.trim()),
              );

              // Hide loading
              if (mounted) navigator.pop();

              if (result != null) {
                if (mounted) {
                  scaffoldMessenger.showSnackBar(
                    const SnackBar(content: Text('Subtask berhasil ditambahkan')),
                  );
                  _loadSubtasks();
                }
              } else {
                if (mounted) {
                  scaffoldMessenger.showSnackBar(
                    const SnackBar(content: Text('Gagal menambahkan subtask')),
                  );
                }
              }
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: AppColors.onPrimary,
            ),
            child: const Text('Simpan'),
          ),
        ],
      ),
    );
  }
}
