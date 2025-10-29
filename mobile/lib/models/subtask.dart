class Subtask {
  final int id;
  final int cardId;
  final String title;
  final String? description;
  final String status;
  final double? estimatedHours;
  final double? actualHours;
  final DateTime? dueDate;
  final DateTime createdAt;
  final DateTime updatedAt;

  Subtask({
    required this.id,
    required this.cardId,
    required this.title,
    this.description,
    required this.status,
    this.estimatedHours,
    this.actualHours,
    this.dueDate,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Subtask.fromJson(Map<String, dynamic> json) {
    return Subtask(
      id: json['id'] as int,
      cardId: json['card_id'] as int,
      title: json['subtask_title'] ?? json['title'] ?? '',
      description: json['description'] as String?,
      status: json['status'] as String,
      estimatedHours: json['estimated_hours'] != null 
          ? double.tryParse(json['estimated_hours'].toString())
          : null,
      actualHours: json['actual_hours'] != null 
          ? double.tryParse(json['actual_hours'].toString())
          : null,
      dueDate: json['due_date'] != null ? DateTime.parse(json['due_date'] as String) : null,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'card_id': cardId,
      'title': title,
      'description': description,
      'status': status,
      'estimated_hours': estimatedHours,
      'actual_hours': actualHours,
      'due_date': dueDate?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }

  String get statusText {
    switch (status) {
      case 'todo':
        return 'To Do';
      case 'in_progress':
        return 'In Progress';
      case 'done':
        return 'Done';
      default:
        return status;
    }
  }

  bool get isDone => status == 'done';
  bool get isInProgress => status == 'in_progress';
  bool get isTodo => status == 'todo';
}