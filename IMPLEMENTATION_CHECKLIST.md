# ✅ IMPLEMENTATION CHECKLIST & DEPLOYMENT GUIDE

## 📋 Pre-Deployment Checklist

### Database

- [x] `carts` table has `custom_file` column
- [x] `order_items` table has `custom_file` column
- [x] Both columns are nullable string type

### Models

- [x] `Cart` model has `custom_file` in fillable
- [x] `OrderItem` model has `custom_file` in fillable

### Controllers

- [x] `CartController::store()` handles file upload
- [x] `CheckoutController::process()` transfers files to orders
- [x] `DesignFileController` created with security checks
    - [x] `download()` method with auth & ownership check
    - [x] `view()` method for image preview
    - [x] `upload()` method for AJAX

### Routes

- [x] Design file routes registered in `web.php`
- [x] Routes protected with `auth` middleware
- [x] Route names: `design.download`, `design.view`, `design.upload`

### Views

- [x] `payment.blade.php` updated with new design display
    - [x] Design file box with icons
    - [x] Preview button for images
    - [x] Download button
    - [x] Modal for preview
- [x] `profile.blade.php` order history updated
    - [x] Design file display in order items
    - [x] Preview and download buttons
    - [x] Modal popup
- [x] `product-detail.blade.php` has file upload UI
    - [x] Standard/Custom radio buttons
    - [x] File input with drag-drop
    - [x] Validation messages

### Configuration

- [x] Storage symlinks created
    - `public/storage` -> `storage/app/public`
    - `public/uploads` -> `storage/custom_uploads`
- [x] `filesystems.php` configured correctly
- [x] `.env` has `APP_URL` set correctly

### Security

- [x] MIME type validation in controllers
- [x] File size limit (5MB)
- [x] Path traversal protection
- [x] User ownership verification
- [x] Auth middleware on routes

### JavaScript

- [x] Modal functions added to `payment.blade.php`
    - [x] `viewDesignModal()`
    - [x] `closeDesignModal()`
- [x] Modal functions added to `profile.blade.php`
    - [x] `viewDesignModal()`
    - [x] `closeDesignModalProfile()`

---

## 🚀 DEPLOYMENT STEPS

### Step 1: Run Migrations (if needed)

```bash
cd designplus-web
php artisan migrate
```

### Step 2: Create Storage Symlink

```bash
php artisan storage:link
```

**Expected Output:**

```
INFO  The [public/storage] link has been connected to [storage/app/public].
INFO  The [public/uploads] link has been connected to [storage/custom_uploads].
```

### Step 3: Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Step 4: Set Proper Permissions (Linux/Mac)

```bash
chmod -R 755 storage/app/public
chmod -R 755 public/storage
chmod -R 755 public/uploads
```

### Step 5: Verify Installation

```bash
# Test file upload capability
php artisan tinker
> Storage::disk('public')->put('test.txt', 'Hello World')
> Storage::disk('public')->exists('test.txt')
> exit
```

---

## 🧪 TESTING GUIDE

### Test 1: File Upload (Cart)

```
1. Go to /product/[any-product-id]
2. Select Bahan, Warna
3. Choose "Custom" design option
4. Upload a file (JPG, PNG, PDF, GIF, WEBP)
5. Add to cart
✅ Expected: File stored in storage/custom_uploads
✅ Check: cart table has file path in custom_file column
```

### Test 2: Checkout with File

```
1. From cart, checkout
2. Go to payment page
✅ Expected: File displayed with icon, name, preview & download buttons
✅ Check: order_items table has file path in custom_file column
```

### Test 3: File Download

```
1. On payment page, click download icon
✅ Expected: File downloads with original name
✅ Check: Server route /order/{id}/item/{id}/download triggered
```

### Test 4: File Preview (Image)

```
1. On payment page, click eye icon (only for images)
2. Modal appears with full-size image
✅ Expected: Image displays in modal popup
✅ Check: Modal closes when clicking X or outside
```

### Test 5: Order History

```
1. Go to Profile > Riwayat Pesanan
2. Click on order with custom design
3. Expand order items
✅ Expected: Same design file display as payment page
✅ Check: Preview and download work same as payment
```

### Test 6: Security Tests

```
a. Try download without login
   ✅ Expected: Redirect to login

b. Try access other user's file (by URL manipulation)
   ✅ Expected: 403 Forbidden or Order not found

c. Upload file >5MB
   ✅ Expected: Validation error

d. Upload .exe or .php file
   ✅ Expected: File type error

e. Try access with invalid path (../../../etc/passwd)
   ✅ Expected: 403 Forbidden
```

