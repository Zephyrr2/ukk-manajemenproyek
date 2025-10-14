import 'user.dart';

class AuthResponse {
  final bool success;
  final String message;
  final AuthData? data;
  final String? error;

  AuthResponse({
    required this.success,
    required this.message,
    this.data,
    this.error,
  });

  factory AuthResponse.fromJson(Map<String, dynamic> json) {
    return AuthResponse(
      success: json['success'] ?? false,
      message: json['message'] ?? 'Unknown error',
      data: json['data'] != null ? AuthData.fromJson(json['data']) : null,
      error: json['error'],
    );
  }
}

class AuthData {
  final User user;
  final String accessToken;
  final String tokenType;

  AuthData({
    required this.user,
    required this.accessToken,
    required this.tokenType,
  });

  factory AuthData.fromJson(Map<String, dynamic> json) {
    return AuthData(
      user: User.fromJson(json['user']),
      accessToken: json['access_token'],
      tokenType: json['token_type'],
    );
  }
}
