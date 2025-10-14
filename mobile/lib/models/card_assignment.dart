import 'user.dart';

class CardAssignment {
  final int id;
  final int cardId;
  final int userId;
  final DateTime? assignedAt;
  final DateTime createdAt;
  final DateTime updatedAt;
  final User? user;

  CardAssignment({
    required this.id,
    required this.cardId,
    required this.userId,
    this.assignedAt,
    required this.createdAt,
    required this.updatedAt,
    this.user,
  });

  factory CardAssignment.fromJson(Map<String, dynamic> json) {
    return CardAssignment(
      id: json['id'],
      cardId: json['card_id'],
      userId: json['user_id'],
      assignedAt: json['assigned_at'] != null ? DateTime.parse(json['assigned_at']) : null,
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'card_id': cardId,
      'user_id': userId,
      'assigned_at': assignedAt?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'user': user?.toJson(),
    };
  }
}