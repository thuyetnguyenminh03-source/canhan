-- Migration script để fix lỗi database cho myntex.io.vn
-- Chạy script này trong phpMyAdmin hoặc MySQL CLI

-- 1. Rename bảng admin_users thành admins nếu cần (nếu admins chưa tồn tại)
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Thêm admin mặc định nếu chưa có
INSERT INTO admins (username, password_hash) 
SELECT 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE username='admin');

-- 3. Đảm bảo testimonials table tồn tại với đúng cấu trúc
CREATE TABLE IF NOT EXISTS testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NULL,
  author_name VARCHAR(255),
  author_title VARCHAR(255),
  avatar_url VARCHAR(255),
  content_vi TEXT,
  content_en TEXT,
  sort_order INT DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Đảm bảo tất cả các bảng có dữ liệu tối thiểu
-- Hero
INSERT INTO hero (greeting_vi, greeting_en, headline, subhead, typewriter_text) 
SELECT 'Xin chào, tôi là', 'Hello, I am', 'Minh Thuyết', 'Thiết kế đồ hoạ BĐS • Brand Identity • Social Ads', 'Tôi biến insight thành thiết kế có hiệu quả kinh doanh'
WHERE NOT EXISTS (SELECT 1 FROM hero LIMIT 1);

-- Contact info
INSERT INTO contact_info (phone, email) 
SELECT '(+84) 0912.275.643', 'hi@myntex.io.vn'
WHERE NOT EXISTS (SELECT 1 FROM contact_info LIMIT 1);

-- 5. Xóa bảng cũ admin_users nếu tồn tại và khác biệt
-- (Chỉ chạy nếu dữ liệu đã được migrate)
-- DROP TABLE IF EXISTS admin_users;

-- 6. Thêm dữ liệu mẫu cho timeline
INSERT INTO timeline (time_range, company, description_vi, sort_order) 
SELECT '11/2024 – 2025', 'Công ty CP Tư vấn & Đầu tư BĐS An Khang', 'Design xây dựng bộ nhận diện, KV chiến dịch cho danh mục dự án cao cấp.', 1
WHERE NOT EXISTS (SELECT 1 FROM timeline WHERE company='Công ty CP Tư vấn & Đầu tư BĐS An Khang' LIMIT 1);

-- Status check
SELECT 'Migration completed successfully!' AS status;
