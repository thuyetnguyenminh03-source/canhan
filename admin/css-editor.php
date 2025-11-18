<?php
require __DIR__ . '/bootstrap.php';
require_login();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();

  $css_content = $_POST['css_content'] ?? '';

  if (file_put_contents(__DIR__ . '/styles.css', $css_content) !== false) {
    $success = 'Đã cập nhật CSS thành công.';
  } else {
    $error = 'Không thể lưu file CSS.';
  }
}

$current_css = file_get_contents(__DIR__ . '/styles.css');

$pageTitle = 'Chỉnh sửa CSS';
require __DIR__ . '/_layout-header.php';
?>

      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">Chỉnh sửa CSS</h1>
            <p class="text-gray-600">Tùy chỉnh giao diện admin panel</p>
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
      <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="post" class="glass-card p-8">
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung CSS</label>
          <textarea name="css_content" rows="30" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm" placeholder="Nhập CSS..."><?= htmlspecialchars($current_css) ?></textarea>
        </div>

        <div class="flex justify-end space-x-4">
          <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all inline-flex items-center font-semibold shadow-md hover:shadow-lg">
            <i class="fas fa-save mr-2"></i> Lưu thay đổi
          </button>
        </div>

        <?= csrf_field(); ?>
      </form>

<?php require __DIR__ . '/_layout-footer.php'; ?>
