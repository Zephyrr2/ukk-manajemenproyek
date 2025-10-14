import 'user.dart';

class Comment {
  final int id;
  final String commentableType;
  final int commentableId;
  final int userId;
  final String content;
  final DateTime createdAt;
  final DateTime updatedAt;
  final User? user;

  Comment({
    required this.id,
    required this.commentableType,
    required this.commentableId,
    required this.userId,
    required this.content,
    required this.createdAt,
    required this.updatedAt,
    this.user,
  });

  factory Comment.fromJson(Map<String, dynamic> json) {
    return Comment(
      id: json['id'],
      commentableType: json['commentable_type'],
      commentableId: json['commentable_id'],
      userId: json['user_id'],
      content: json['content'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'commentable_type': commentableType,
      'commentable_id': commentableId,
      'user_id': userId,
      'content': content,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'user': user?.toJson(),
    };
  }
}