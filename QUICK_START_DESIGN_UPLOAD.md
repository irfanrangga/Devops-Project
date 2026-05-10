# 🎯 QUICK START GUIDE - Fitur Upload & Tampil Desain Custom

## ✅ Apa yang Sudah Diimplementasikan?

### 1. **Backend Implementation**

- ✅ DesignFileController dengan 3 method:
    - `download()` - Download file dengan security check
    - `view()` - Preview gambar di browser
    - `upload()` - Handle AJAX file upload
- ✅ Enhanced CartController dengan validasi file yang lebih baik
- ✅ Enhanced CheckoutController dengan MIME type validation
- ✅ Route protection dengan auth middleware

### 2. **Frontend Implementation**

- ✅ Payment page dengan:
    - Design file display dengan icon dan nama file
    - Preview button (untuk gambar)
    - Download button
    - Modal popup untuk preview design
- ✅ Order history (Profile > Riwayat Pesanan) dengan:
    - Same UI sebagai payment page
    - Preview dan download functionality
    - Better styling dan organization

### 3. **File Storage & Security**

- ✅ Storage symlink sudah dibuat (`php artisan storage:link`)
- ✅ MIME type validation (JPG, PNG, PDF, GIF, WEBP)
- ✅ File size limit (5MB)
- ✅ Path traversal protection
- ✅ User ownership verification

---

## 🚀 CARA MENGGUNAKAN

### Step 1: Upload Desain Saat Checkout

**Di Product Detail Page:**

1. Pilih **Bahan** (material)
2. Pilih **Warna**
3. Di bagian "**Desain**":
    - Pilih **"Custom"** (radio button)
    - Upload file desain Anda (JPG, PNG, PDF, GIF, WEBP)
    - File max 5MB
4. Isi **Kuantitas** dan **Catatan** (optional)
5. Klik **"Beli Langsung"** atau tambah ke **"Keranjang"**

### Step 2: Lihat Desain di Halaman Pembayaran

**Di Payment Page:**

1. Setelah checkout, otomatis diarahkan ke halaman pembayaran
2. Di bagian **"Rincian Produk"**, lihat item Anda
3. Untuk produk dengan custom design:
    - **Bahan & Warna** ditampilkan dengan icon
    - **File Desain Custom** ditampilkan di box biru dengan:
        - ✅ Icon file (gambar, PDF, dll)
        - ✅ Nama file
        - ✅ Tombol **"Eye"** untuk preview (jika gambar)
        - ✅ Tombol **"Download"** untuk download
4. Preview akan membuka modal dengan gambar full-size

### Step 3: Download Desain Dari Riwayat Pesanan

**Di Profile > Riwayat Pesanan:**

1. Buka menu **Profile** (top-right navbar)
2. Klik **"Riwayat Pesanan"**
3. Lihat list pesanan Anda
4. Klik salah satu pesanan untuk expand
5. Di setiap item dengan custom design:
    - Sama seperti payment page
    - Bisa preview dan download kapan saja

---

## 🔍 FILE STRUCTURE

```
designplus-web/
├── app/Http/Controllers/
│   ├── DesignFileController.php       [BARU]
│   ├── CartController.php             [UPDATED]
│   └── CheckoutController.php         [UPDATED]
├── routes/
│   └── web.php                        [UPDATED]
├── resources/views/
│   ├── payment.blade.php              [UPDATED - Enhanced UI]
│   └── profile.blade.php              [UPDATED - Order history]
├── storage/
│   └── app/public/
│       └── custom_uploads/            [File storage]
├── public/
│   ├── storage/                       [Symlink]
│   └── uploads/                       [Symlink]
└── DESIGN_UPLOAD_FEATURE.md           [Documentation]
```

---

## 🛡️ SECURITY FEATURES

1. **Authentication Required**
    - Hanya user yang login bisa upload dan download
2. **Ownership Verification**
    - User hanya bisa download dari order mereka sendiri
3. **File Type Validation**
    - MIME type check pada upload
    - Hanya format: JPG, PNG, PDF, GIF, WEBP
4. **File Size Limit**
    - Maximum 5MB per file
5. **Path Protection**
    - Validasi path untuk prevent directory traversal attack
    - File harus terdaftar di database

---

## 📋 TESTING CHECKLIST

- [ ] Upload file di product detail (beli langsung)
- [ ] Lihat file di payment page
- [ ] Preview gambar (klik eye icon)
- [ ] Download file (klik download icon)
- [ ] Lihat file di riwayat pesanan
- [ ] Preview dari riwayat pesanan
- [ ] Download dari riwayat pesanan
- [ ] Coba upload file yang terlalu besar (should reject)
- [ ] Coba upload format yang salah (should reject)
- [ ] Coba akses file orang lain (should deny)

---

## 🐛 TROUBLESHOOTING

### "File tidak ditemukan" saat download

**Solusi:**

```bash
cd designplus-web
php artisan storage:link
```

### File gagal terupload

**Kemungkinan:**

- File terlalu besar (>5MB)
- Format salah
- Storage disk penuh

**Solusi:** Check `storage/logs/laravel.log`

### Preview modal tidak muncul

**Kemungkinan:**

- Symlink tidak bekerja
- Browser cache
- JavaScript error

**Solusi:**

1. Clear browser cache (Ctrl+Shift+Del)
2. Recreate symlink
3. Check browser console (F12)

---

## 🎨 DESIGN CHANGES

### Payment Page Changes

- Design file section dipindahkan ke detail item
- Lebih prominent dengan background gradient
- Icons untuk visual clarity
- Modal untuk preview

### Profile Order History Changes

- Setiap item sekarang show detail dengan file design
- Hover effect untuk UX improvement
- Responsive untuk mobile
- Same styling dengan payment page

---

## 📊 WORKFLOWS

### Flow Diagram: Upload to View

```
1. Customer Upload Design
   ↓
2. File Stored di storage/custom_uploads
   ↓
3. File path saved di carts table
   ↓
4. Customer Checkout
   ↓
5. File path copied ke order_items table
   ↓
6. Customer bisa view/download di Payment page
   ↓
7. Customer bisa view/download di Order History
```

### Security Check Flow

```
Customer Request Download
   ↓
Check Auth (login?)
   ↓
Get Order & OrderItem
   ↓
Verify Owner (order_id == user_id?)
   ↓
Check File Path (valid?)
   ↓
Check File Exists (di disk?)
   ↓
Return File
```

---

## 📞 NEED HELP?

### Helpful Commands:

```bash
# Check storage link
ls -la public/storage

# Clear cache
php artisan cache:clear

# Check logs
tail -f storage/logs/laravel.log

# Recreate symlink
rm public/storage
php artisan storage:link
```

### Database Queries:

```sql
-- Check uploaded files
SELECT id, order_id, product_name, custom_file
FROM order_items
WHERE custom_file != 'Standard';

-- Check cart files
SELECT user_id, product_id, custom_file
FROM carts
WHERE custom_file != 'Standard';
```

---

## 📝 NOTES

1. **File tersimpan di** `storage/app/public/custom_uploads/`
2. **Accessible via** `public/storage/custom_uploads/`
3. **Atau via secure route** `/order/{orderId}/item/{itemId}/download`
4. **Max file size:** 5MB
5. **Allowed formats:** JPG, PNG, PDF, GIF, WEBP
6. **Added cost for custom:** Rp 5.000

---

**Status:** ✅ COMPLETE & READY TO TEST
**Last Updated:** May 9, 2026
