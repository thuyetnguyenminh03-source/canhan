# 📋 HƯỚNG DẪN DEPLOY LÊN HOSTING CPANEL

## ✅ CHUẨN BỊ TRƯỚC KHI UPLOAD

### 1. **Kiểm tra yêu cầu hosting:**
- **PHP Version:** 7.4+ (khuyến nghị 8.0+)
- **MySQL Version:** 5.7+ hoặc MariaDB 10.2+
- **Extensions:** PDO, PDO_MySQL, mbstring, openssl
- **Storage:** Tối thiểu 100MB

### 2. **Chuẩn bị thông tin:**
- Database name, username, password từ cPanel
- Email hosting (nếu có) hoặc tiếp tục dùng Gmail
- Domain/subdomain đã trỏ về hosting

---

## 📁 CẤU TRÚC FILE CẦN UPLOAD

```
public_html/ (hoặc thư mục web root của bạn)
├── admin/                    # Admin panel
│   ├── bootstrap.php         # Database connection
│   ├── config.php           # Config chính (cần chỉnh sửa)
│   └── ...                  # Các file admin khác
├── api/                     # API endpoints
├── assets/                  # CSS, JS, images
├── projects/                # Project pages
├── sql/                     # Database schema
├── .htaccess               # URL rewrite rules
├── index.html              # Trang chủ
└── vercel.json              # Config (nếu cần)
```

---

## 🔧 CÁC BƯỚC DEPLOY CHI TIẾT

### **Bước 1: Tạo Database trong cPanel**
1. Đăng nhập cPanel
2. Vào **MySQL Database Wizard**
3. Tạo database mới (ví dụ: `youruser_portfolio`)
4. Tạo user và password
5. Gán user vào database với **ALL PRIVILEGES**

### **Bước 2: Import Database**
1. Vào **phpMyAdmin** trong cPanel
2. Chọn database vừa tạo
3. Click **Import** → Chọn file `/sql/schema.sql`
4. Click **Go** để import

### **Bước 3: Upload Files**
1. Vào **File Manager** trong cPanel
2. Vào thư mục `public_html` (hoặc subdomain folder)
3. Upload toàn bộ file (trừ các file test đã xóa)
4. Set permissions 755 cho folders, 644 cho files

### **Bước 4: Cấu Hình Database**
1. Mở file `/admin/config.php`
2. Sửa thông tin database:

```php
return array (
  'db' => 
  array (
    'host' => 'localhost',        // Thường là localhost
    'name' => 'youruser_portfolio', // Database name từ Bước 1
    'user' => 'youruser_dbuser',   // Database user từ Bước 1
    'pass' => 'your_password',      // Database password
    'charset' => 'utf8mb4',
  ),
  // ... rest of config
);
```

### **Bước 5: Cấu Hình Email (2 Tùy Chọn)**

#### **Option A: Dùng Gmail SMTP (Khuyến nghị)**
- Giữ nguyên config Gmail trong `/admin/config.php`
- Đảm bảo app password vẫn hoạt động

#### **Option B: Dùng Email Hosting**
- Sửa config email trong `/admin/config.php`:

```php
'email' => 
array (
  'enabled' => true,
  'smtp_host' => 'mail.yourdomain.com',  // SMTP hosting
  'smtp_port' => 587,
  'smtp_username' => 'noreply@yourdomain.com',
  'smtp_password' => 'email_password',
  'smtp_encryption' => 'tls',
  'from_email' => 'noreply@yourdomain.com',
  'from_name' => 'Portfolio Admin',
  'admin_email' => 'your-email@gmail.com',
),
```

---

## 🔐 BẢO MẬT QUAN TRỌNG

### **1. File .htaccess bảo vệ admin:**
```apache
# Bảo vệ thư mục admin
<FilesMatch "\.(php|log|txt|md)$">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Cho phép access admin từ IP cụ thể (tùy chọn)
<FilesMatch "^admin/">
    Order Deny,Allow
    Deny from all
    Allow from YOUR_IP_ADDRESS
</FilesMatch>
```

### **2. Bảo vệ file config:**
```apache
# Chặn access file config
<Files "config.php">
    Order Deny,Allow
    Deny from all
</FilesMatch>
```

### **3. Các biện pháp bảo mật khác:**
- Đổi tên folder `admin` thành tên khó đoán (ví dụ: `xyz123admin`)
- Sử dụng mật khẩu mạnh cho admin
- Cập nhật PHP version mới nhất
- Enable SSL/HTTPS

---

## 🧪 KIỂM TRA SAU KHI DEPLOY

### **1. Test trang chủ:**
- Truy cập: `https://yourdomain.com`
- Kiểm tra hiển thị đúng

### **2. Test admin panel:**
- Truy cập: `https://yourdomain.com/admin/`
- Login với user: `admin`, password: `NewAdmin@2025`

### **3. Test email:**
- Vào **Admin Panel → Test Email**
- Gửi test email để kiểm tra SMTP

### **4. Test password reset:**
- Vào **Admin Panel → Password Manager**
- Click "Forgot Password" để test email reset

---

## 🚨 LỖI THƯỜNG GẶP & CÁCH KHẮC PHỤC

### **Lỗi 1: "500 Internal Server Error"**
**Nguyên nhân:** PHP version cũ, file permissions sai
**Giải pháp:** 
- Kiểm tra PHP version (nên dùng 8.0+)
- Set permissions: Folders 755, Files 644
- Kiểm tra error logs trong cPanel

### **Lỗi 2: "Cannot connect to database"**
**Nguyên nhân:** Database config sai
**Giải pháp:**
- Kiểm tra host (thường là localhost)
- Kiểm tra database name, user, password
- Test connection trong phpMyAdmin

### **Lỗi 3: "Email not sending"**
**Nguyên nhân:** SMTP config sai, hosting chặn port
**Giải pháp:**
- Thử Option B (email hosting) thay vì Gmail
- Kiểm tra port 587 có bị chặn không
- Liên hệ hosting provider để mở port

### **Lỗi 4: "Token không hợp lệ"**
**Nguyên nhân:** Timezone server khác
**Giải pháp:**
- Đã fix trong code (dùng PHP time thay vì MySQL NOW)
- Nếu vẫn lỗi, liên hệ hosting để set timezone đúng

---

## 📞 HỖ TRỢ

Nếu gặp lỗi khi deploy:
1. Kiểm tra error logs trong cPanel
2. Chụp ảnh lỗi gửi cho mình
3. Cung cấp thông tin hosting (PHP version, MySQL version)

**Good luck! 🚀**