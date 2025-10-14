import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/auth_response.dart';
import '../models/user.dart';
import '../models/task_card.dart';

class ApiService {
  static const String baseUrl = 'http://127.0.0.1:8000/api'; // URL Laravel backend Anda
  
  // Headers untuk request
  static Map<String, String> get headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  // Headers dengan authorization token
  static Future<Map<String, String>> get authHeaders async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  // Simpan token ke SharedPreferences
  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  // Ambil token dari SharedPreferences
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  // Hapus token dari SharedPreferences
  static Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // Simpan user data ke SharedPreferences
  static Future<void> saveUser(User user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('user_data', jsonEncode(user.toJson()));
  }

  // Ambil user data dari SharedPreferences
  static Future<User?> getUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userData = prefs.getString('user_data');
    if (userData != null) {
      return User.fromJson(jsonDecode(userData));
    }
    return null;
  }

  // Hapus user data dari SharedPreferences
  static Future<void> removeUser() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('user_data');
  }

  // Cek apakah user sudah login
  static Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null;
  }

  // Register user
  static Future<AuthResponse> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: headers,
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        }),
      );

      final responseBody = jsonDecode(response.body);
      
      // Handle validation errors (422)
      if (response.statusCode == 422) {
        final errors = responseBody['errors'] as Map<String, dynamic>?;
        String errorMessage = 'Validation failed';
        if (errors != null && errors.isNotEmpty) {
          errorMessage = errors.values.first[0].toString();
        }
        return AuthResponse(
          success: false,
          message: errorMessage,
        );
      }

      final authResponse = AuthResponse.fromJson(responseBody);
      
      if (authResponse.success && authResponse.data != null) {
        await saveToken(authResponse.data!.accessToken);
        await saveUser(authResponse.data!.user);
      }

      return authResponse;
    } catch (e) {
      return AuthResponse(
        success: false,
        message: 'Network error: ${e.toString()}',
      );
    }
  }

  // Login user
  static Future<AuthResponse> login({
    required String email,
    required String password,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: headers,
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      final authResponse = AuthResponse.fromJson(jsonDecode(response.body));
      
      if (authResponse.success && authResponse.data != null) {
        await saveToken(authResponse.data!.accessToken);
        await saveUser(authResponse.data!.user);
      }

      return authResponse;
    } catch (e) {
      return AuthResponse(
        success: false,
        message: 'Network error: ${e.toString()}',
      );
    }
  }

  // Logout user
  static Future<bool> logout() async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: await authHeaders,
      );

      // Hapus token dan user data meskipun request gagal
      await removeToken();
      await removeUser();

      return response.statusCode == 200;
    } catch (e) {
      // Tetap hapus data lokal meskipun gagal request ke server
      await removeToken();
      await removeUser();
      return false;
    }
  }

  // Test koneksi API
  static Future<bool> testConnection() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/test'),
        headers: headers,
      );
      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }

  // Get user profile
  static Future<Map<String, dynamic>?> getProfile() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // Get user's projects
  static Future<List<dynamic>?> getUserProjects() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/projects'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return data['data'] as List<dynamic>?;
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // Get user's tasks
  static Future<List<dynamic>?> getUserTasks() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/tasks'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return data['data'] as List<dynamic>?;
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // Get user's cards (tasks)
  static Future<List<TaskCard>> getUserCards() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/cards'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final List<dynamic> cardsJson = data['data'] ?? [];
        return cardsJson.map((json) => TaskCard.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  // Get task details
  static Future<Map<String, dynamic>?> getTaskDetails(int taskId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/tasks/$taskId'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // Update task status
  static Future<bool> updateTaskStatus(int taskId, String status) async {
    try {
      final response = await http.patch(
        Uri.parse('$baseUrl/cards/$taskId/status'),
        headers: await authHeaders,
        body: jsonEncode({
          'status': status,
        }),
      );

      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }

  // Start task
  static Future<bool> startTask(int taskId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/tasks/$taskId/start'),
        headers: await authHeaders,
      );

      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }

  // Submit task for review
  static Future<bool> submitTask(int taskId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/tasks/$taskId/submit'),
        headers: await authHeaders,
      );

      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }

  // Get subtasks for a task
  static Future<List<dynamic>?> getSubtasks(int taskId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/tasks/$taskId/subtasks'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return data['data'] as List<dynamic>?;
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // Create subtask
  static Future<bool> createSubtask({
    required int taskId,
    required String title,
    String? description,
    double? estimatedHours,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/tasks/$taskId/subtasks'),
        headers: await authHeaders,
        body: jsonEncode({
          'title': title,
          'description': description,
          'estimated_hours': estimatedHours,
        }),
      );

      return response.statusCode == 200 || response.statusCode == 201;
    } catch (e) {
      return false;
    }
  }

  // Toggle subtask status
  static Future<bool> toggleSubtaskStatus(int taskId, int subtaskId) async {
    try {
      final response = await http.patch(
        Uri.parse('$baseUrl/tasks/$taskId/subtasks/$subtaskId/toggle'),
        headers: await authHeaders,
      );

      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }

  // Get comments for task
  static Future<List<dynamic>?> getTaskComments(int taskId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/tasks/$taskId/comments'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return data['data'] as List<dynamic>?;
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // Add comment to task
  static Future<bool> addTaskComment(int taskId, String content) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/tasks/$taskId/comments'),
        headers: await authHeaders,
        body: jsonEncode({
          'content': content,
        }),
      );

      return response.statusCode == 200 || response.statusCode == 201;
    } catch (e) {
      return false;
    }
  }

  // Get dashboard data
  static Future<Map<String, dynamic>?> getDashboardData() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/dashboard'),
        headers: await authHeaders,
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      return null;
    } catch (e) {
      return null;
    }
  }


}
