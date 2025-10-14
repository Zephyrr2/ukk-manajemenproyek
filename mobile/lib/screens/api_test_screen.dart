import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../utils/constants.dart';

class ApiTestScreen extends StatefulWidget {
  const ApiTestScreen({super.key});

  @override
  State<ApiTestScreen> createState() => _ApiTestScreenState();
}

class _ApiTestScreenState extends State<ApiTestScreen> {
  String _testResult = '';
  bool _isLoading = false;

  Future<void> _testApiConnection() async {
    setState(() {
      _isLoading = true;
      _testResult = 'Testing API connection...';
    });

    try {
      // Test basic API connection
      final isConnected = await ApiService.testConnection();
      if (isConnected) {
        setState(() {
          _testResult = 'API Connection: Success\nServer is reachable';
        });
      } else {
        setState(() {
          _testResult = 'API Connection: Failed - Server not reachable';
        });
      }
    } catch (e) {
      setState(() {
        _testResult = 'API Connection: Error - $e';
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('API Test'),
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            ElevatedButton(
              onPressed: _isLoading ? null : _testApiConnection,
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                padding: const EdgeInsets.symmetric(vertical: 16),
              ),
              child: _isLoading
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text(
                      'Test API Connection',
                      style: TextStyle(color: Colors.white),
                    ),
            ),
            const SizedBox(height: 20),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                border: Border.all(color: Colors.grey),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                _testResult.isEmpty ? 'No test results yet' : _testResult,
                style: const TextStyle(fontFamily: 'monospace'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}