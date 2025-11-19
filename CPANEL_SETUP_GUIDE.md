# 📋 HƯỚNG DẪN CẤU HÌNH CHO CPANEL

## 1. Tạo Database trong cPanel

1. **Đăng nhập cPanel**
2. **Vào MySQL Database Wizard**
3. **Tạo database mới** (vd: `youruser_portfolio`)
4. **Tạo user mới** (vd: `youruser_admin`)
5. **Gán quyền ALL PRIVILEGES** cho user

## 2. Import Database

1. **Vào phpMyAdmin** trong cPanel
2. **Chọn database** vừa tạo
3. **Import file** `database/portfolio.sql`

## 3. Cập Nhật Config

Sau khi có thông tin database từ cPanel, cập nhật file `admin/config.php`:

```php
'db' => array (
  'host' => 'localhost', // hoặc IP server nếu khác
  'name' => 'youruser_portfolio', // database name từ cPanel
  'user' => 'youruser_admin', // username từ cPanel
  'pass' => 'your-strong-password', // password bạn đặt
  'charset' => 'utf8mb4',
),
```

## 4. Email Configuration cho cPanel

### Option A: Dùng Email Hosting (Khuyên dùng)
Thay vì Gmail, dùng email tạo trong cPanel:

```php
'email' => array (
  'enabled' => true,
  'smtp_host' => 'mail.yourdomain.com', // thay yourdomain.com
  'smtp_port' => 587,
  'smtp_username' => 'noreply@yourdomain.com',
  'smtp_password' => 'email-password',
  'smtp_encryption' => 'tls',
  'from_email' => 'noreply@yourdomain.com',
  'from_name' => 'Portfolio Admin',
  'admin_email' => 'your-email@domain.com',
),
```

### Option B: Tiếp tục dùng Gmail (nếu hosting cho phép)
Giữ nguyên Gmail config nhưng kiểm tra:
- Port 587 có mở không
- SSL extension có enable không

## 5. File Permissions

Trong cPanel File Manager, set permissions:
```bash
# Folders
admin/          -> 755
admin/uploads/  -> 755
assets/         -> 755

# Files
*.php           -> 644
config.php      -> 600 (riêng file này nên đặt 600)
```

## 6. .htaccess cho cPanel

Tạo file `.htaccess` trong thư mục gốc:

```apache
# Redirect to HTTPS (nếu có SSL)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]

# Prevent directory listing
Options -Indexes

# PHP Settings (tùy hosting)
php_flag display_errors off
php_value max_execution_time 300
php_value memory_limit 256M
```

Tạo file `admin/.htaccess` để bảo vệ admin:

```apache
# Protect admin directory
AuthType Basic
AuthName "Admin Panel"
AuthUserFile /home/youruser/public_html/admin/.htpasswd
Require valid-user

# Deny access to sensitive files
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>
```

## 7. Kiểm Tra Lỗi

Nếu admin panel không hoạt động:

1. **Tạo file debug** `admin/debug.php`:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'bootstrap.php';

echo "<h1>Debug Info</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO Available: " . (class_exists('PDO') ? 'Yes' : 'No') . "</p>";

// Test database connection
try {
    $db = new PDO("mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}", 
                  $config['db']['user'], 
                  $config['db']['pass']);
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test email
if ($config['email']['enabled']) {
    echo "<p>📧 Email enabled</p>";
} else {
    echo "<p>📧 Email disabled</p>";
}
```

2. **Truy cập** `yourdomain.com/admin/debug.php`

## 8. Security Checklist

- [ ] Đổi mật khẩu admin mạnh
- [ ] Enable HTTPS nếu có SSL
- [ ] Set đúng file permissions
- [ ] Backup database thường xuyên
- [ ] Keep PHP version updated
- [ ] Use strong database password

## 9. Upload Files

Upload theo thứ tự:
1. **Database structure** (import SQL)
2. **Config files** (đã chỉnh sửa)
3. **Admin folder** (full)
4. **Assets folder** (full)
5. **Root files** (index.html, etc)

## 10. Test Sau Upload

1. **Truy cập website chính** → should work
2. **Truy cập admin** → should show login
3. **Test login** → với credentials đã lưu
4. **Test email** → nếu có chức năng
5. **Test upload** → nếu có chức năng

---

**Lưu ý:** Thay `youruser`, `yourdomain.com` bằng thông tin thực tế của bạn!