---

## 📂 FILE CHANGES SUMMARY

### New Files

1. `app/Http/Controllers/DesignFileController.php` (285 lines)
2. `DESIGN_UPLOAD_FEATURE.md` (Documentation)
3. `QUICK_START_DESIGN_UPLOAD.md` (User guide)

### Modified Files

1. `app/Http/Controllers/CartController.php`
    - Enhanced `store()` with better validation
    - Added error handling

2. `app/Http/Controllers/CheckoutController.php`
    - Enhanced file upload handling
    - Added MIME type validation
    - Added better error messages

3. `routes/web.php`
    - Added design file routes
    - Imported DesignFileController

4. `resources/views/payment.blade.php`
    - Enhanced design display section
    - Added modal for preview
    - Added preview & download buttons

5. `resources/views/profile.blade.php`
    - Enhanced order history display
    - Added file design section per item
    - Added modal for preview
    - Added JavaScript functions

---

## 🔍 VERIFICATION QUERIES

### Check Database

```sql
-- Tables exist
SHOW TABLES LIKE 'carts';
SHOW TABLES LIKE 'order_items';

-- Columns exist
DESCRIBE carts;
DESCRIBE order_items;

-- Sample data
SELECT * FROM carts WHERE custom_file IS NOT NULL LIMIT 5;
SELECT * FROM order_items WHERE custom_file IS NOT NULL LIMIT 5;
```

### Check Files

```bash
# Check symlinks
ls -la public/storage
ls -la public/uploads

# Check storage directory
ls -la storage/app/public/custom_uploads/

# Check file permissions
stat storage/app/public/custom_uploads
```

### Check Routes

```bash
php artisan route:list | grep design

# Expected output:
# POST   /design/upload                    design.upload
# GET    /order/{orderId}/item/{itemId}/download  design.download
# GET    /order/{orderId}/item/{itemId}/view      design.view
```

---

## 🎯 POST-DEPLOYMENT TASKS

1. **Testing**
    - [ ] Full flow testing (upload → payment → history)
    - [ ] Security testing (ownership, auth, path traversal)
    - [ ] Mobile responsiveness
    - [ ] Different file types (JPG, PNG, PDF, GIF, WEBP)

2. **Monitoring**
    - [ ] Check error logs: `storage/logs/laravel.log`
    - [ ] Monitor disk space usage
    - [ ] Track failed uploads

3. **Documentation**
    - [ ] Inform users about feature in announcements
    - [ ] Update help/FAQ section
    - [ ] Create video tutorial (optional)

4. **Optimization** (Optional)
    - [ ] Add image compression
    - [ ] Implement file cleanup (old uploads)
    - [ ] Add virus scanning
    - [ ] Setup CDN if needed

---

## 🐛 COMMON ISSUES & SOLUTIONS

### Issue: "The file does not exist or is no longer available"

**Cause:** Symlink not created properly
**Solution:**

```bash
rm public/storage
php artisan storage:link
```

### Issue: File upload fails silently

**Cause:** Storage directory not writable
**Solution:**

```bash
chmod -R 777 storage/app/public
```

### Issue: Modal not showing

**Cause:** JavaScript error or CSS conflict
**Solution:**

1. Check browser console (F12)
2. Clear cache (Ctrl+Shift+Del)
3. Check if Tailwind CSS loaded properly

### Issue: File size says 0 bytes

**Cause:** File not uploaded properly
**Solution:**

1. Check upload form enctype="multipart/form-data"
2. Verify server max upload size in php.ini
3. Check storage disk space

---

## 📊 EXPECTED BEHAVIOR

### Pricing

- Standard: Regular price
- Custom: Regular price + Rp 5.000

### File Display

- **Payment Page**: Shows file immediately after checkout
- **Order History**: Shows file for all paid/unpaid orders
- **Download Access**: All authenticated users who own the order

### File Retention

- Files kept indefinitely (or until order is deleted)
- No automatic cleanup (manual cleanup needed)

---

## 📞 SUPPORT & CONTACT

For issues or questions:

1. Check error logs: `storage/logs/laravel.log`
2. Run tests from testing guide above
3. Review troubleshooting section
4. Contact development team with:
    - Error message/log
    - Steps to reproduce
    - Expected vs actual behavior

---

## ✨ FEATURE COMPLETE

All components are implemented and ready for:

- ✅ Production deployment
- ✅ User testing
- ✅ Integration testing
- ✅ Load testing (if needed)

**Status**: READY FOR DEPLOYMENT
**Version**: 1.0
**Last Updated**: May 9, 2026
