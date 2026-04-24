# 🚀 Panduan Lengkap - Troubleshooting Kelola Alat Form

## 📋 Checklist Pre-Submission

Sebelum mensubmit form, pastikan:

- [ ] Nama buku terisi
- [ ] Kategori dipilih (Gaming/Business/Design)
- [ ] Kondisi dipilih (Baik/Lecet/Rusak)
- [ ] Stok ≥ 1
- [ ] Sewa/Hari ≥ 1000
- [ ] Harga Beli ≥ 10000
- [ ] Network tab menunjukkan POST request successfully (Status 200/302)

---

## 🔧 Command Perbaikan Quick Fix

Jalankan ini jika form masih bermasalah:

### 1. Clear Laravel Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 2. Set Storage Permissions
```bash
# Windows (run as Administrator)
icacls "storage" /grant:r "%username%":F /t
icacls "bootstrap\cache" /grant:r "%username%":F /t

# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 3. Create Storage Symlink
```bash
php artisan storage:link
```

### 4. Verify Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

---

## 🐛 Debugging Steps

### Step 1: Check Browser Console (F12)
1. Buka halaman admin.alat
2. Tekan F12 → Console tab
3. Lihat ada error atau tidak
4. Klik "Tambah buku" button
5. Submit form dengan data minimal
6. Lihat apakah ada error di console

### Step 2: Check Network Tab
1. Buka F12 → Network tab
2. Submit form
3. Cari POST request ke `/admin/alat/store`
4. Lihat Response tab untuk error message

### Step 3: Check Laravel Logs
```bash
# Windows
type storage\logs\laravel.log

# Linux/Mac
tail -f storage/logs/laravel.log

# Real-time monitoring
tail -f storage/logs/laravel.log | grep -i "error\|exception"
```

### Step 4: Tinker Console Check
```bash
php artisan tinker

# List semua data
>>> App\Models\Alat::all();

# Check latest
>>> App\Models\Alat::latest()->first();

# Create manual test
>>> App\Models\Alat::create([
  'nama_alat' => 'Test buku',
  'slug' => 'test-buku-abc123',
  'kategori' => 'Gaming',
  'stok_total' => 5,
  'stok_tersedia' => 5,
  'harga_sewa' => 100000,
  'harga_asli' => 10000000,
  'kondisi' => 'baik',
  'deskripsi' => 'Test'
]);
```

---

## 🛑 Common Issues & Solutions

### Issue 1: "The given data was invalid"
**Cause:** Validation error
**Solution:** 
- Pastikan semua field required terisi
- Periksa error message di view
- Cek console untuk validation error detail

### Issue 2: "SQLSTATE[HY000]"
**Cause:** Database connection error
**Solution:**
```bash
# Check .env file
cat .env | grep DATABASE

# Verify database exists
php artisan tinker
>>> DB::connection()->getPdo();
```

### Issue 3: "Call to undefined function"
**Cause:** Migration belum dijalankan
**Solution:**
```bash
php artisan migrate
php artisan migrate:status
```

### Issue 4: File Upload Failed
**Cause:** Storage permissions atau disk config
**Solution:**
```bash
# Check storage/app/public/alats folder exists
ls -la storage/app/public/

# Create if not exists
mkdir -p storage/app/public/alats

# Fix permissions
chmod -R 775 storage/app/public
```

### Issue 5: Data Saved tapi Tidak Muncul di Grid
**Cause:** Query filter atau display logic
**Solution:**
```bash
# Check database
php artisan tinker
>>> App\Models\Alat::all()->toArray();

# Reload page
# Clear browser cache Ctrl+Shift+R
```

---

## 📝 Test Form Submission

### Test 1: Minimal Valid Data
```
Form Fields:
- Nama buku: ASUS VivoBook 15
- Kategori: Gaming
- Kondisi: Baik
- Stok: 5
- Sewa/Hari: 100000
- Harga Beli: 10000000
- Deskripsi: (kosong boleh)
- Foto: (kosong boleh)

Expected Result:
✅ Page reload
✅ Success message ditampilkan
✅ Data muncul di grid teratas
✅ Database entry tersimpan
```

### Test 2: With File Upload
```
(Sama seperti Test 1, tapi tambah:)
- Foto: Upload file .jpg/.png max 2MB

Expected Result:
✅ File tersimpan di storage/app/public/alats
✅ URL foto terlihat di card
```

---

## 📊 Verification Checklist

Setelah submit form, verify ini:

### Browser
- [ ] Alert/Toast success message muncul
- [ ] Form modal tertutup
- [ ] Data muncul di grid/table
- [ ] Data muncul di kategori filter yang benar
- [ ] Search dapat menemukan data baru

### Database (via tinker)
```bash
php artisan tinker
>>> App\Models\Alat::latest()->get(['nama_alat', 'kategori', 'stok_total', 'harga_sewa']);
```
- [ ] nama_alat sesuai input
- [ ] kategori sesuai pilihan
- [ ] stok_total sesuai input
- [ ] harga_sewa sesuai input

### File System (jika upload foto)
```bash
ls -la storage/app/public/alats/
```
- [ ] File foto tersimpan
- [ ] File accessible via URL

---

## 🔍 Advanced Debugging

### Verbosity Tinggi - Check Request
```php
// Di AlatController.php storeAlat method, tambah:
\Log::info('Store Alat Request:', [
    'nama_alat' => $request->nama_alat,
    'kategori' => $request->kategori,
    'all_data' => $request->all()
]);
```

### Monitor Real-time
```bash
# Terminal 1: Monitor logs
tail -f storage/logs/laravel.log

# Terminal 2: Tinker watch
php artisan tinker
>>> while(true) { echo "Count: " . App\Models\Alat::count(); sleep(2); clear(); }
```

---

## ✅ Success Indicators

Form sudah bekerja dengan baik ketika:
1. ✅ Submit form berhasil
2. ✅ Success message muncul
3. ✅ Data langsung muncul di grid
4. ✅ Data tersimpan di database
5. ✅ Edit/Hapus function berfungsi
6. ✅ Filter kategori bekerja untuk data baru
7. ✅ Search menemukan data baru
8. ✅ Upload foto berhasil (jika ditest)

---

## 🎯 Next Actions

Jika semuanya bekerja:
1. ✅ Test CRUD untuk file lain yang mirip
2. ✅ Setup Tailwind CSS untuk production (lihat TAILWIND_SETUP.md)
3. ✅ Lakukan full end-to-end testing

Jika masih ada issue:
1. ⚠️ Jalankan step-by-step debugging di atas
2. ⚠️ Catat error message yang muncul
3. ⚠️ Check Laravel logs untuk stack trace
4. ⚠️ Hubungi developer dengan error message + screenshot

---

*Panduan ini dibuat untuk memastikan form submission berfungsi dengan sempurna dan semua data tersimpan dengan benar.*
