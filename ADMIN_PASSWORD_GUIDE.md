# 📋 HƯỚNG DẪN VÀO WEB ADMIN ĐỔI MẬT KHẨU

## 🎯 BƯỚC 1: TRUY CẬP TRANG LOGIN
**URL:** http://localhost:8000/admin/login.php

## 🔑 BƯỚC 2: ĐĂNG NHẬP
**Tên đăng nhập:** `admin`  
**Mật khẩu hiện tại:** `MySecurePass2025!` (mật khẩu vừa đổi gần đây)

*Lưu ý: Nếu quên mật khẩu, dùng công cụ dòng lệnh:*
```bash
php manual-change-admin-password.php admin MậtKhẩuMới123!
```

## ⚙️ BƯỚC 3: VÀO TRANG ĐỔI MẬT KHẨU
Sau khi đăng nhập thành công, có 2 cách để vào trang đổi mật khẩu:

### Cách 1: Qua Menu
1. Nhìn vào menu bên trái
2. Click vào **"🔑 Change Admin Password"**

### Cách 2: Truy cập trực tiếp
**URL:** http://localhost:8000/admin/change-admin-password.php

## 🔄 BƯỚC 4: ĐỔI MẬT KHẨU
1. **Mật khẩu hiện tại:** Nhập mật khẩu đang dùng
2. **Mật khẩu mới:** Nhập mật khẩu mới (hoặc nhấn "Generate Strong Password")
3. **Xác nhận mật khẩu:** Nhập lại mật khẩu mới
4. Click **"Change Password"**

## ✅ YÊU CẦU MẬT KHẨU MỚI
- Ít nhất 8 ký tự
- Có chữ hoa (A-Z)
- Có chữ thường (a-z) 
- Có số (0-9)
- Có ký tự đặc biệt (!@#$%^&*...)

## 📊 KIỂM TRA SAU KHI ĐỔI
Sau khi đổi thành công:
1. Bạn sẽ tự động bị đăng xuất
2. Đăng nhập lại với mật khẩu mới
3. Kiểm tra tại: http://localhost:8000/admin/security-dashboard.php

## 🛡️ CÔNG CỤ HỖ TRỢ
Nếu gặp vấn đề, dùng các công cụ:
```bash
# Kiểm tra trạng thái
php admin/check-admin-status.php

# Tạo mật khẩu ngẫu nhiên
php generate-admin-password.php 16

# Đổi mật khẩu nhanh
php manual-change-admin-password.php admin MậtKhẩuMới123!
```

## 🚨 LƯU Ý BẢO MẬT
- Không dùng mật khẩu đã từng dùng ở nơi khác
- Không chia sẻ mật khẩu qua email/chat
- Nên đổi mật khẩu định kỳ 3-6 tháng
- Dùng trình quản lý mật khẩu để lưu trữ