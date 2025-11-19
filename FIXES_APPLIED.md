# Lỗi Đã Sửa - Myntex.io.vn (18/11/2025)

## 🔴 CRITICAL ISSUES (Lỗi Nghiêm Trọng)

### 1. **Database Configuration Sai (CRITICAL)**
**File:** `config.php` (root)

**Lỗi:**
```php
'user' => 'root',    // ❌ SAIIII
'pass' => '',        // ❌ SAIIII
```

**Sửa:** 
```php
'user' => 'acegiove_portfolio',
'pass' => 'thuyet164',
```

**Tác động:** Website không thể kết nối MySQL, toàn bộ API bị lỗi

---

### 2. **Table Name Mismatch (CRITICAL)**
**Lỗi:** Có 6 file PHP sử dụng bảng `admin_users` nhưng database chỉ có bảng `admins`

**Các file đã sửa:**
1. `/admin/login.php` - ✅ Sửa 1 query
2. `/admin/forgot-password.php` - ✅ Sửa 4 queries
3. `/admin/check-token.php` - ✅ Sửa 2 queries
4. `/admin/password-manager.php` - ✅ Sửa 3 queries
5. `/admin/change-admin-password.php` - ✅ Sửa 2 queries
6. `/admin/security-dashboard.php` - ✅ Sửa 3 queries

**Tác động:** Admin panel login không hoạt động, quên mật khẩu bị lỗi, không thể quản lý mật khẩu

---

## ✅ Changes Applied

### Database Schema
- **File:** `/sql/schema.sql`
- **Thay đổi:** `admin_users` → `admins` (dòng 2)
- **Lý do:** Nhất quán với tên bảng thực tế

### Database Initialization
- **File:** `/database/migrate.sql` (NEW)
- **Nội dung:**
  - Tạo bảng `admins` nếu chưa tồn tại
  - Thêm admin mặc định: username=`admin`, password (bcrypt)
  - Đảm bảo testimonials table tồn tại
  - Thêm dữ liệu tối thiểu cho hero, contact, timeline

---

## 📋 Testing Checklist

### Frontend
- [ ] Homepage load được
- [ ] API `/api/site.php` trả về dữ liệu
- [ ] Project modal hiển thị đúng
- [ ] Contact form hoạt động

### Admin Panel  
- [ ] Login page load được
- [ ] Có thể login với `admin/[password]`
- [ ] Dashboard hiển thị đúng
- [ ] Quên mật khẩu hoạt động
- [ ] Password Manager hoạt động
- [ ] Security Dashboard hoạt động

---

## 🚀 Next Steps (Khuyến cáo)

1. **Run migration script:**
   ```bash
   # Chạy trong phpMyAdmin hoặc MySQL CLI
   mysql -u acegiove_portfolio -p acegiove_portfolio < database/migrate.sql
   ```

2. **Verify database:**
   ```sql
   SHOW TABLES;  -- Kiểm tra tất cả bảng tồn tại
   SELECT COUNT(*) FROM admins;  -- Kiểm tra admin user
   ```

3. **Test Login:**
   - URL: `https://myntex.io.vn/admin/login.php`
   - Username: `admin`
   - Password: [Check console/email]

4. **Monitor Errors:**
   - Check `/debug.log` for errors
   - Check Admin → Security Dashboard

---

## 📌 Important Notes

- **Password:** Admin mặc định được hash bcrypt. Reset qua "Quên Mật Khẩu" nếu cần
- **Email:** Config SMTP sẵn sàng (Gmail), nhưng link reset cũng lưu file fallback
- **Session:** Session key = `myntex_admin_session` (từ config.php)
- **CSRF:** Bảo vệ CSRF token được kích hoạt tự động trên bootstrap.php

---

## 📝 Files Modified

| File | Changes | Status |
|------|---------|--------|
| `/config.php` | DB credentials | ✅ |
| `/sql/schema.sql` | Table name | ✅ |
| `/database/migrate.sql` | NEW file | ✅ |
| `/admin/login.php` | Query fix | ✅ |
| `/admin/forgot-password.php` | 4 Query fixes | ✅ |
| `/admin/check-token.php` | 2 Query fixes | ✅ |
| `/admin/password-manager.php` | 3 Query fixes | ✅ |
| `/admin/change-admin-password.php` | 2 Query fixes | ✅ |
| `/admin/security-dashboard.php` | 3 Query fixes | ✅ |

**Total Fixes: 18 lines modified across 9 files**
