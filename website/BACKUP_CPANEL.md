# Backup Otomatis untuk cPanel

## 📋 Daftar Isi
1. [Cara Backup Manual di cPanel (Tanpa Terminal)](#cara-backup-manual-di-cpanel-tanpa-terminal)
2. [Fitur Backup](#fitur-backup)
3. [Setup & Instalasi](#setup--instalasi)
4. [Cara Menggunakan](#cara-menggunakan)
5. [Setup Cronjob di cPanel](#setup-cronjob-di-cpanel)
6. [Monitoring & Maintenance](#monitoring--maintenance)

---

## 🎯 Cara Backup Manual di cPanel (Tanpa Terminal)

### A. Backup Full Website

#### 1. **Melalui cPanel Backup Wizard**

**Langkah-langkah:**

1. **Login ke cPanel**
   - Buka `https://yourdomain.com/cpanel`
   - Masukkan username & password

2. **Buka Backup Wizard**
   - Cari di search box: "Backup"
   - Klik **"Backup Wizard"** atau **"Backup"**

3. **Pilih Backup**
   - Klik tombol **"Backup"**

4. **Pilih Jenis Backup:**

   **a) Full Backup (Seluruh Akun)**
   - Klik **"Generate a Full Backup"**
   - Pilih **"Home Directory"**
   - Email notification: Masukkan email (opsional)
   - Klik **"Generate Backup"**
   - Tunggu proses selesai (bisa 5-30 menit tergantung ukuran)
   - Download file `.tar.gz` yang dihasilkan

   **b) Partial Backup (Per Bagian)**
   
   **Backup Home Directory:**
   - Scroll ke **"Partial Backups"**
   - Bagian **"Download a Home Directory Backup"**
   - Klik **"Download"**
   - File `.tar.gz` akan terdownload

   **Backup Database:**
   - Bagian **"Download a MySQL Database Backup"**
   - Pilih database yang ingin dibackup dari dropdown
   - Klik nama database atau tombol download
   - File `.sql.gz` akan terdownload

   **Backup Email Forwarders:**
   - Bagian **"Download an Email Forwarders Backup"**
   - Klik domain email Anda
   - File backup akan terdownload

   **Backup Email Filters:**
   - Bagian **"Download an Email Filter Backup"**
   - Klik domain email Anda
   - File backup akan terdownload

### B. Backup Database via phpMyAdmin

**Langkah-langkah:**

1. **Buka phpMyAdmin**
   - Login ke cPanel
   - Cari & klik **"phpMyAdmin"**

2. **Pilih Database**
   - Di sidebar kiri, klik nama database Anda
   - Contoh: `username_laravel`

3. **Export Database**
   - Klik tab **"Export"** di menu atas

4. **Pilih Metode Export:**

   **Quick Export (Cepat):**
   - Pilih **"Quick"**
   - Format: **SQL**
   - Klik **"Go"**
   - File `.sql` akan terdownload

   **Custom Export (Detail):**
   - Pilih **"Custom"**
   - **Tables**: Pilih semua atau table tertentu
   - **Output**: Pilih "Save output to a file"
   - **Format**: SQL
   - **Compression**: 
     - None (tidak terkompresi)
     - zipped (.zip)
     - gzipped (.gz) ← **Recommended**
   - **Format-specific options**:
     - ✅ Add DROP TABLE / VIEW / PROCEDURE / FUNCTION
     - ✅ Add IF NOT EXISTS
     - ✅ Add CREATE DATABASE / USE statement
   - Klik **"Go"**
   - File akan terdownload

### C. Backup Files via File Manager

**Langkah-langkah:**

1. **Buka File Manager**
   - Login ke cPanel
   - Klik **"File Manager"**

2. **Navigasi ke Folder Project**
   - Buka folder `public_html` atau folder project Anda

3. **Kompres Folder/Files:**

   **Metode 1: Compress Seluruh Folder**
   - Klik kanan pada folder project (misal: `public_html`)
   - Pilih **"Compress"**
   - Pilih Compression Type:
     - **Zip Archive** (.zip) ← Paling umum
     - **Gzip Archive** (.tar.gz) ← Lebih kecil
     - **Bzip2 Archive** (.tar.bz2) ← Paling kecil
   - Klik **"Compress File(s)"**
   - File compressed akan muncul di folder yang sama

   **Metode 2: Compress Files Tertentu**
   - Select files/folders yang ingin dibackup (Ctrl+Click)
   - Klik kanan → **"Compress"**
   - Beri nama file (misal: `backup-2025-11-20.tar.gz`)
   - Pilih compression type
   - Klik **"Compress File(s)"**

4. **Download Backup**
   - Klik kanan pada file compressed
   - Pilih **"Download"**
   - Atau select file → Klik **"Download"** di toolbar atas

### D. Backup Files Penting Saja (Laravel)

**Files yang WAJIB dibackup:**

1. **Folder `/storage/app`**
   - User uploads, files aplikasi
   - Compress & download

2. **Folder `/public/uploads`** (jika ada)
   - User uploaded images, documents
   - Compress & download

3. **File `.env`**
   - Database credentials & config
   - Download langsung (jangan di-compress)
   - ⚠️ **PENTING**: File ini rahasia!

4. **Database** (via phpMyAdmin)
   - Export database seperti di atas

**Cara Compress Multiple Folders:**
1. Select semua folder yang diperlukan (Ctrl+Click):
   - `storage/app`
   - `public/uploads`
   - `.env`
2. Klik kanan → **"Compress"**
3. Nama: `backup-essential-2025-11-20.tar.gz`
4. Download file hasil compress

### E. Jadwal Backup Manual

**Rekomendasi Jadwal:**

| Jenis Backup | Frekuensi | Cara |
|--------------|-----------|------|
| Database | Setiap hari | phpMyAdmin Export |
| Files penting | Setiap minggu | File Manager Compress |
| Full backup | Setiap bulan | cPanel Backup Wizard |

### F. Tips Backup Manual

**✅ DO:**
- Beri nama file dengan tanggal: `backup-2025-11-20.tar.gz`
- Gunakan compression (gzip) untuk menghemat space
- Download backup ke komputer lokal
- Simpan backup di beberapa lokasi (Google Drive, Dropbox, Hard disk)
- Test restore backup secara berkala

**❌ DON'T:**
- Jangan simpan backup di folder `public_html` (bisa diakses publik)
- Jangan backup folder `vendor` (bisa diinstall ulang via composer)
- Jangan backup folder `node_modules` (bisa diinstall ulang via npm)
- Jangan share file `.env` ke orang lain

### G. Restore Backup Manual

#### Restore Database:
1. Buka phpMyAdmin
2. Pilih database
3. Klik tab **"Import"**
4. Klik **"Choose File"**
5. Select file backup `.sql` atau `.sql.gz`
6. Klik **"Go"**

#### Restore Files:
1. Upload file backup `.tar.gz` via File Manager
2. Klik kanan → **"Extract"**
3. Pilih lokasi extract
4. Klik **"Extract File(s)"**

---

## 🎯 Fitur Backup

### 1. Laravel Artisan Commands
- **`backup:database`** - Backup database MySQL
- **`backup:cleanup`** - Hapus backup lama

### 2. Bash Script untuk cPanel
- Backup database + files dalam satu script
- Kompresi otomatis (gzip)
- Retention policy (hapus backup lama)
- Logging lengkap
- Email notification (opsional)

---

## 🚀 Setup & Instalasi

### A. Setup Laravel Commands (Lokal/Development)

Commands sudah dibuat di:
```
app/Console/Commands/
├── BackupDatabase.php
└── BackupCleanup.php
```

**Tidak perlu registrasi manual** - Laravel auto-discover commands.

### B. Setup Bash Script di cPanel

#### 1. Upload Script ke Server

Upload file `backup-cpanel.sh` ke server melalui:
- **File Manager** cPanel, atau
- **FTP/SFTP** client

Lokasi yang disarankan:
```
/home/username/backup-cpanel.sh
```

#### 2. Edit Konfigurasi Script

Buka file `backup-cpanel.sh` dan sesuaikan:

```bash
# Project path (path ke root Laravel)
PROJECT_PATH="/home/username/public_html"

# Backup destination
BACKUP_PATH="/home/username/backups"

# Database credentials (copy dari file .env)
DB_NAME="your_database_name"
DB_USER="your_database_user"
DB_PASS="your_database_password"
DB_HOST="localhost"

# Berapa hari backup disimpan
RETENTION_DAYS=7

# Email notifikasi (opsional)
NOTIFY_EMAIL="your-email@domain.com"
```

#### 3. Set Permission Executable

Via **Terminal** di cPanel:
```bash
chmod +x /home/username/backup-cpanel.sh
```

#### 4. Test Manual

Jalankan manual untuk test:
```bash
bash /home/username/backup-cpanel.sh
```

Cek hasil backup di folder `/home/username/backups/`

---

## 📖 Cara Menggunakan

### Command Laravel (Development/Lokal)

#### 1. Backup Database
```bash
# Basic backup
php artisan backup:database

# Dengan kompresi gzip
php artisan backup:database --compress

# Custom path
php artisan backup:database --path=/custom/path
```

#### 2. Cleanup Backup Lama
```bash
# Hapus backup > 7 hari (default)
php artisan backup:cleanup

# Hapus backup > 30 hari
php artisan backup:cleanup --days=30

# Custom backup path
php artisan backup:cleanup --path=/custom/path
```

### Bash Script (cPanel Production)

#### Manual Execution
```bash
bash /home/username/backup-cpanel.sh
```

#### Struktur Backup yang Dibuat
```
/home/username/backups/
├── database/
│   ├── backup_dbname_2025-01-15_14-30-00.sql.gz
│   ├── backup_dbname_2025-01-16_14-30-00.sql.gz
│   └── ...
├── files/
│   ├── backup_files_2025-01-15_14-30-00.tar.gz
│   ├── backup_files_2025-01-16_14-30-00.tar.gz
│   └── ...
└── logs/
    ├── backup_2025-01-15.log
    ├── backup_2025-01-16.log
    └── ...
```

---

## ⏰ Setup Cronjob di cPanel

### Langkah-langkah:

#### 1. Buka Cron Jobs di cPanel
- Login ke **cPanel**
- Cari menu **"Cron Jobs"** (Advanced section)

#### 2. Add New Cron Job

Pilih pengaturan waktu:

##### Opsi A: Backup Harian (Setiap hari jam 2 pagi)
```
Minute: 0
Hour: 2
Day: *
Month: *
Weekday: *
```

Command:
```bash
/bin/bash /home/username/backup-cpanel.sh > /dev/null 2>&1
```

##### Opsi B: Backup Setiap 12 Jam
```
Minute: 0
Hour: */12
Day: *
Month: *
Weekday: *
```

Command:
```bash
/bin/bash /home/username/backup-cpanel.sh > /dev/null 2>&1
```

##### Opsi C: Backup Mingguan (Setiap Minggu jam 3 pagi)
```
Minute: 0
Hour: 3
Day: *
Month: *
Weekday: 0
```

Command:
```bash
/bin/bash /home/username/backup-cpanel.sh > /dev/null 2>&1
```

#### 3. Shortcut cPanel (Common Settings)

Atau gunakan dropdown **"Common Settings"**:
- **Once Per Day (0 0 * * *)** - Midnight
- **Once Per Week (0 0 * * 0)** - Sunday midnight
- **Twice Per Month (0 0 1,15 * *)** - 1st & 15th

Kemudian tambahkan command script.

#### 4. Email Notification

Jika ingin notifikasi email dari cron:
- Isi field **"Email"** di bagian atas halaman Cron Jobs
- Atau hapus `> /dev/null 2>&1` dari command

---

## 🔍 Monitoring & Maintenance

### 1. Cek Log File

Via File Manager atau Terminal:
```bash
tail -f /home/username/backups/logs/backup_$(date +%Y-%m-%d).log
```

### 2. Cek Ukuran Backup

```bash
du -sh /home/username/backups/*
```

### 3. List Semua Backup

```bash
# Database backups
ls -lh /home/username/backups/database/

# Files backups
ls -lh /home/username/backups/files/
```

### 4. Test Restore Database

```bash
# Extract backup
gunzip backup_dbname_2025-01-15_14-30-00.sql.gz

# Restore to database
mysql -u username -p database_name < backup_dbname_2025-01-15_14-30-00.sql
```

### 5. Download Backup (Backup Remote)

**Opsi A: Via File Manager cPanel**
- Browse ke folder `/backups/`
- Select file → Download

**Opsi B: Via FTP/SFTP**
- Connect dengan FileZilla/WinSCP
- Download folder backups

**Opsi C: Via rsync (Advanced)**
```bash
# Dari komputer lokal
rsync -avz username@server:/home/username/backups/ ./local-backups/
```

---

## 🛡️ Best Practices

### 1. Retention Policy
- **Production**: Simpan 7-14 hari
- **Critical data**: Simpan 30 hari
- Sesuaikan `RETENTION_DAYS` di script

### 2. Storage Management
- Monitor disk space cPanel regularly
- Backup ke external storage jika perlu (Google Drive, S3, dll)

### 3. Security
- Jangan commit file backup ke Git
- Lindungi folder backup dengan `.htaccess`:
  ```apache
  # /home/username/backups/.htaccess
  deny from all
  ```

### 4. Testing
- Test restore backup secara berkala
- Verifikasi integritas file backup

### 5. Multiple Locations
- Simpan backup di lokasi berbeda (3-2-1 rule):
  - 3 copies data
  - 2 different media
  - 1 offsite location

---

## 🔧 Troubleshooting

### Error: "mysqldump: command not found"

**Solusi**: Gunakan full path mysqldump
```bash
which mysqldump
# Output: /usr/bin/mysqldump

# Update script dengan full path
/usr/bin/mysqldump --user="$DB_USER" ...
```

### Error: "Permission denied"

**Solusi**: Set permission
```bash
chmod +x /home/username/backup-cpanel.sh
chmod 755 /home/username/backups
```

### Error: Backup files terlalu besar

**Solusi**: Exclude folder besar di script
```bash
tar -czf "$FILES_FILEPATH" \
    --exclude='storage/app/public/videos' \
    --exclude='storage/app/public/large-files' \
    ...
```

### Cronjob tidak jalan

**Solusi**: Cek email notifikasi dari cron atau cek log:
```bash
tail -50 /var/log/cron
```

---

## 📞 Support

Jika ada masalah:
1. Cek log file di `backups/logs/`
2. Verifikasi database credentials di `.env`
3. Pastikan disk space cukup
4. Test script secara manual dulu

---

## 📝 Changelog

- **v1.0** - Initial release
  - Database backup dengan kompresi
  - Files backup (tar.gz)
  - Automated cleanup
  - Logging system

---

**Catatan**: Pastikan selalu test backup dan restore secara berkala untuk memastikan data dapat dipulihkan dengan baik.
