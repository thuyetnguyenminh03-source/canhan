<?php
require __DIR__ . '/bootstrap.php';
require_login();

ini_set('display_errors', '1');
error_reporting(E_ALL);

$error = '';
$editing = null; // Khởi tạo để tránh Notice

// Lấy danh sách dự án
try {
  $items = $pdo->query('
    SELECT id, title_vi, title_en, slug, description_vi, description_en, sort_order
    FROM projects
    ORDER BY sort_order, id
  ')->fetchAll();
} catch (Throwable $e) {
  $error = 'Lỗi: ' . $e->getMessage();
}

// Thêm/Sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    verify_csrf_or_die();

    $id = (int)($_POST['id'] ?? 0);
    $title_vi = trim($_POST['title_vi'] ?? '');
    $title_en = trim($_POST['title_en'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description_vi = trim($_POST['description_vi'] ?? '');
    $description_en = trim($_POST['description_en'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($title_vi === '' || $slug === '') {
      $error = 'Tiêu đề (VI) và Slug không được trống.';
    } else {
      if ($id > 0) {
        $stmt = $pdo->prepare('
          UPDATE projects
          SET title_vi = ?, title_en = ?, slug = ?, description_vi = ?, description_en = ?, sort_order = ?
          WHERE id = ?
        ');
        $stmt->execute([$title_vi, $title_en, $slug, $description_vi, $description_en, $sort_order, $id]);
        redirect_with_message('projects.php', 'Đã cập nhật dự án.');
      } else {
        $stmt = $pdo->prepare('
          INSERT INTO projects (title_vi, title_en, slug, description_vi, description_en, sort_order)
          VALUES (?,?,?,?,?,?)
        ');
        $stmt->execute([$title_vi, $title_en, $slug, $description_vi, $description_en, $sort_order]);
        redirect_with_message('projects.php', 'Đã thêm dự án.');
      }
    }
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}

// Xóa
if (isset($_GET['delete'])) {
  try {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM projects WHERE id=?')->execute([$id]);
    redirect_with_message('projects.php', 'Đã xóa dự án.');
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}

// Dữ liệu chỉnh sửa
if (isset($_GET['edit'])) {
  try {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('
      SELECT id, title_vi, title_en, slug, description_vi, description_en, sort_order
      FROM projects
      WHERE id = ?
    ');
    $stmt->execute([$id]);
    $editing = $stmt->fetch() ?: [];
    if (!$editing) $error = 'Không tìm thấy dự án cần sửa.';
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}
$pageTitle = 'Quản lý Dự án';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">Quản lý Dự án</h1>
            <p class="text-gray-600">Thêm, sửa, xóa các dự án của bạn</p>
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
      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- Form thêm/sửa -->
      <form method="post" class="glass-card p-8 mb-8">
        <h2 class="text-xl font-bold mb-6 flex items-center">
          <i class="fas fa-edit text-blue-400 mr-3"></i>
          <?= !empty($editing) ? 'Chỉnh sửa dự án' : 'Thêm dự án mới' ?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?= csrf_field(); ?>
        <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề (VI) *</label>
            <input type="text" name="title_vi" value="<?= htmlspecialchars($editing['title_vi'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Nhập tiêu đề tiếng Việt" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề (EN)</label>
            <input type="text" name="title_en" value="<?= htmlspecialchars($editing['title_en'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter English title">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Slug *</label>
            <input type="text" name="slug" value="<?= htmlspecialchars($editing['slug'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="ten-du-an" required>
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-info-circle mr-1"></i>
              Slug dùng để tạo URL dự án (chỉ chữ thường, số, dấu gạch ngang)
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Thứ tự</label>
            <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-sort-numeric-down mr-1"></i>
              Số nhỏ hơn hiển thị trước
            </p>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả (VI)</label>
            <textarea name="description_vi" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Mô tả dự án bằng tiếng Việt"><?= htmlspecialchars($editing['description_vi'] ?? '') ?></textarea>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả (EN)</label>
            <textarea name="description_en" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Project description in English"><?= htmlspecialchars($editing['description_en'] ?? '') ?></textarea>
          </div>

          <div class="md:col-span-2 flex items-center space-x-3 pt-4">
            <button type="submit" class="btn-elegant inline-flex items-center">
              <i class="fas fa-<?= !empty($editing) ? 'save' : 'plus' ?> mr-2"></i>
              <?= !empty($editing) ? 'Cập nhật dự án' : 'Thêm dự án mới' ?>
            </button>
            <?php if (!empty($editing)): ?>
              <a href="projects.php" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold inline-flex items-center">
                <i class="fas fa-times mr-2"></i> Hủy chỉnh sửa
              </a>
            <?php endif; ?>
          </div>
        </div>
      </form>

      <!-- Bảng dữ liệu -->
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-list text-purple-400 mr-3"></i>
            Danh sách dự án
          </h2>
          <div class="flex items-center space-x-3">
            <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl font-semibold shadow-lg">
              <i class="fas fa-folder mr-2"></i>
              Tổng: <?= count($items) ?>
            </span>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                <th class="text-left p-4 font-bold rounded-tl-xl">ID</th>
                <th class="text-left p-4 font-bold">Tiêu đề (VI)</th>
                <th class="text-left p-4 font-bold">Tiêu đề (EN)</th>
                <th class="text-left p-4 font-bold">Slug</th>
                <th class="text-left p-4 font-bold">Thứ tự</th>
                <th class="text-left p-4 font-bold rounded-tr-xl">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $it): ?>
                <tr class="border-b border-gray-100 hover:bg-blue-50/50 transition-colors">
                  <td class="p-4">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 text-white rounded-lg inline-flex items-center justify-center font-bold text-xs">
                      <?= $it['id'] ?>
                    </span>
                  </td>
                  <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($it['title_vi']) ?></td>
                  <td class="p-4 text-gray-600"><?= htmlspecialchars($it['title_en']) ?></td>
                  <td class="p-4">
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-mono">
                      <?= htmlspecialchars($it['slug']) ?>
                    </span>
                  </td>
                  <td class="p-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                      <?= (int)$it['sort_order'] ?>
                    </span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <a href="project-media.php?id=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-images mr-1"></i> Hình ảnh
                      </a>
                      <a href="?edit=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-1"></i> Sửa
                      </a>
                      <a href="?delete=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg" onclick="return confirm('Bạn có chắc muốn xóa dự án này?')">
                        <i class="fas fa-trash mr-1"></i> Xóa
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($items)): ?>
                <tr>
                  <td colspan="6" class="p-12 text-center">
                    <div class="flex flex-col items-center">
                      <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                      </div>
                      <p class="text-gray-500 text-lg font-medium">Chưa có dự án nào</p>
                      <p class="text-gray-400 text-sm mt-2">Hãy thêm dự án đầu tiên của bạn</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
