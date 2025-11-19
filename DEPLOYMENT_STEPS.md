# Hướng Dẫn Deploy Myntex.io.vn - Sửa Lỗi

## 📋 Quy Trình Deploy Nhanh

### Bước 1: Upload Files Lên Server

```bash
# Qua FTP hoặc cPanel File Manager, upload/overwrite các files:
config.php                         # Database config fix
sql/schema.sql                     # Table schema update  
database/migrate.sql               # New migration script
admin/login.php                    # Admin queries fix
admin/forgot-password.php          # Password reset fix
admin/check-token.php              # Token validation fix
admin/password-manager.php         # Password manager fix
admin/change-admin-password.php    # Change password fix
admin/security-dashboard.php       # Security stats fix
```

### Bước 2: Chạy Migration Script

#### **Cách 1: Qua phpMyAdmin (Đơn giản nhất)**

1. Truy cập: `https://myntex.io.vn/cpanel` → phpmyadmin
2. Chọn database: `acegiove_portfolio`
3. Click tab "SQL"
4. Copy toàn bộ nội dung từ `database/migrate.sql`
5. Paste vào và click "Go"

#### **Cách 2: Qua MySQL CLI (SSH)**

```bash
ssh user@myntex.io.vn
mysql -u acegiove_portfolio -p acegiove_portfolio < database/migrate.sql
# Nhập password: thuyet164
```

#### **Cách 3: Qua cPanel Terminal**

1. Login cPanel
2. Chọn "Terminal" 
3. Chạy:
```bash
cd /home/acegiove/public_html
mysql -u acegiove_portfolio -p < database/migrate.sql
```

---

### Bước 3: Xác Minh Kết Quả

#### **Trong phpMyAdmin:**

```sql
-- Kiểm tra tất cả bảng có
SHOW TABLES;

-- Kiểm tra admin user tồn tại
SELECT COUNT(*) as total_admins FROM admins;
SELECT * FROM admins LIMIT 1;

-- Kiểm tra testimonials table
DESCRIBE testimonials;

-- Kiểm tra dữ liệu hero
SELECT COUNT(*) FROM hero;
```

#### **Trên Website:**

1. **Frontend:**
   - Mở https://myntex.io.vn/
   - Nếu thấy data (hero section, projects), OK ✅

2. **API Test:**
   - Mở https://myntex.io.vn/api/site.php
   - Nếu thấy JSON response, OK ✅

3. **Admin Panel:**
   - Mở https://myntex.io.vn/admin/login.php
   - Nếu login form hiển thị, OK ✅
   - Login: `admin` / `[password]`

---

## 🔒 Mật Khẩu Admin

### Mật Khẩu Mặc Định
Từ script migration, admin user được tạo với:
- **Username:** `admin`
- **Password Hash:** `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi` (bcrypt)
- **Đây là password mặc định cho testing**

### Thay Đổi Mật Khẩu

#### **Cách 1: Qua Admin Dashboard**
1. Login: https://myntex.io.vn/admin/login.php
2. Dashboard → Quản lý Mật khẩu
3. Click "Generate New Password" hoặc "Create Reset Link"

#### **Cách 2: Qua "Quên Mật Khẩu"**
1. https://myntex.io.vn/admin/forgot-password.php
2. Click "Tạo link reset mật khẩu"
3. Copy link từ message/file
4. Mở link và set password mới

#### **Cách 3: Qua SQL (Nếu quên hoàn toàn)**
```sql
-- Generate new password trước:
-- $ php -r "echo password_hash('MyNewPassword123!', PASSWORD_DEFAULT);"

UPDATE admins SET password_hash = '$2y$10$...' WHERE username = 'admin';
```

---

## 🔧 Troubleshooting

### ❌ "Database connection failed"

**Nguyên nhân:** Config database sai

**Giải pháp:**
```php
// Kiểm tra /config.php có:
'user' => 'acegiove_portfolio',  // ✅
'pass' => 'thuyet164',            // ✅
'name' => 'acegiove_portfolio',   // ✅
```

### ❌ "Table admin_users doesn't exist"

**Nguyên nhân:** File chưa được update

**Giải pháp:**
- Kiểm tra các files PHP đã upload đúng
- Run lại migration script từ `database/migrate.sql`

### ❌ Admin login fail "Sai tài khoản hoặc mật khẩu"

**Nguyên nhân:** Admin user chưa được tạo

**Giải pháp:**
```sql
-- Tạo admin user:
INSERT INTO admins (username, password_hash) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
```

### ❌ "Email sending failed" trên forgot-password

**Lý do:** SMTP config hoặc firewall block email

**Giải pháp:**
- Reset link sẽ lưu vào file `/admin/reset_password_link.txt`
- Hoặc sửa email config trong `/admin/config.php`

---

## ✅ Confirmation Checklist

- [ ] Tất cả files đã upload
- [ ] Migration script đã chạy thành công
- [ ] Database tables tồn tại (SHOW TABLES)
- [ ] Admin user tồn tại (SELECT FROM admins)
- [ ] Frontend load dữ liệu từ API
- [ ] Admin panel login hoạt động
- [ ] Password reset hoạt động
- [ ] Không có error logs

---

## 📞 Support

Nếu có lỗi:
1. Check `/debug.log` file
2. Check Admin → Security Dashboard → Error logs
3. Kiểm tra lại config.php credentials
4. Test API: https://myntex.io.vn/api/site.php

---

**Last Updated:** 18/11/2025  
**Status:** Ready for Production ✅
