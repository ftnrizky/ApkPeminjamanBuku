# 📝 Integration Guide - Activity Log

Panduan lengkap untuk mengintegrasikan Activity Logging ke dalam existing controllers.

## 📚 Menggunakan ActivityLogService

Semua activity logging di centralisasi melalui `App\Services\ActivityLogService`.

### Import
```php
use App\Services\ActivityLogService;
```

---

## 🔐 1. Login Logging

### File: `app/Http/Controllers/Auth/LoginController.php`

**Lokasi Update:** Di dalam method `login()` atau `authenticated()` setelah login berhasil

```php
public function authenticated(Request $request, $user)
{
    // ... existing code ...
    
    // Log login activity
    ActivityLogService::logLogin($user->name, $user->role);
    
    return redirect()->intended($this->redirectPath());
}
```

---

## 🚪 2. Logout Logging

### File: `routes/web.php` atau Controller

**Lokasi Update:** Di dalam logout route/middleware

```php
Route::post('/logout', function () {
    $userName = Auth::user()?->name ?? 'Unknown';
    
    ActivityLogService::logLogout($userName);
    
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
```

---

## 📦 3. Peminjaman (Create/Store)

### File: `app/Http/Controllers/PeminjamController.php`

**Method:** `storePeminjaman()`

```php
public function storePeminjaman(Request $request)
{
    // ... validation & creation logic ...
    
    $peminjaman = Peminjaman::create([
        // ... data ...
    ]);
    
    // Log peminjaman activity
    ActivityLogService::logPeminjaman(
        $peminjaman->id,
        $peminjaman->alat->nama_alat,
        Auth::user()->name
    );
    
    return redirect()->back()->with('success', 'Peminjaman berhasil dibuat');
}
```

---

## ✅ 4. Persetujuan Peminjaman (Admin/Petugas)

### File: `app/Http/Controllers/Admin/PeminjamanController.php`

**Method:** `verifikasiPeminjaman()`

```php
public function verifikasiPeminjaman(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    
    if ($request->status === 'approved') {
        // Log approval
        ActivityLogService::logApprovalPeminjaman(
            $peminjaman->id,
            $peminjaman->user->name,
            $peminjaman->alat->nama_alat
        );
    } else {
        // Log rejection
        ActivityLogService::logRejectionPeminjaman(
            $peminjaman->id,
            $peminjaman->user->name,
            $peminjaman->alat->nama_alat
        );
    }
    
    // ... existing update logic ...
}
```

### File: `app/Http/Controllers/PetugasController.php`

**Method:** `prosesPersetujuanPinjam()`

```php
public function prosesPersetujuanPinjam(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    $status = $request->status;
    
    if ($status === 'approved') {
        ActivityLogService::logApprovalPeminjaman(
            $peminjaman->id,
            $peminjaman->user->name,
            $peminjaman->alat->nama_alat
        );
    } elseif ($status === 'rejected') {
        ActivityLogService::logRejectionPeminjaman(
            $peminjaman->id,
            $peminjaman->user->name,
            $peminjaman->alat->nama_alat
        );
    }
    
    // ... existing update logic ...
}
```

---

## 🔄 5. Pengembalian (User Submit Return)

### File: `app/Http/Controllers/PeminjamController.php`

**Method:** `prosesKembali()`

```php
public function prosesKembali(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    
    // Update status to "dikembalikan"
    $peminjaman->update([
        'status' => 'dikembalikan',
        'tgl_pengembalian_real' => now(),
    ]);
    
    // Log pengembalian
    ActivityLogService::logPengembalian(
        $peminjaman->id,
        $peminjaman->user->name,
        $peminjaman->alat->nama_alat
    );
    
    return redirect()->back()->with('success', 'Laptop berhasil dikembalikan');
}
```

---

## 🎯 6. Verifikasi Pengembalian (Staff Approval)

### File: `app/Http/Controllers/PetugasController.php`

**Method:** `prosesKonfirmasiKembali()`

```php
public function prosesKonfirmasiKembali(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    $kondisi = $request->kondisi_unit;
    $denda = $request->total_denda ?? 0;
    
    // Update peminjaman with condition & penalty
    $peminjaman->update([
        'status' => 'selesai',
        'kondisi_unit' => $kondisi,
        'denda' => $denda,
        'verified_by' => Auth::id(),
        'verified_at' => now(),
    ]);
    
    // Log approval pengembalian
    ActivityLogService::logApprovalPengembalian(
        $peminjaman->id,
        $peminjaman->user->name,
        $peminjaman->alat->nama_alat,
        $kondisi,
        $denda
    );
    
    return redirect()->back()->with('success', 'Pengembalian berhasil diverifikasi');
}
```

---

## 🔔 7. Send Reminder Notification

### File: `app/Http/Controllers/OverdueListController.php`

**Method:** `sendReminder()`

```php
public function sendReminder($id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    
    // Log reminder
    ActivityLogService::logReminder($peminjaman->id, $peminjaman->user->name);
    
    // TODO: Implement actual notification sending
    // - Email notification
    // - SMS notification
    // - Push notification
    // - In-app notification
    
    return response()->json([
        'success' => true,
        'message' => 'Pengingat berhasil dikirim'
    ]);
}
```

---

## 📊 Quick Reference

| Activity Type | Service Method | Triggered When |
|---|---|---|
| `login` | `logLogin()` | User login successfully |
| `logout` | `logLogout()` | User logout |
| `pinjam` | `logPeminjaman()` | Borrower creates loan request |
| `setujui_pinjam` | `logApprovalPeminjaman()` | Admin/Staff approves loan |
| `tolak_pinjam` | `logRejectionPeminjaman()` | Admin/Staff rejects loan |
| `kembali` | `logPengembalian()` | Borrower returns laptop |
| `setujui_kembali` | `logApprovalPengembalian()` | Staff verifies return |
| `reminder` | `logReminder()` | Reminder sent |

---

## 🛠️ Testing Activity Logs

### Via Tinker
```bash
php artisan tinker
```

```php
// Get recent activities
App\Models\ActivityLog::latest()->limit(10)->get();

// Get login activities
App\Models\ActivityLog::where('activity_type', 'login')->latest()->get();

// Get activities by user
App\Models\ActivityLog::where('user_id', 1)->latest()->get();

// Get activities of last 24 hours
App\Models\ActivityLog::where('created_at', '>=', now()->subDay())->latest()->get();

// Get activities grouped by type
App\Models\ActivityLog::selectRaw('activity_type, count(*) as total')->groupBy('activity_type')->get();
```

### Via Admin Dashboard
1. Go to `admin/activity-log`
2. Use filters to find specific activities
3. Export to PDF if needed

---

## ⚡ Best Practices

1. **Always log important actions:**
   - Authentication events
   - Data modifications (create, update, delete)
   - Approval/rejection flows
   - Administrative actions

2. **Include relevant context:**
   - User information
   - What was changed/created
   - Related data IDs
   - Timestamps

3. **Performance optimization:**
   - Log asynchronously for heavy operations
   - Archive old logs periodically
   - Use database indexes

4. **Security:**
   - Never log sensitive data (passwords, etc.)
   - Verify user permissions before logging
   - Store audit trail securely

---

## 🔧 Future Enhancements

- [ ] Real-time activity dashboard
- [ ] Activity alerts/notifications
- [ ] Advanced filtering & search
- [ ] Export to multiple formats (CSV, Excel, JSON)
- [ ] API endpoints for activity data
- [ ] Activity analytics & reporting
- [ ] Role-based access to logs
- [ ] Email notifications for critical activities

---

**Last Updated:** 20 April 2026
