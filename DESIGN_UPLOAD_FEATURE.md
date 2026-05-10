# Panduan Fitur Upload & Tampilkan Desain Custom

## 📋 Ringkasan Fitur

Fitur ini memungkinkan pelanggan untuk:

1. **Upload desain custom** saat membeli produk (saat pilih opsi "Custom")
2. **Lihat desain** di halaman pembayaran sebelum melakukan pembayaran
3. **Download desain** dari halaman pembayaran dan riwayat pesanan
4. **Melihat preview desain** di modal untuk gambar (JPG, PNG, GIF, WEBP)

## 🔧 Komponen yang Sudah Diimplementasikan

### 1. Database

- ✅ Tabel `carts` - kolom `custom_file` untuk menyimpan path file saat di keranjang
- ✅ Tabel `order_items` - kolom `custom_file` untuk menyimpan path file dalam order

### 2. Models

- ✅ `Cart` model - memiliki field `custom_file`
- ✅ `OrderItem` model - memiliki field `custom_file`

### 3. Controllers

- ✅ **CartController** - Handle upload file saat add to cart
    - Validasi file (JPG, PNG, PDF, GIF, WEBP)
    - Max size: 5MB
    - Store di `storage/app/public/custom_uploads`

- ✅ **CheckoutController** - Handle upload file saat direct checkout
    - Validasi MIME type tambahan
    - Transfer file dari cart ke order items
    - Error handling

- ✅ **DesignFileController** (BARU) - Secure file download/view
    - `download()` - Download file dengan security check
    - `view()` - View gambar di browser
    - `upload()` - Upload file dengan AJAX

### 4. Routes (Web)

```php
// Design File Routes (dengan auth middleware)
Route::get('/order/{orderId}/item/{itemId}/download', [DesignFileController::class, 'download'])->name('design.download');
Route::get('/order/{orderId}/item/{itemId}/view', [DesignFileController::class, 'view'])->name('design.view');
Route::post('/design/upload', [DesignFileController::class, 'upload'])->name('design.upload');
```

### 5. Views

- ✅ **product-detail.blade.php**
    - File upload UI dengan drag & drop
    - Opsi standard vs custom
    - Validasi client-side

- ✅ **payment.blade.php** (DITINGKATKAN)
    - Menampilkan file desain custom dengan icon
    - Tombol preview (untuk gambar)
    - Tombol download
    - Modal untuk view desain

- ✅ **profile.blade.php** (DITINGKATKAN)
    - Halaman riwayat pesanan menampilkan file desain
    - Same UI dengan payment page
    - Modal untuk preview desain

## 📁 File Structure

```
storage/
  └── app/
      └── public/
          └── custom_uploads/     # Folder untuk file upload
              ├── xxxx-filename.pdf
              ├── yyyy-filename.jpg
              └── ...

public/
  ├── storage/                    # Symlink ke storage/app/public
  │   └── custom_uploads/
  │       └── ...
  └── ...
```

## ⚙️ Setup yang Diperlukan

### 1. Create Storage Symlink

Jalankan perintah artisan untuk membuat symlink:

```bash
php artisan storage:link
```

Perintah ini akan membuat symlink dari `public/storage` ke `storage/app/public`.
Output yang diharapkan:

```
The [public/storage] directory has been linked.
```

### 2. Verifikasi Permissions

Pastikan folder `storage/app/public` memiliki permission yang tepat:

```bash
# Linux/Mac
chmod -R 755 storage/app/public
chmod -R 755 storage/app/public/custom_uploads

# Windows (via PowerShell dengan admin)
icacls "storage\app\public" /grant:r "%USERNAME%":F /t
```

### 3. Environment Configuration

Pastikan di `.env`:

```env
APP_URL=http://localhost:8000  # atau domain Anda
FILESYSTEM_DISK=public
```

## 🔐 Security Features

1. **Authentication Check**
    - Hanya user yang authenticated bisa download file
    - Setiap download divalidasi ownership (user hanya bisa download file mereka sendiri)

