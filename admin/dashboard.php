<?php
require __DIR__ . '/bootstrap.php';
require_login();

// Thống kê tổng quan
try {
  $stats = [
    'projects' => $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
    'testimonials' => $pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn(),
    'services' => $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn(),
    'skills' => $pdo->query('SELECT COUNT(*) FROM skills')->fetchColumn(),
    'timeline' => $pdo->query('SELECT COUNT(*) FROM timeline')->fetchColumn(),
    'media' => $pdo->query('SELECT COUNT(*) FROM project_media')->fetchColumn()
  ];
} catch (Throwable $e) {
  $stats = ['projects' => 0, 'testimonials' => 0, 'services' => 0, 'skills' => 0, 'timeline' => 0, 'media' => 0];
}

// Dự án gần đây
try {
  $recent_projects = $pdo->query('
    SELECT id, title_vi, title_en, slug
    FROM projects
    ORDER BY id DESC
    LIMIT 5
  ')->fetchAll();
} catch (Throwable $e) {
  $recent_projects = [];
}

// Testimonials gần đây
try {
  $recent_testimonials = $pdo->query('
    SELECT id, author_name, content_vi
    FROM testimonials
    ORDER BY id DESC
    LIMIT 3
  ')->fetchAll();
} catch (Throwable $e) {
  $recent_testimonials = [];
}

// Kiểm tra dữ liệu mẫu
$has_sample_data = $stats['projects'] > 0 || $stats['testimonials'] > 0;
$pageTitle = 'Dashboard';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">Dashboard</h1>
            <p class="text-gray-600">Tổng quan về hệ thống quản lý</p>
          </div>
          <div class="flex items-center space-x-3">
            <button onclick="toggleTheme()" class="theme-toggle p-3">
              <i class="fas fa-moon text-gray-600"></i>
            </button>
            <a href="../index.html" target="_blank" class="btn-elegant inline-flex items-center">
              <i class="fas fa-external-link-alt mr-2"></i> Xem website
            </a>
          </div>
        </div>
      </div>

      <?= flash_message(); ?>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6 stat-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Dự án</p>
              <p class="text-3xl font-bold text-blue-600"><?= $stats['projects'] ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-folder text-blue-600 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="glass-card p-6 stat-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Đánh giá</p>
              <p class="text-3xl font-bold text-green-600"><?= $stats['testimonials'] ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-comments text-green-600 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="glass-card p-6 stat-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Dịch vụ</p>
              <p class="text-3xl font-bold text-purple-600"><?= $stats['services'] ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-cogs text-purple-600 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="glass-card p-6 stat-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Kỹ năng</p>
              <p class="text-3xl font-bold text-orange-600"><?= $stats['skills'] ?></p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-star text-orange-600 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="glass-card p-6 stat-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Timeline</p>
              <p class="text-3xl font-bold text-teal-600"><?= $stats['timeline'] ?></p>
            </div>
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-history text-teal-600 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="glass-card p-6 stat-card">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Hình ảnh</p>
              <p class="text-3xl font-bold text-pink-600"><?= $stats['media'] ?></p>
            </div>
            <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-images text-pink-600 text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="glass-card p-6 mb-8">
        <h3 class="section-title text-xl mb-6 flex items-center">
          <i class="fas fa-bolt text-yellow-400 mr-3"></i>
          Hành động nhanh
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <a href="projects.php" class="btn-elegant p-4 text-center">
            <i class="fas fa-plus text-xl mb-2"></i>
            <div class="text-sm">Thêm dự án</div>
          </a>
          <a href="testimonials.php" class="btn-elegant p-4 text-center">
            <i class="fas fa-comments text-xl mb-2"></i>
            <div class="text-sm">Thêm đánh giá</div>
          </a>
          <a href="services.php" class="btn-elegant p-4 text-center">
            <i class="fas fa-cogs text-xl mb-2"></i>
            <div class="text-sm">Thêm dịch vụ</div>
          </a>
          <a href="skills.php" class="btn-elegant p-4 text-center">
            <i class="fas fa-star text-xl mb-2"></i>
            <div class="text-sm">Thêm kỹ năng</div>
          </a>
        </div>
      </div>

      <!-- Recent Projects -->
      <div class="glass-card p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
          <h3 class="section-title text-xl flex items-center">
            <i class="fas fa-clock text-blue-400 mr-3"></i>
            Dự án gần đây
          </h3>
          <a href="projects.php" class="text-blue-600 hover:text-blue-700 font-medium">Xem tất cả</a>
        </div>
        <?php if (!empty($recent_projects)): ?>
          <div class="space-y-3">
            <?php foreach ($recent_projects as $project): ?>
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-blue-600"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900"><?= htmlspecialchars($project['title_vi']) ?></p>
                    <p class="text-sm text-gray-500">Slug: <?= htmlspecialchars($project['slug']) ?></p>
                  </div>
                </div>
                <a href="project-media.php?id=<?= $project['id'] ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                  Quản lý hình ảnh
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
            </div>
            <p class="text-gray-500">Chưa có dự án nào</p>
            <a href="projects.php" class="text-blue-600 hover:text-blue-700 font-medium">Thêm dự án đầu tiên</a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Recent Testimonials -->
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="section-title text-xl flex items-center">
            <i class="fas fa-comments text-green-400 mr-3"></i>
            Đánh giá gần đây
          </h3>
          <a href="testimonials.php" class="text-green-600 hover:text-green-700 font-medium">Xem tất cả</a>
        </div>
        <?php if (!empty($recent_testimonials)): ?>
          <div class="space-y-3">
            <?php foreach ($recent_testimonials as $testimonial): ?>
              <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-user text-green-600"></i>
                </div>
                <div class="flex-1">
                  <p class="font-medium text-gray-900"><?= htmlspecialchars($testimonial['author_name']) ?></p>
                  <p class="text-sm text-gray-600 italic">"<?= htmlspecialchars(substr($testimonial['content_vi'], 0, 100)) ?>..."</p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-comments text-gray-400 text-3xl"></i>
            </div>
            <p class="text-gray-500">Chưa có đánh giá nào</p>
            <a href="testimonials.php" class="text-green-600 hover:text-green-700 font-medium">Thêm đánh giá đầu tiên</a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sample Data Notice -->
      <?php if (!$has_sample_data): ?>
        <div class="mt-8 glass-card p-6 border-l-4 border-yellow-400 bg-gradient-to-r from-yellow-50/50 to-orange-50/50">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
              <i class="fas fa-lightbulb text-white text-lg"></i>
            </div>
            <div class="flex-1">
              <h4 class="text-yellow-800 font-bold text-lg mb-2">Website chưa có dữ liệu mẫu</h4>
              <p class="text-yellow-700 text-sm leading-relaxed mb-4">
                Hãy thêm một số dự án, testimonials và thông tin khác để website của bạn trở nên sinh động và chuyên nghiệp hơn. Đây là cơ hội để giới thiệu công việc của bạn với khách hàng tiềm năng.
              </p>
              <div class="flex flex-wrap gap-3">
                <a href="projects.php" class="btn-elegant text-sm px-4 py-2">
                  <i class="fas fa-plus mr-2"></i> Thêm Dự Án
                </a>
                <a href="testimonials.php" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 text-sm font-medium">
                  <i class="fas fa-comments mr-2"></i> Thêm Review
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

<?php require __DIR__ . '/_layout-footer.php'; ?>
