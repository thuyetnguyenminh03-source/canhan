<?php
if (!isset($pageTitle)) $pageTitle = 'MyText CMS';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($pageTitle) ?> - MyText CMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
      --glass-bg: rgba(255, 255, 255, 0.25);
      --glass-border: rgba(255, 255, 255, 0.18);
      --shadow-elegant: 0 8px 32px rgba(0, 0, 0, 0.1);
      --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
      --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --gradient-accent: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    [data-theme="dark"] {
      --glass-bg: rgba(0, 0, 0, 0.25);
      --glass-border: rgba(0, 0, 0, 0.18);
      --shadow-elegant: 0 8px 32px rgba(0, 0, 0, 0.3);
      --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
    }

    [data-theme="dark"] body {
      background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
      color: #e2e8f0;
    }

    .glass-card {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      box-shadow: var(--shadow-elegant);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
      box-shadow: var(--shadow-hover);
    }

    .sidebar {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border-right: 1px solid var(--glass-border);
    }

    .nav-link {
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      transform: translateX(4px);
    }

    .nav-link.active {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
      border: 1px solid rgba(102, 126, 234, 0.3);
    }

    .btn-elegant {
      background: var(--gradient-primary);
      border: none;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      padding: 12px 24px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-elegant::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn-elegant:hover::before {
      left: 100%;
    }

    .btn-elegant:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .theme-toggle {
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-border);
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .theme-toggle:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar w-64 fixed h-full z-10">
      <div class="p-6 border-b border-white/10">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
            <i class="fas fa-crown text-white text-lg"></i>
          </div>
          <div>
            <h2 class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">MyText CMS</h2>
            <p class="text-sm text-gray-400">Premium Dashboard</p>
          </div>
        </div>
      </div>
      <nav class="px-4 py-4 space-y-2">
        <a href="dashboard.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'dashboard' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-tachometer-alt mr-3 <?= $current_page === 'dashboard' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Tổng quan
        </a>
        <a href="hero-edit.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'hero-edit' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-image mr-3 <?= $current_page === 'hero-edit' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Hero Section
        </a>
        <a href="timeline.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'timeline' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-history mr-3 <?= $current_page === 'timeline' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Timeline
        </a>
        <a href="services.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'services' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-cogs mr-3 <?= $current_page === 'services' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Dịch vụ
        </a>
        <a href="skills.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'skills' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-star mr-3 <?= $current_page === 'skills' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Kỹ năng
        </a>
        <a href="projects.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'projects' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-folder mr-3 <?= $current_page === 'projects' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Dự án
        </a>
        <a href="testimonials.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'testimonials' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-comments mr-3 <?= $current_page === 'testimonials' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Testimonials
        </a>
        <a href="contact.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'contact' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-envelope mr-3 <?= $current_page === 'contact' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Liên hệ
        </a>
        <a href="footer.php" class="nav-link flex items-center px-4 py-3 rounded-xl <?= $current_page === 'footer' ? 'active text-blue-300 font-semibold shadow-lg' : 'hover:bg-white/5' ?> transition-all duration-300 group">
          <i class="fas fa-link mr-3 <?= $current_page === 'footer' ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' ?> transition-colors"></i> Footer
        </a>
        <div class="border-t border-white/10 my-4"></div>
        <a href="logout.php" class="nav-link flex items-center px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-all duration-300 group">
          <i class="fas fa-sign-out-alt mr-3 group-hover:text-red-300 transition-colors"></i> Đăng xuất
        </a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 ml-64 p-8">
