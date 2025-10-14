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
      id: json['id'],
      cardId: json['card_id'],
      title: json['title'],
      description: json['description'],
      status: json['status'],
      estimatedHours: json['estimated_hours']?.toDouble(),
      actualHours: json['actual_hours']?.toDouble(),
      dueDate: json['due_date'] != null ? DateTime.parse(json['due_date']) : null,
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
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
}