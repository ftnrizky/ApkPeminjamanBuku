# 🧪 TESTING GUIDE - E-LAPTOP MANAGEMENT SYSTEM

## Pre-Test Setup

```bash
# 1. Ensure migration is run
php artisan migrate --force

# 2. Start Laravel dev server
php artisan serve

# 3. Access application
# Admin:     http://localhost:8000/admin/dashboard
# Staff:     http://localhost:8000/petugas/dashboard
# Borrower:  http://localhost:8000/peminjam/dashboard
```

---

## 📋 TEST SCENARIOS

### TEST 1: Activity Log Feature - Admin Dashboard ✅

**Prerequisite:**
- Admin logged in
- At least one user activity in database

**Steps:**
1. Navigate to Admin Sidebar → "Monitoring" → "Activity Log"
2. **Verify:**
   - [ ] Page loads without errors
   - [ ] Table displays activity records
   - [ ] Columns visible: Time, User, Activity Type, Description, Role
   - [ ] Each row has different activity type badge
   - [ ] Color coding: login (cyan), pinjam (teal), etc.
   - [ ] Pagination works (if >20 records)

**Expected Result:** ✅ Activity log page displays correctly with all data

---

### TEST 2: Activity Log Filtering ✅

**Steps:**
1. On Activity Log page, fill search field: `login` or `pinjam`
2. Click "Cari" button
3. **Verify:**
   - [ ] Results filtered to matching activities
   - [ ] Row count updated
   - [ ] Filter applied correctly

**Steps:**
1. Select "Activity Type" dropdown (if exists)
2. Choose one type (e.g., "pinjam")
3. **Verify:**
   - [ ] Only selected activity type shows
   - [ ] Count reduced

**Steps:**
1. Set date range: "From" to "To"
2. Click "Cari"
3. **Verify:**
   - [ ] Only activities within date range shown
   - [ ] Correct results displayed

**Expected Result:** ✅ Filters work correctly

---

### TEST 3: Activity Log PDF Export ✅

**Steps:**
1. On Activity Log page, click "Export PDF" button
2. **Verify:**
   - [ ] PDF file downloads
   - [ ] File opens in browser/PDF viewer
   - [ ] Filename: `activity-log-YYYYMMDD-HHMMSS.pdf`
   - [ ] PDF contains:
     - Header with E-Laptop branding
     - Summary info
     - Activity table
     - Professional formatting

**Expected Result:** ✅ PDF exports successfully with all data

---

### TEST 4: Overdue List - Admin View ✅

**Prerequisite:**
- Admin logged in
- Data with overdue laptops exists (create test data if needed)

**Steps:**
1. Navigate to Admin Sidebar → "Monitoring" → "Laptop Belum Kembali"
2. **Verify Page Loads:**
   - [ ] No errors on page load
   - [ ] 3 stat cards visible: Total, Overdue, Critical
   - [ ] Stats show correct numbers
   - [ ] Stats cards have gradient backgrounds (cyan/amber/red)

**Verify Stats Cards:**
1. Count total unpaid laptops
2. **Verify:**
   - [ ] "Total Belum Dikembalikan" = count of all unpaid
   - [ ] "Terlambat" = count of overdue (<3 days)
   - [ ] "Kritis (>3 Hari)" = count of critical (>3 days)

**Verify Table:**
- [ ] Columns: Kode, Peminjam, Laptop, Qty, Tgl Pinjam, Batas Kembali, Status, Aksi
- [ ] Table populated with data
- [ ] At least one row visible
- [ ] Status badges color-coded properly

**Expected Result:** ✅ Admin overdue list displays correctly

---

### TEST 5: Overdue List - Staff View ✅

**Steps:**
1. Logout from admin
2. Login as staff (petugas)
3. Navigate to Sidebar → "Belum Dikembalikan"
4. **Verify:**
   - [ ] Page loads without errors
   - [ ] Same stats cards display
   - [ ] Same table with data
   - [ ] No kategori filter (should be simplified)

**Expected Result:** ✅ Staff view displays correctly

---

### TEST 6: Overdue List Search & Filter ✅

**Steps:**
1. In search field, enter borrower name or laptop model
2. Click "Cari"
3. **Verify:**
   - [ ] Results filtered to matching records
   - [ ] Other records hidden

**Steps:**
1. Select status from dropdown (if available)
2. Click filter
3. **Verify:**
   - [ ] Only selected status shows

**Steps (Admin Only):**
1. Select kategori from dropdown
2. Click filter
3. **Verify:**
   - [ ] Only selected kategori shows

**Expected Result:** ✅ Filters work correctly

