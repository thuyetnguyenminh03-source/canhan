# 📋 HƯỚNG DẪN ĐỔI PASSWORD DATABASE

## 🚨 LƯU Ý QUAN TRỌNG:
**Password hiện tại đang lộ: `thuyet164`** - Cần đổi ngay lập tức!

## 🔧 CÁCH 1: SỬ DỤNG GIAO DIỆN ADMIN (Khuyên dùng)

### Bước 1: Truy cập tool đổi password
```
http://localhost:8000/admin/change-db-password.php
```

### Bước 2: Chọn 1 trong 2 cách:
- **Tạo tự động**: Nhấn "Tạo mật khẩu ngẫu nhiên" 
- **Tự nhập**: Nhập mật khẩu mới (ít nhất 8 ký tự)

### Bước 3: Xác nhận
- Kiểm tra mật khẩu mới
- Nhấn "Đổi mật khẩu"
- **SAO LƯU MẬT KHẨU** - ghi lại ngay!

---

## 💻 CÁCH 2: DÙNG TERMINAL (Nâng cao)

### Bước 1: Mở terminal
```bash
cd /Users/minhthuyet/BanCaNhanHoa/18:11/CaNhan
```

### Bước 2: Chạy script đổi password
```bash
# Tự động tạo password mạnh
./change-db-password.sh

# Hoặc tự nhập password
./change-db-password.sh "YourNewPassword123!"
```

### Bước 3: Cập nhật config
Script sẽ tự động cập nhật file `admin/config.php`

---

## 📝 CÁCH 3: THỦ CÔNG (Trực tiếp MySQL)

### Bước 1: Kết nối MySQL
```bash
mysql -u root -p
```

### Bước 2: Đổi password
```sql
-- Đổi password cho user hiện tại
ALTER USER 'acegiove_portfolio'@'localhost' IDENTIFIED BY 'YourNewStrongPassword123!';
FLUSH PRIVILEGES;

-- Kiểm tra
SELECT User, Host FROM mysql.user WHERE User = 'acegiove_portfolio';
```

### Bước 3: Test kết nối
```bash
mysql -u acegiove_portfolio -p acegiove_portfolio
```

### Bước 4: Cập nhật config.php
```php
// admin/config.php
return [
  'db' => [
    'host' => 'localhost',
    'name' => 'acegiove_portfolio',
    'user' => 'acegiove_portfolio',
    'pass' => 'YourNewStrongPassword123!', // ← Đổi ở đây
    'charset' => 'utf8mb4'
  ],
  // ...
];
```

---

## ✅ KIỂM TRA SAU KHI ĐỔI

### 1. Test website
```
http://localhost:8000
http://localhost:8000/admin/
```

### 2. Test API
```bash
curl http://localhost:8000/api/project.php?slug=p1
```

### 3. Test database connection
```bash
php -r "
try {
    \$pdo = new PDO('mysql:host=localhost;dbname=acegiove_portfolio;charset=utf8mb4', 'acegiove_portfolio', 'YourNewPassword');
    echo '✅ Kết nối thành công!';
} catch (Exception \$e) {
    echo '❌ Lỗi: ' . \$e->getMessage();
}
"
```

---

## 🔒 MẬT KHẨU MẠNH

### ✅ Nên có:
- Ít nhất 12 ký tự
- Chữ hoa + chữ thường
- Số
- Ký tự đặc biệt (!@#$%^&*)

### ✅ Ví dụ mật khẩu mạnh:
```
MxP@rtf0l!0_2024#Secure
9kL$7nB2@mQ8#vF3!xD1
Thuyet@Portfolio#2024$Secure
```

### ❌ Không nên:
- `thuyet164` (hiện tại - quá yếu!)
- `password123`
- `admin2024`
- Tên riêng, ngày sinh

---

## 📱 LƯU Ý BẢO MẬT

### Sau khi đổi password:
1. **Xóa file log**: `password_changes.log`
2. **Xóa file backup**: `db_password_*.txt`
3. **Không chia sẻ password** qua email, chat
4. **Dùng password manager** (Bitwarden, 1Password,...)
5. **Đổi password định kỳ** mỗi 3-6 tháng

---

## 🆘 KHẮC PHỤC SỰ CỐ

### Lỗi kết nối sau khi đổi password:
1. Kiểm tra lại password trong config.php
2. Kiểm tra quyền user MySQL
3. Restart MySQL service
4. Kiểm tra firewall

### Không vào được admin:
1. Kiểm tra session/cookie
2. Xóa cache browser
3. Kiểm tra file permissions

---

## 📞 HỖ TRỢ

Nếu gặp lỗi:
1. Kiểm tra error logs trong MySQL
2. Kiểm tra PHP error logs
3. Test từng bước một cách cẩn thận
4. Sao lưu trước khi thực hiện

**🔴 QUAN TRỌNG**: Password hiện tại đang lộ - **ĐỔI NGAY LẬP TỨC**!