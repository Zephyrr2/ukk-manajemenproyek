import 'task_card.dart';

class Board {
  final int id;
  final int projectId;
  final String boardName;
  final DateTime createdAt;
  final DateTime updatedAt;
  final List<TaskCard>? cards;

  Board({
    required this.id,
    required this.projectId,
    required this.boardName,
    required this.createdAt,
    required this.updatedAt,
    this.cards,
  });

  factory Board.fromJson(Map<String, dynamic> json) {
    return Board(
      id: json['id'],
      projectId: json['project_id'],
      boardName: json['board_name'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      cards: json['cards'] != null
          ? (json['cards'] as List)
              .map((item) => TaskCard.fromJson(item))
              .toList()
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'project_id': projectId,
      'board_name': boardName,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'cards': cards?.map((item) => item.toJson()).toList(),
    };
  }
}