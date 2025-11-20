# Project Submission & Review Feature

## Overview
Fitur ini memungkinkan leader untuk submit project ke admin untuk direview, dan admin dapat approve atau reject project tersebut dengan notifikasi otomatis.

## Database Changes

### 1. Migration: `2025_09_01_051644_create_projects_table.php`
Ditambahkan kolom baru:
- `status` (enum): 'draft', 'submitted', 'approved', 'rejected' (default: 'draft')
- `submission_note` (text, nullable): Catatan dari leader saat submit
- `review_note` (text, nullable): Catatan dari admin saat review
- `submitted_at` (timestamp, nullable): Waktu project disubmit
- `reviewed_at` (timestamp, nullable): Waktu project direview
- `reviewed_by` (foreign key to users, nullable): Admin yang mereview

### 2. Migration: `2025_11_18_111521_create_notifications_table.php`
Ditambahkan kolom baru:
- `project_id` (foreign key to projects, nullable): Referensi ke project yang terkait notifikasi

## Model Updates

### 1. Project Model (`app/Models/Project.php`)
**Fillable ditambahkan:**
- status, submission_note, review_note, submitted_at, reviewed_at, reviewed_by

**Casts ditambahkan:**
- submitted_at, reviewed_at sebagai datetime

**Relasi baru:**
- `reviewer()`: belongsTo User (yang mereview project)
- `notifications()`: hasMany Notification

### 2. Notification Model (`app/Models/Notification.php`)
**Fillable ditambahkan:**
- project_id

**Relasi baru:**
- `project()`: belongsTo Project

**Icon attribute (ditambahkan):**
- 'project_submitted' => '📋'
- 'project_approved' => '✅'
- 'project_rejected' => '❌'

**Color attribute (ditambahkan):**
- 'project_submitted' => 'blue'
- 'project_approved' => 'green'
- 'project_rejected' => 'red'

## Controller Changes

### 1. Leader ProjectController (`app/Http/Controllers/Leader/ProjectController.php`)

#### Method Baru: `submitProject($projectId)`
**Route:** `POST /leader/projects/{id}/submit-project`

**Fungsi:**
- Leader dapat submit project yang berstatus 'draft'
- Update status project menjadi 'submitted'
- Simpan submission_note dan submitted_at
- Buat notifikasi untuk semua admin

**Validasi:**
- submission_note: nullable, string, max 1000 karakter
- Project harus milik leader yang login
- Project harus berstatus 'draft'

**Response:**
```json
{
  "success": true,
  "message": "Project berhasil disubmit untuk review oleh admin!",
  "project": {
    "id": 1,
    "name": "Project Name",
    "status": "submitted"
  }
}
```

### 2. Admin ProjectController (`app/Http/Controllers/Admin/ProjectController.php`)

#### Method Baru: `approveProject($slug)`
**Route:** `POST /admin/projects/{slug}/approve`

**Fungsi:**
- Admin dapat approve project yang berstatus 'submitted'
- Update status project menjadi 'approved'
- Simpan review_note, reviewed_at, dan reviewed_by
- Buat notifikasi untuk leader project

**Validasi:**
- review_note: nullable, string, max 1000 karakter
- Project harus berstatus 'submitted'

**Response:**
```json
{
  "success": true,
  "message": "Project berhasil di-approve!",
  "project": {
    "id": 1,
    "name": "Project Name",
    "status": "approved"
  }
}
```

#### Method Baru: `rejectProject($slug)`
**Route:** `POST /admin/projects/{slug}/reject`

**Fungsi:**
- Admin dapat reject project yang berstatus 'submitted'
- Update status project menjadi 'rejected'
- Simpan review_note, reviewed_at, dan reviewed_by
- Buat notifikasi untuk leader project

**Validasi:**
- review_note: required, string, max 1000 karakter (wajib diisi saat reject)
- Project harus berstatus 'submitted'

**Response:**
```json
{
  "success": true,
  "message": "Project telah di-reject!",
  "project": {
    "id": 1,
    "name": "Project Name",
    "status": "rejected"
  }
}
```

#### Method Baru: `resetProjectStatus($slug)`
**Route:** `POST /admin/projects/{slug}/reset-status`

**Fungsi:**
- Reset project yang rejected kembali ke status 'draft'
- Hapus semua data review (submission_note, review_note, dll)
- Memungkinkan leader untuk melakukan perbaikan dan submit ulang

**Validasi:**
- Project harus berstatus 'rejected'

**Response:**
```json
{
  "success": true,
  "message": "Status project berhasil direset ke draft!",
  "project": {
    "id": 1,
    "name": "Project Name",
    "status": "draft"
  }
}
```

## Routes Added

### Admin Routes (`/admin/*`)
```php
Route::post('/projects/{slug}/approve', [ProjectController::class, 'approveProject'])->name('admin.projects.approve');
Route::post('/projects/{slug}/reject', [ProjectController::class, 'rejectProject'])->name('admin.projects.reject');
Route::post('/projects/{slug}/reset-status', [ProjectController::class, 'resetProjectStatus'])->name('admin.projects.reset-status');
```

### Leader Routes (`/leader/*`)
```php
Route::post('/projects/{id}/submit-project', [LeaderProjectController::class, 'submitProject'])->name('leader.projects.submit');
```

## Notification Types

### 1. project_submitted
**Dikirim ke:** Semua admin
**Trigger:** Leader submit project
**Data:**
- project_id
- project_name
- submitted_by (nama leader)
- submission_note

### 2. project_approved
**Dikirim ke:** Leader yang membuat project
**Trigger:** Admin approve project
**Data:**
- project_id
- project_name
- reviewed_by (nama admin)
- review_note

### 3. project_rejected
**Dikirim ke:** Leader yang membuat project
**Trigger:** Admin reject project
**Data:**
- project_id
- project_name
- reviewed_by (nama admin)
- review_note

## Status Flow

```
draft -> submitted -> approved
                  -> rejected -> draft (reset)
```

### Status Details:
- **draft**: Status awal project, leader masih bisa edit
- **submitted**: Project sudah disubmit ke admin, menunggu review
- **approved**: Project disetujui admin
- **rejected**: Project ditolak admin, bisa direset ke draft untuk perbaikan

## Implementation Notes

### Frontend Implementation (To Do):
1. **Leader Dashboard/Project View:**
   - Tombol "Submit Project" (hanya muncul jika status = 'draft')
   - Modal untuk input submission_note
   - Badge status project (draft/submitted/approved/rejected)
   - View submission_note dan review_note

2. **Admin Project List:**
   - Filter berdasarkan status
   - Badge status pada setiap project card
   - Badge "Waiting Review" untuk submitted projects

3. **Admin Project Detail:**
   - Tombol "Approve" dan "Reject" (hanya muncul jika status = 'submitted')
   - Modal untuk input review_note
   - Display submission_note dari leader
   - Tombol "Reset to Draft" (hanya muncul jika status = 'rejected')

4. **Notifications:**
   - Tampilkan notifikasi project_submitted untuk admin
   - Tampilkan notifikasi project_approved/rejected untuk leader
   - Link ke detail project dari notifikasi

### Security Considerations:
- Leader hanya bisa submit project miliknya sendiri
- Admin bisa review semua submitted projects
- Status transition harus valid (draft->submitted, submitted->approved/rejected)
- Review note wajib diisi saat reject project

### Additional Features to Consider:
- Email notification saat project di-approve/reject
- History log untuk semua perubahan status project
- Deadline untuk review (SLA)
- Re-submit project setelah perbaikan
- Comments/discussion thread antara leader dan admin