---

### TEST 7: Quick "Terlambat" Button (Admin Only) ✅

**Steps:**
1. Click "Terlambat" button
2. **Verify:**
   - [ ] Page reloads
   - [ ] Only overdue records show
   - [ ] On-time records hidden
   - [ ] Row count reduced

**Expected Result:** ✅ Quick filter works

---

### TEST 8: Send Reminder Notification ✅

**Steps:**
1. In overdue list, click "Notif" button on any row
2. **Verify:**
   - [ ] Confirmation dialog appears
   - [ ] Dialog shows "Kirim notifikasi pengingat pengembalian?"

**Steps:**
1. Click "OK" in confirmation
2. **Verify:**
   - [ ] Request sent (check Network tab in DevTools)
   - [ ] Success message appears
   - [ ] Response: `{"success":true,"message":"Pengingat berhasil dikirim"}`

**If there's an error:**
1. Check browser console (F12)
2. **Verify:**
   - [ ] No JavaScript errors
   - [ ] CSRF token valid (in form)

**Expected Result:** ✅ Reminder notification sends successfully

---

### TEST 9: Pagination ✅

**Prerequisite:**
- >20 overdue records in database

**Steps:**
1. Go to Overdue List
2. Scroll to bottom of page
3. **Verify:**
   - [ ] Pagination links visible
   - [ ] Current page highlighted
   - [ ] Next/Prev buttons clickable
   - [ ] Last page link available

**Steps:**
1. Click on page 2
2. **Verify:**
   - [ ] Different records load
   - [ ] Row count still 20 (per page)
   - [ ] Page indicator updated

**Expected Result:** ✅ Pagination works correctly

---

### TEST 10: Custom Denda Form (Menyetujui Kembali) ✅

**Steps:**
1. Navigate to Staff → "Verifikasi Kembali"
2. Find pending return request
3. Click approve button
4. **Verify Modal Opens:**
   - [ ] Modal shows laptop details
   - [ ] Condition buttons visible (Baik, Lecet, Rusak, Hilang, Lainnya)

**Steps:**
1. Click "Lainnya" (Custom Damage) button
2. **Verify:**
   - [ ] Custom damage input field appears
   - [ ] Placeholder shows example value
   - [ ] Field is focused/clickable

**Steps:**
1. Enter custom amount: `150000`
2. **Verify:**
   - [ ] Value accepted
   - [ ] Summary updates with new amount
   - [ ] Total denda increases

**Steps:**
1. Click "Konfirmasi Pengembalian"
2. **Verify:**
   - [ ] Form submits (no error 500!)
   - [ ] Success message appears
   - [ ] Page reloads
   - [ ] Record marked as "selesai"

**Expected Result:** ✅ Custom denda form works without errors

---

### TEST 11: Database Activity Log Recording ✅

**Steps via Tinker:**
```bash
php artisan tinker
```

**Check Records:**
```php
# See all records
App\Models\ActivityLog::latest()->limit(10)->get();

# See specific type
App\Models\ActivityLog::where('activity_type', 'login')->latest()->get();

# See by user
App\Models\ActivityLog::where('user_name', 'Admin User')->latest()->get();

# See with data
App\Models\ActivityLog::where('activity_type', 'pinjam')->latest()->first();
```

**Verify:**
- [ ] Records created with correct data
- [ ] user_id populated
- [ ] activity_type set correctly
- [ ] ip_address captured
- [ ] user_agent captured
- [ ] created_at timestamp correct

**Expected Result:** ✅ Database records created properly

---

### TEST 12: Responsive Design ✅

**Mobile (375px):**
1. Resize browser to mobile width
2. **Verify:**
   - [ ] Activity Log table scrolls horizontally
   - [ ] Overdue list stats cards stack vertically
   - [ ] Buttons remain clickable
   - [ ] No horizontal overflow

**Tablet (768px):**
1. Resize to tablet width
2. **Verify:**
   - [ ] Stats cards show 2-3 per row
   - [ ] Table readable
   - [ ] Layout proper

**Desktop (1920px):**
1. Full screen width
2. **Verify:**
   - [ ] Stats cards show 3 per row
   - [ ] Table fully visible
   - [ ] No layout issues

**Expected Result:** ✅ Responsive design works at all breakpoints

---

### TEST 13: Navigation & Sidebar ✅

**Admin:**
1. Login as admin
2. **Verify Sidebar:**
   - [ ] "Monitoring" section visible
   - [ ] "Activity Log" menu item visible
   - [ ] "Laptop Belum Kembali" menu item visible

