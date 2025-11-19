-- SQL Script để tạo các bảng thiếu cho myntex.io.vn
-- Chạy script này trong phpMyAdmin hoặc MySQL

-- Tạo bảng admins nếu chưa có
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng password_resets nếu chưa có
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_token` (`token`),
  KEY `idx_username` (`username`),
  KEY `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng site_settings nếu chưa có
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert admin mặc định (password: admin123)
INSERT INTO `admins` (`username`, `password`, `email`, `is_active`) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'thuyet.nguyenminh03@gmail.com', 1);

-- Insert site settings mặc định
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `description`) VALUES 
('site_title', 'Myntex Portfolio', 'Website title'),
('site_description', 'Portfolio của Minh Thuyết - Thiết kế đồ họa BĐS & Brand Identity', 'Website description'),
('contact_email', 'thuyet.nguyenminh03@gmail.com', 'Contact email'),
('contact_phone', '0912.275.643', 'Contact phone'),
('social_behance', 'https://www.behance.net/thuytnguynminh', 'Behance profile'),
('social_instagram', 'https://www.instagram.com/myntex.dsn/', 'Instagram profile'),
('social_tiktok', 'https://www.tiktok.com/@myntex_dsn', 'TikTok profile'),
('created_at', '2025-01-01 00:00:00', 'Site creation date');

-- Grant permissions (chạy với user có quyền)
GRANT SELECT, INSERT, UPDATE, DELETE ON myntexio_portfolio.* TO 'myntexio_admin'@'localhost';
FLUSH PRIVILEGES;

-- Thông báo hoàn thành
SELECT '✅ Database tables created successfully for myntex.io.vn!' AS status;