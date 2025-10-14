import 'project.dart';
import 'task_card.dart';

class DashboardData {
  final int totalProjects;
  final int totalTasks;
  final int completedTasks;
  final int activeTasks;
  final int overdueTasks;
  final List<Project>? recentProjects;
  final List<TaskCard>? upcomingTasks;

  DashboardData({
    required this.totalProjects,
    required this.totalTasks,
    required this.completedTasks,
    required this.activeTasks,
    required this.overdueTasks,
    this.recentProjects,
    this.upcomingTasks,
  });

  factory DashboardData.fromJson(Map<String, dynamic> json) {
    return DashboardData(
      totalProjects: json['total_projects'] ?? 0,
      totalTasks: json['total_tasks'] ?? 0,
      completedTasks: json['completed_tasks'] ?? 0,
      activeTasks: json['active_tasks'] ?? 0,
      overdueTasks: json['overdue_tasks'] ?? 0,
      recentProjects: json['recent_projects'] != null
          ? (json['recent_projects'] as List)
              .map((item) => Project.fromJson(item))
              .toList()
          : null,
      upcomingTasks: json['upcoming_tasks'] != null
          ? (json['upcoming_tasks'] as List)
              .map((item) => TaskCard.fromJson(item))
              .toList()
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'total_projects': totalProjects,
      'total_tasks': totalTasks,
      'completed_tasks': completedTasks,
      'active_tasks': activeTasks,
      'overdue_tasks': overdueTasks,
      'recent_projects': recentProjects?.map((item) => item.toJson()).toList(),
      'upcoming_tasks': upcomingTasks?.map((item) => item.toJson()).toList(),
    };
  }
}

class TaskStatistics {
  final int total;
  final int todo;
  final int inProgress;
  final int review;
  final int done;

  TaskStatistics({
    required this.total,
    required this.todo,
    required this.inProgress,
    required this.review,
    required this.done,
  });

  factory TaskStatistics.fromJson(Map<String, dynamic> json) {
    return TaskStatistics(
      total: json['total'] ?? 0,
      todo: json['todo'] ?? 0,
      inProgress: json['in_progress'] ?? 0,
      review: json['review'] ?? 0,
      done: json['done'] ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'total': total,
      'todo': todo,
      'in_progress': inProgress,
      'review': review,
      'done': done,
    };
  }
}