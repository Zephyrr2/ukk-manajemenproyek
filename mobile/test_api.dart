import 'dart:convert';
import 'package:http/http.dart' as http;

void main() async {
  await testApiConnection();
  await testRegister();
  await testLogin();
}

Future<void> testApiConnection() async {
  print('🔍 Testing API Connection...');
  try {
    final response = await http.get(
      Uri.parse('http://127.0.0.1:8000/api/test'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    );
    
    if (response.statusCode == 200) {
      print('✅ API Connection: OK');
      print('   Response: ${response.body}');
    } else {
      print('❌ API Connection Failed');
      print('   Status: ${response.statusCode}');
      print('   Response: ${response.body}');
    }
  } catch (e) {
    print('❌ API Connection Error: $e');
  }
  print('');
}

Future<void> testRegister() async {
  print('📝 Testing Register...');
  try {
    final response = await http.post(
      Uri.parse('http://127.0.0.1:8000/api/register'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'name': 'Test User',
        'email': 'testuser${DateTime.now().millisecondsSinceEpoch}@example.com',
        'password': '12345678',
        'password_confirmation': '12345678',
      }),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 201 || (response.statusCode == 200 && data['success'] == true)) {
      print('✅ Register: OK');
      print('   Message: ${data['message']}');
      if (data['data'] != null) {
        print('   User: ${data['data']['user']['name']}');
        print('   Token: ${data['data']['access_token'].substring(0, 20)}...');
      }
    } else {
      print('❌ Register Failed');
      print('   Status: ${response.statusCode}');
      print('   Response: ${response.body}');
    }
  } catch (e) {
    print('❌ Register Error: $e');
  }
  print('');
}

Future<void> testLogin() async {
  print('🔐 Testing Login...');
  try {
    // First, register a test user
    await http.post(
      Uri.parse('http://127.0.0.1:8000/api/register'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'name': 'Login Test User',
        'email': 'logintest@example.com',
        'password': '12345678',
        'password_confirmation': '12345678',
      }),
    );
    
    // Then try to login
    final response = await http.post(
      Uri.parse('http://127.0.0.1:8000/api/login'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'email': 'logintest@example.com',
        'password': '12345678',
      }),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200 && data['success'] == true) {
      print('✅ Login: OK');
      print('   Message: ${data['message']}');
      if (data['data'] != null) {
        print('   User: ${data['data']['user']['name']}');
        print('   Token: ${data['data']['access_token'].substring(0, 20)}...');
      }
    } else {
      print('❌ Login Failed');
      print('   Status: ${response.statusCode}');
      print('   Response: ${response.body}');
    }
  } catch (e) {
    print('❌ Login Error: $e');
  }
  print('');
}
