# Mobile App - Manajemen Proyek

Aplikasi mobile Flutter untuk sistem manajemen proyek yang terintegrasi dengan backend Laravel.

## Fitur

- ✅ **Authentication System**
  - Login dengan email dan password
  - Register untuk user baru
  - Auto-login dengan token
  - Logout dengan konfirmasi
  - Token management dengan SharedPreferences

- ✅ **UI/UX Features**
  - Splash screen dengan logo
  - Form validation
  - Loading indicators
  - Snackbar notifications
  - Material Design 3
  - Responsive design

- ✅ **API Integration**
  - HTTP requests ke Laravel backend
  - Error handling
  - Token-based authentication
  - User data persistence

## Struktur Project

```
lib/
├── main.dart                 # Entry point aplikasi
├── models/                   # Data models
│   ├── user.dart            # Model User
│   └── auth_response.dart   # Model response authentication
├── screens/                  # UI Screens
│   ├── splash_screen.dart   # Splash screen
│   ├── login_screen.dart    # Login screen
│   ├── register_screen.dart # Register screen
│   └── home_screen.dart     # Home screen
├── services/                 # Business logic
│   └── api_service.dart     # API service untuk backend
└── utils/                    # Utilities
    ├── constants.dart       # Constants (colors, strings, sizes)
    └── validators.dart      # Form validators
```

## Setup

### Prerequisites
- Flutter SDK (3.7.2+)
- Dart SDK
- Android Studio atau VS Code
- Laravel backend running di `http://127.0.0.1:8000`

### Installation

1. **Clone project dan masuk ke folder mobile:**
   ```bash
   cd mobile
   ```

2. **Install dependencies:**
   ```bash
   flutter pub get
   ```

3. **Pastikan Laravel backend sudah running:**
   ```bash
   cd ../website
   php artisan serve
   ```

4. **Run aplikasi:**
   ```bash
   flutter run
   ```

### Configuration

Jika backend Laravel Anda berjalan di URL yang berbeda, update `baseUrl` di file `lib/services/api_service.dart`:

```dart
static const String baseUrl = 'http://YOUR_BACKEND_URL/api';
```

## API Endpoints

Aplikasi ini menggunakan endpoint berikut dari Laravel backend:

- `POST /api/register` - Register user baru
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/test` - Test koneksi API

## Dependencies

```yaml
dependencies:
  flutter:
    sdk: flutter
  cupertino_icons: ^1.0.8
  http: ^1.1.0              # HTTP requests
  shared_preferences: ^2.2.2 # Local storage
```

## Authentication Flow

1. **Splash Screen**: Cek status login dari SharedPreferences
2. **Login/Register**: User authenticate dengan backend
3. **Token Storage**: Simpan token dan user data di local storage
4. **Auto Login**: Gunakan token yang tersimpan untuk login otomatis
5. **Logout**: Hapus token dan redirect ke login

## Validasi Form

- **Email**: Format email yang valid
- **Password**: Minimal 8 karakter
- **Nama**: Minimal 2 karakter
- **Confirm Password**: Harus sama dengan password

## Error Handling

- Network errors
- Validation errors
- API response errors
- Token expiration
- Connection timeout

## Screenshots

### Login Screen
- Form login dengan email dan password
- Toggle visibility password
- Link ke register screen
- Loading indicator saat login

### Register Screen
- Form register dengan nama, email, password, dan konfirmasi password
- Validasi form real-time
- Loading indicator saat register

### Home Screen
- Welcome card dengan informasi user
- Menu grid untuk navigasi
- Logout button dengan konfirmasi

### Splash Screen
- Logo aplikasi
- Loading indicator
- Auto redirect ke login/home

## Development

### Menambah Screen Baru

1. Buat file di `lib/screens/`
2. Import di file yang memerlukan
3. Update routing jika perlu

### Menambah API Service

1. Update `lib/services/api_service.dart`
2. Tambah method baru sesuai endpoint
3. Handle error dan response

### Styling

Gunakan constants yang sudah didefinisikan di `lib/utils/constants.dart`:
- `AppColors` untuk warna
- `AppStrings` untuk text
- `AppSpacing` untuk spacing
- `AppSizes` untuk ukuran

## Testing

Untuk test aplikasi:

1. Pastikan backend Laravel running
2. Test register dengan data baru
3. Test login dengan data yang sudah terdaftar
4. Test logout
5. Test auto-login setelah restart app

## Troubleshooting

### Koneksi API Gagal
- Pastikan backend Laravel running di `http://127.0.0.1:8000`
- Check firewall dan network settings
- Verify API endpoints di browser

### Build Error
- Run `flutter clean`
- Run `flutter pub get`
- Restart IDE

### Hot Reload Tidak Work
- Restart aplikasi
- Run `flutter clean`

## Future Features

- [ ] Dashboard dengan statistik
- [ ] Manajemen proyek
- [ ] Tim dan kolaborasi
- [ ] Push notifications
- [ ] Dark theme
- [ ] Offline support