2. **Path Traversal Protection**
    - Validasi file path (tidak boleh `..` atau `\`)
    - File harus exist di database order_items

3. **MIME Type Validation**
    - Validasi saat upload: JPG, PNG, PDF, GIF, WEBP
    - Double-check MIME type di controller

4. **File Size Limit**
    - Maximum 5MB per file
    - Validated di both form-level dan server-level

## 🧪 Testing Workflow

### 1. Upload Desain (Cart)

```
1. Buka halaman product detail
2. Pilih Bahan, Warna, dan Kuantitas
3. Di bagian "Desain", pilih "Custom"
4. Upload file (JPG, PNG, PDF, GIF, WEBP)
5. Tambah ke keranjang
6. Verifikasi file tersimpan di cart
```

### 2. View di Payment

```
1. Dari cart, lanjut checkout
2. Di halaman payment, lihat rincian produk
3. File custom harus tampil dengan icon dan nama
4. Klik eye icon untuk preview (jika gambar)
5. Klik download icon untuk download file
```

### 3. View di Order History

```
1. Buka Profile > Riwayat Pesanan
2. Klik salah satu order
3. Lihat detail item dengan file custom
4. File harus tampil sama seperti di payment page
5. Bisa preview dan download
```

## 📝 Validation Rules

### File Upload Validation

```php
'custom_file' => [
    'nullable',
    'file',
    'mimes:jpg,jpeg,png,pdf,gif,webp',
    'max:5120' // 5MB
]
```

### MIME Type Allowed

- `image/jpeg` (.jpg, .jpeg)
- `image/png` (.png)
- `image/gif` (.gif)
- `image/webp` (.webp)
- `application/pdf` (.pdf)

## 🐛 Troubleshooting

### Storage Symlink tidak Bekerja

**Error:** `The file does not exist or is no longer available`

**Solusi:**

```bash
# Cek symlink
ls -la public/storage

# Jika tidak ada, buat symlink
php artisan storage:link

# Jika masih gagal, hapus dan buat ulang
rm public/storage
php artisan storage:link
```

### File Upload Gagal

**Error:** `Gagal menambahkan produk ke keranjang`

**Kemungkinan Penyebab:**

1. Folder `storage/app/public` tidak writable
2. Ukuran file > 5MB
3. Format file tidak sesuai
4. Disk space penuh

**Solusi:**

```bash
# Ubah permissions
chmod -R 777 storage/app/public

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### File Tidak Bisa Di-Download

**Error:** `File tidak ditemukan atau akses ditolak`

**Kemungkinan Penyebab:**

1. User tidak login
2. File bukan milik user (order ID tidak match)
3. File path di database berbeda dari file path asli
4. Symlink tidak bekerja

**Solusi:**

1. Pastikan user login
2. Verifikasi order_id di URL
3. Check database: `SELECT custom_file FROM order_items;`
4. Recreate symlink: `php artisan storage:link`

## 📊 Database Queries untuk Testing

```sql
-- Lihat semua file custom yang terupload
SELECT id, order_id, product_name, custom_file, created_at
FROM order_items
WHERE custom_file != 'Standard' AND custom_file != 'Custom Request'
ORDER BY created_at DESC;

-- Lihat file di cart
SELECT id, user_id, product_id, custom_file, material, warna
FROM carts
WHERE custom_file != 'Standard'
ORDER BY created_at DESC;

-- Hapus file orphan (jika ada masalah)
UPDATE order_items
SET custom_file = NULL
WHERE custom_file = 'Standard' OR custom_file = 'null';
```

## 🎨 UI/UX Features

### Design Preview Modal

- Modal dinamis yang dibuat saat pertama kali digunakan
- Support gambar: JPG, PNG, GIF, WEBP
- Bisa close dengan tombol X atau klik outside modal
- Max height untuk prevent layout shift

### Design Display

- Icon berbeda untuk file type (image, PDF, dll)
- Gradient background untuk highlight
- Hover effect untuk UX improvement
- Responsive design (mobile-friendly)

### Validasi Client-Side

- Accept attribute di file input
- Max size check (tidak akurat tapi helpful)
- File type preview sebelum upload

## 🔄 Future Improvements

1. **Image Compression**
    - Compress gambar sebelum store untuk save disk space
2. **Watermark**
    - Tambahkan watermark saat preview

3. **Multiple Designs**
    - Allow upload multiple design variations

4. **Design Templates**
    - Pre-made design templates untuk choose

5. **Design Annotations**
    - Biarkan admin add notes/corrections pada design

## 📞 Support

Jika ada masalah atau pertanyaan tentang fitur ini:

1. Check logs: `storage/logs/laravel.log`
2. Run tests untuk verifikasi
3. Hubungi developer

---

**Last Updated:** May 9, 2026
**Version:** 1.0
