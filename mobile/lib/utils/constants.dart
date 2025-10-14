import 'package:flutter/material.dart';

// Colors - Match dengan web (Tailwind CSS)
class AppColors {
  static const Color primary = Color(0xFF16A34A); // green-600
  static const Color primaryDark = Color(0xFF15803D); // green-700
  static const Color accent = Color(0xFF10B981); // green-500
  static const Color background = Color(0xFFF3F4F6); // gray-100
  static const Color surface = Colors.white;
  static const Color error = Color(0xFFEF4444); // red-500
  static const Color onPrimary = Colors.white;
  static const Color onSurface = Color(0xFF111827); // gray-900
  static const Color onBackground = Color(0xFF111827);
  static const Color onError = Colors.white;
  
  // Additional colors dari web
  static const Color gray50 = Color(0xFFF9FAFB);
  static const Color gray100 = Color(0xFFF3F4F6);
  static const Color gray200 = Color(0xFFE5E7EB);
  static const Color gray300 = Color(0xFFD1D5DB);
  static const Color gray400 = Color(0xFF9CA3AF);
  static const Color gray500 = Color(0xFF6B7280);
  static const Color gray600 = Color(0xFF4B5563);
  static const Color gray700 = Color(0xFF374151);
  static const Color gray800 = Color(0xFF1F2937);
  static const Color gray900 = Color(0xFF111827);
  
  static const Color green100 = Color(0xFFDCFCE7);
  static const Color green600 = Color(0xFF16A34A);
  static const Color green700 = Color(0xFF15803D);
  
  static const Color blue100 = Color(0xFFDBEAFE);
  static const Color blue600 = Color(0xFF2563EB);
  
  static const Color purple100 = Color(0xFFF3E8FF);
  static const Color purple600 = Color(0xFF9333EA);
  
  static const Color yellow100 = Color(0xFFFEF3C7);
  static const Color yellow500 = Color(0xFFEAB308);
  static const Color yellow600 = Color(0xFFCA8A04);
  
  static const Color orange100 = Color(0xFFFFEDD5);
  static const Color orange600 = Color(0xFFEA580C);
  
  static const Color pink500 = Color(0xFFEC4899);
  
  static const Color red100 = Color(0xFFFEE2E2);
  static const Color red500 = Color(0xFFEF4444);
  static const Color red600 = Color(0xFFDC2626);
}

// Spacing
class AppSpacing {
  static const double xs = 4.0;
  static const double sm = 8.0;
  static const double md = 16.0;
  static const double lg = 24.0;
  static const double xl = 32.0;
  static const double xxl = 48.0;
}

// Sizes
class AppSizes {
  static const double borderRadius = 8.0;
  static const double buttonHeight = 44.0;
  static const double iconSize = 24.0;
  static const double avatarSize = 40.0;
}

class AppStrings {
  static const String appName = 'Manajemen Proyek';
  static const String login = 'Login';
  static const String register = 'Register';
  static const String email = 'Email';
  static const String password = 'Password';
  static const String confirmPassword = 'Konfirmasi Password';
  static const String name = 'Nama Lengkap';
  static const String loginButton = 'Masuk';
  static const String registerButton = 'Daftar';
  static const String dontHaveAccount = 'Belum punya akun?';
  static const String alreadyHaveAccount = 'Sudah punya akun?';
  static const String forgotPassword = 'Lupa Password?';
  static const String loading = 'Memuat...';
  static const String loginSuccess = 'Login berhasil!';
  static const String registerSuccess = 'Registrasi berhasil!';
  static const String loginFailed = 'Login gagal!';
  static const String registerFailed = 'Registrasi gagal!';
  static const String invalidEmail = 'Email tidak valid';
  static const String passwordTooShort = 'Password minimal 8 karakter';
  static const String passwordNotMatch = 'Password tidak sama';
  static const String fieldRequired = 'Field ini wajib diisi';
}
