import 'user.dart';
import 'board.dart';

class Project {
  final int id;
  final int userId;
  final String projectName;
  final String slug;
  final String? description;
  final DateTime? deadline;
  final DateTime createdAt;
  final DateTime updatedAt;
  final User? user;
  final List<ProjectMember>? projectMembers;
  final List<Board>? boards;
  final int? totalTasks;
  final int? completedTasks;
  final int? progressPercentage;

  Project({
    required this.id,
    required this.userId,
    required this.projectName,
    required this.slug,
    this.description,
    this.deadline,
    required this.createdAt,
    required this.updatedAt,
    this.user,
    this.projectMembers,
    this.boards,
    this.totalTasks,
    this.completedTasks,
    this.progressPercentage,
  });

  factory Project.fromJson(Map<String, dynamic> json) {
    return Project(
      id: json['id'],
      userId: json['user_id'],
      projectName: json['project_name'],
      slug: json['slug'],
      description: json['description'],
      deadline: json['deadline'] != null ? DateTime.parse(json['deadline']) : null,
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      user: json['user'] != null ? User.fromJson(json['user']) : null,
      projectMembers: json['project_members'] != null
          ? (json['project_members'] as List)
              .map((item) => ProjectMember.fromJson(item))
              .toList()
          : null,
      boards: json['boards'] != null
          ? (json['boards'] as List)
              .map((item) => Board.fromJson(item))
              .toList()
          : null,
      totalTasks: json['total_tasks'],
      completedTasks: json['completed_tasks'],
      progressPercentage: json['progress_percentage'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'project_name': projectName,
      'slug': slug,
      'description': description,
      'deadline': deadline?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'user': user?.toJson(),
      'project_members': projectMembers?.map((item) => item.toJson()).toList(),
      'boards': boards?.map((item) => item.toJson()).toList(),
      'total_tasks': totalTasks,
      'completed_tasks': completedTasks,
      'progress_percentage': progressPercentage,
    };
  }
}

class ProjectMember {
  final int id;
  final int projectId;
  final int userId;
  final String role;
  final DateTime? joinedAt;
  final User? user;

  ProjectMember({
    required this.id,
    required this.projectId,
    required this.userId,
    required this.role,
    this.joinedAt,
    this.user,
  });

  factory ProjectMember.fromJson(Map<String, dynamic> json) {
    return ProjectMember(
      id: json['id'],
      projectId: json['project_id'],
      userId: json['user_id'],
      role: json['role'],
      joinedAt: json['joined_at'] != null ? DateTime.parse(json['joined_at']) : null,
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'project_id': projectId,
      'user_id': userId,
      'role': role,
      'joined_at': joinedAt?.toIso8601String(),
      'user': user?.toJson(),
    };
  }
}