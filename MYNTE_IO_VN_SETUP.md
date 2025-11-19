# 📋 HƯỚNG DẪN SETUP CHO myntex.io.vn

## 🌐 THÔNG TIN DOMAIN
- **Domain:** myntex.io.vn
- **Hosting:** cPanel (Vietnam)
- **Email:** Sử dụng email hosting thay vì Gmail

## 1️⃣ TẠO DATABASE TRONG CPANEL

### Bước 1: Tạo Database
1. Đăng nhập cPanel của myntex.io.vn
2. Vào **MySQL Database Wizard**
3. Tạo database: `myntexio_portfolio`
4. Tạo user: `myntexio_admin`
5. Password: (đặt mật khẩu mạnh)
6. Gán **ALL PRIVILEGES**

### Bước 2: Import Database
1. Vào **phpMyAdmin**
2. Chọn database `myntexio_portfolio`
3. Click **Import**
4. Chọn file `database/portfolio.sql` từ máy tính
5. Click **Go**

## 2️⃣ CẤU HÌNH FILES

### File 1: admin/config.php (QUAN TRỌNG)
Upload file `admin/config-hosting.php` lên hosting và đổi tên thành `admin/config.php`

**Nội dung đã cấu hình sẵn:**
```php
'db' => array (
  'host' => 'localhost',
  'name' => 'myntexio_portfolio',
  'user' => 'myntexio_admin',
  'pass' => 'your-strong-password-here', // ĐỔI LẠI PASSWORD
  'charset' => 'utf8mb4',
),
```

### File 2: Email Configuration
Trong cùng file `admin/config.php`:
```php
'email' => array (
  'enabled' => true,
  'smtp_host' => 'mail.myntex.io.vn', // Email hosting
  'smtp_port' => 587,
  'smtp_username' => 'noreply@myntex.io.vn', // Tạo trong cPanel
  'smtp_password' => 'email-password-here', // ĐỔI PASSWORD EMAIL
  'smtp_encryption' => 'tls',
  'from_email' => 'noreply@myntex.io.vn',
  'from_name' => 'Myntex Portfolio Admin',
  'admin_email' => 'thuyet.nguyenminh03@gmail.com',
),
```

## 3️⃣ TẠO EMAIL TRONG CPANEL

1. Vào **Email Accounts** trong cPanel
2. Tạo email: `noreply@myntex.io.vn`
3. Đặt password mạnh
4. Lưu password để cập nhật vào config.php

## 4️⃣ UPLOAD FILES

### Thứ tự upload:
1. **Database trước** (đã làm ở bước 1)
2. **Upload folder admin/** (toàn bộ)
3. **Upload folder assets/** (toàn bộ)
4. **Upload folder api/** (toàn bộ)
5. **Upload file gốc:** index.html, style.css, etc.

### Cấu trúc trên hosting:
```
public_html/
├── index.html
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── api/
│   └── site.php
├── admin/
│   ├── config.php (ĐÃ CẤU HÌNH)
│   ├── index.php
│   ├── dashboard.php
│   └── ...
└── database/
    └── portfolio.sql
```

## 5️⃣ SET PERMISSIONS

Trong cPanel File Manager:
```
admin/              → 755
admin/config.php    → 600 (bảo mật cao)
admin/uploads/      → 755
assets/             → 755
api/                → 755
```

## 6️⃣ TẠO .htaccess

Tạo file `.htaccess` trong thư mục gốc:
```apache
# Redirect to HTTPS (nếu có SSL)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://myntex.io.vn/$1 [R=301,L]

# Prevent directory listing
Options -Indexes

# PHP Settings
php_flag display_errors off
php_value max_execution_time 300
php_value memory_limit 256M

# Protect sensitive files
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>
```

## 7️⃣ KIỂM TRA SAU UPLOAD

### Test 1: Website chính
- Truy cập: https://myntex.io.vn
- Kiểm tra portfolio có hiển thị không
- Test click hình ảnh dự án

### Test 2: Admin Panel
- Truy cập: https://myntex.io.vn/admin/
- Login với: admin / [password đã lưu]
- Kiểm tra dashboard

### Test 3: Upload debug script
Upload file `admin/debug-cpanel.php` và truy cập:
https://myntex.io.vn/admin/debug-cpanel.php

## 8️⃣ LỖI THƯỜNG GẶP & CÁCH FIX

### ❌ Trắng trang admin
**Nguyên nhân:** PHP version cũ hoặc thiếu extension
**Fix:** Kiểm tra PHP ≥ 7.4, enable PDO, mbstring

### ❌ "Cannot connect to database"
**Nguyên nhân:** Sai database credentials
**Fix:** Kiểm tra lại config.php với thông tin từ cPanel

### ❌ Email không gửi được
**Nguyên nhân:** Gmail bị chặn hoặc sai SMTP
**Fix:** Dùng email hosting (noreply@myntex.io.vn)

### ❌ Không upload file được
**Nguyên nhân:** Folder permissions sai
**Fix:** Set admin/uploads/ thành 755

### ❌ 500 Internal Server Error
**Nguyên nhân:** Lỗi .htaccess hoặc PHP
**Fix:** Kiểm tra error logs trong cPanel

## 9️⃣ BẢO MẬT

- [ ] Đổi mật khẩu admin ngay sau khi login được
- [ ] Đổi mật khẩu database trong config.php
- [ ] Enable HTTPS nếu có SSL certificate
- [ ] Regular backup database
- [ ] Keep PHP version updated

## 🔍 TEST CHỨC NĂNG MỚI

Sau khi upload thành công, test các chức năng:
- ✅ Click hình ảnh dự án → modal mở
- ✅ Navigation giữa các ảnh
- ✅ Admin password reset qua email
- ✅ Upload projects trong admin

---

**📞 Hỗ trợ:** Nếu gặp lỗi, chụp ảnh màn hình và gửi cho tôi!

**Lưu ý:** Thay các password trong config.php bằng password thực tế của bạn!