**Staff:**
1. Login as staff
2. **Verify Sidebar:**
   - [ ] "Belum Dikembalikan" menu item visible in "Transaksi & Laporan" section
   - [ ] Activity Log NOT visible

**Expected Result:** ✅ Navigation menu displays correctly per role

---

### TEST 14: Error Handling ✅

**Test Invalid Data:**
1. Try to open Activity Log with invalid filter
2. **Verify:**
   - [ ] Page loads normally
   - [ ] No 500 errors
   - [ ] Returns empty results gracefully

**Test Missing Records:**
1. Search for non-existent activity
2. **Verify:**
   - [ ] Empty state message shows: "Semua laptop sudah dikembalikan"
   - [ ] No error message
   - [ ] Page remains functional

**Test Network Error:**
1. In DevTools, throttle to "Offline"
2. Click "Notif" button
3. **Verify:**
   - [ ] Error caught and displayed
   - [ ] Network error message shows
   - [ ] Page remains stable

**Expected Result:** ✅ Error handling works properly

---

### TEST 15: Color Scheme & Branding ✅

**Verify Colors:**
- [ ] Cyan (#06b6d4) used for primary elements
- [ ] Teal (#0891b2) used for secondary elements
- [ ] Nav icons/buttons are cyan
- [ ] Status badges have correct colors:
  - [ ] On-time = cyan
  - [ ] Overdue = amber
  - [ ] Critical = red
- [ ] No emerald colors (old theme) visible

**Verify Typography:**
- [ ] Font is Inter (not Plus Jakarta Sans)
- [ ] Headers bold/dark
- [ ] Labels uppercase & tracked
- [ ] Body text readable

**Verify Animations:**
- [ ] Hover effects smooth (not janky)
- [ ] Transitions are 200-300ms
- [ ] Scale effects on buttons
- [ ] No excessive animations

**Expected Result:** ✅ Design system applied correctly

---

## 📊 TEST SUMMARY REPORT

### Before Running Tests:
- [ ] Run `php artisan migrate --force`
- [ ] Start `php artisan serve`
- [ ] Create test data if needed
- [ ] Login with admin & staff accounts

### Test Results Log:

| Test # | Name | Status | Notes |
|--------|------|--------|-------|
| 1 | Activity Log Feature | ⚪ | |
| 2 | Activity Log Filtering | ⚪ | |
| 3 | Activity Log PDF Export | ⚪ | |
| 4 | Overdue List Admin | ⚪ | |
| 5 | Overdue List Staff | ⚪ | |
| 6 | Search & Filter | ⚪ | |
| 7 | Quick Filter Button | ⚪ | |
| 8 | Send Reminder | ⚪ | |
| 9 | Pagination | ⚪ | |
| 10 | Custom Denda Form | ⚪ | |
| 11 | Database Logging | ⚪ | |
| 12 | Responsive Design | ⚪ | |
| 13 | Navigation & Sidebar | ⚪ | |
| 14 | Error Handling | ⚪ | |
| 15 | Color & Branding | ⚪ | |

**Legend:** ⚪ Not tested, 🟢 Passed, 🔴 Failed

---

## 🐛 If Tests Fail

### Activity Log page blank/error:
```bash
# Check migration
php artisan migrate:status

# Check table exists
php artisan tinker
> Schema::hasTable('activity_logs')

# Check for errors
php artisan logs:clear
php artisan serve
# Check storage/logs/laravel.log
```

### Custom denda still getting error 500:
```php
# Check if menyetujui_kembali.blade.php has the fix
# Look for: updateSummary() function with parseInt()
# Look for: addHiddenInputs() including custom_biaya array
```

### Sidebar menu items not showing:
```php
# Check if route names registered in web.php
php artisan route:list | grep activity
php artisan route:list | grep overdue

# Check if layout files updated
# Admin: resources/views/layouts/admin.blade.php
# Staff: resources/views/layouts/petugas.blade.php
```

### PDF export fails:
```bash
# Verify barryvdh/laravel-dompdf is installed
composer show | grep dompdf

# Check PDF view path
# Should be: resources/views/admin/activity_log_pdf.blade.php
```

---

## ✅ FINAL VERIFICATION

After all tests pass:
- [ ] All 15 tests passed
- [ ] No error messages
- [ ] All features working
- [ ] Responsive design verified
- [ ] Database populated correctly
- [ ] Ready for production deployment

---

**Test Run Date:** _______________
**Tester Name:** _______________
**Overall Status:** 🟢 PASS / 🔴 FAIL
**Notes:** _________________________________

---

**Generated:** 20 April 2026
**Version:** 1.0
