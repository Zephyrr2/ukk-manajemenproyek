import 'user.dart';
import 'board.dart';
import 'subtask.dart';
import 'card_assignment.dart';
import 'comment.dart';

class TaskCard {
  final int id;
  final int boardId;
  final int? userId;
  final String cardTitle;
  final String? slug;
  final String? description;
  final int? position;
  final DateTime? dueDate;
  final String status;
  final String priority;
  final double? estimatedHours;
  final double? actualHours;
  final DateTime? startedAt;
  final DateTime createdAt;
  final DateTime updatedAt;
  final Board? board;
  final User? user;
  final List<Subtask>? subtasks;
  final List<CardAssignment>? assignments;
  final List<Comment>? comments;

  TaskCard({
    required this.id,
    required this.boardId,
    this.userId,
    required this.cardTitle,
    this.slug,
    this.description,
    this.position,
    this.dueDate,
    required this.status,
    required this.priority,
    this.estimatedHours,
    this.actualHours,
    this.startedAt,
    required this.createdAt,
    required this.updatedAt,
    this.board,
    this.user,
    this.subtasks,
    this.assignments,
    this.comments,
  });

  factory TaskCard.fromJson(Map<String, dynamic> json) {
    // Validate required fields
    if (json['id'] == null) {
      throw Exception('Task ID cannot be null');
    }
    if (json['board_id'] == null) {
      throw Exception('Board ID cannot be null');
    }
    
    return TaskCard(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      boardId: json['board_id'] is int ? json['board_id'] : int.parse(json['board_id'].toString()),
      userId: json['user_id'] != null 
          ? (json['user_id'] is int ? json['user_id'] : int.parse(json['user_id'].toString()))
          : null,
      cardTitle: json['card_title']?.toString() ?? json['title']?.toString() ?? 'Untitled',
      slug: json['slug']?.toString(),
      description: json['description']?.toString(),
      position: json['position'] != null
          ? (json['position'] is int ? json['position'] : int.tryParse(json['position'].toString()))
          : null,
      dueDate: json['due_date'] != null ? DateTime.parse(json['due_date']) : null,
      status: json['status']?.toString() ?? 'todo',
      priority: json['priority']?.toString() ?? 'medium',
      estimatedHours: json['estimated_hours'] != null 
          ? double.tryParse(json['estimated_hours'].toString())
          : null,
      actualHours: json['actual_hours'] != null 
          ? double.tryParse(json['actual_hours'].toString())
          : null,
      startedAt: json['started_at'] != null ? DateTime.parse(json['started_at']) : null,
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at'])
          : DateTime.now(),
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : DateTime.now(),
      board: json['board'] != null ? Board.fromJson(json['board']) : null,
      user: json['user'] != null ? User.fromJson(json['user']) : null,
      subtasks: json['subtasks'] != null
          ? (json['subtasks'] as List)
              .map((item) => Subtask.fromJson(item))
              .toList()
          : null,
      assignments: json['assignments'] != null
          ? (json['assignments'] as List)
              .map((item) => CardAssignment.fromJson(item))
              .toList()
          : null,
      comments: json['comments'] != null
          ? (json['comments'] as List)
              .map((item) => Comment.fromJson(item))
              .toList()
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'board_id': boardId,
      'user_id': userId,
      'card_title': cardTitle,
      'slug': slug,
      'description': description,
      'position': position,
      'due_date': dueDate?.toIso8601String(),
      'status': status,
      'priority': priority,
      'estimated_hours': estimatedHours,
      'actual_hours': actualHours,
      'started_at': startedAt?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'board': board?.toJson(),
      'user': user?.toJson(),
      'subtasks': subtasks?.map((item) => item.toJson()).toList(),
      'assignments': assignments?.map((item) => item.toJson()).toList(),
      'comments': comments?.map((item) => item.toJson()).toList(),
    };
  }

  String get statusText {
    switch (status) {
      case 'todo':
        return 'To Do';
      case 'in_progress':
        return 'In Progress';
      case 'review':
        return 'Review';
      case 'done':
        return 'Done';
      default:
        return status;
    }
  }

  String get priorityText {
    switch (priority) {
      case 'low':
        return 'Low';
      case 'medium':
        return 'Medium';
      case 'high':
        return 'High';
      default:
        return priority;
    }
  }

  // Alias untuk compatibility
  String get title => cardTitle;

  // Format due date
  String getFormattedDueDate() {
    if (dueDate == null) return 'No deadline';
    
    final now = DateTime.now();
    final difference = dueDate!.difference(now).inDays;
    
    if (difference < 0) {
      return 'Overdue';
    } else if (difference == 0) {
      return 'Today';
    } else if (difference == 1) {
      return 'Tomorrow';
    } else if (difference <= 7) {
      return '$difference days left';
    } else {
      return '${dueDate!.day}/${dueDate!.month}/${dueDate!.year}';
    }
  }
}