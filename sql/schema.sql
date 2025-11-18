-- Admin users (giữ bảng hiện có, thêm nếu thiếu)
CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Hero section
CREATE TABLE IF NOT EXISTS hero (
  id INT AUTO_INCREMENT PRIMARY KEY,
  greeting_vi VARCHAR(255),
  greeting_en VARCHAR(255),
  headline VARCHAR(255),
  subhead VARCHAR(255),
  typewriter_text TEXT,
  cv_link VARCHAR(255),
  behance_link VARCHAR(255),
  instagram_link VARCHAR(255),
  tiktok_link VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Timeline (About)
CREATE TABLE IF NOT EXISTS timeline (
  id INT AUTO_INCREMENT PRIMARY KEY,
  time_range VARCHAR(255),
  company VARCHAR(255),
  description_vi TEXT,
  description_en TEXT,
  sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Services
CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title_vi VARCHAR(255),
  title_en VARCHAR(255),
  description_vi TEXT,
  description_en TEXT,
  sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Skills
CREATE TABLE IF NOT EXISTS skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  logo_url VARCHAR(255),
  sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact info
CREATE TABLE IF NOT EXISTS contact_info (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phone VARCHAR(50),
  email VARCHAR(255),
  address_vi VARCHAR(255),
  address_en VARCHAR(255),
  facebook_url VARCHAR(255),
  instagram_url VARCHAR(255),
  tiktok_url VARCHAR(255),
  zalo_url VARCHAR(255),
  whatsapp_url VARCHAR(255),
  map_embed TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Footer links
CREATE TABLE IF NOT EXISTS footer_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  section VARCHAR(50), -- about, projects, docs, socials
  name_vi VARCHAR(255),
  name_en VARCHAR(255),
  url VARCHAR(255),
  sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Footer info
CREATE TABLE IF NOT EXISTS footer_info (
  id INT AUTO_INCREMENT PRIMARY KEY,
  copyright_vi VARCHAR(255),
  copyright_en VARCHAR(255),
  extra_html TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Projects (giữ bảng hiện có, tạo nếu thiếu)
CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(255) UNIQUE NOT NULL,
  title_vi VARCHAR(255) NOT NULL,
  title_en VARCHAR(255),
  description_vi TEXT,
  description_en TEXT,
  sort_order INT DEFAULT 0,
  summary_vi TEXT,
  summary_en TEXT,
  meta_role VARCHAR(255),
  meta_time VARCHAR(255),
  meta_tools VARCHAR(255),
  objective_vi TEXT,
  objective_en TEXT,
  challenge_vi TEXT,
  challenge_en TEXT,
  strategy_vi TEXT,
  strategy_en TEXT,
  workflow_vi TEXT,
  workflow_en TEXT,
  kpi1 VARCHAR(255),
  kpi2 VARCHAR(255),
  kpi3 VARCHAR(255),
  kpi4 VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Project media
CREATE TABLE IF NOT EXISTS project_media (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  section VARCHAR(50) DEFAULT 'gallery',
  url VARCHAR(255) NOT NULL,
  title VARCHAR(255),
  sort_order INT DEFAULT 0,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Testimonials (có thể gán project_id hoặc để NULL dùng chung)
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
