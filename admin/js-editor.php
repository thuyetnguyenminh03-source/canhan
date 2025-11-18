<?php
require __DIR__ . '/bootstrap.php';
require_login();

$error = '';
$success = '';

$js_files = [
  'main' => '../assets/js/main.js',
  'load-projects' => '../assets/js/load-projects.js'
];

$current_file = $_GET['file'] ?? 'main';
$current_path = $js_files[$current_file] ?? $js_files['main'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();

  $js_content = $_POST['js_content'] ?? '';
  $selected_file = $_POST['selected_file'] ?? 'main';

  $target_path = $js_files[$selected_file] ?? $js_files['main'];

  if (file_put_contents($target_path, $js_content) !== false) {
    $success = 'Đã cập nhật JavaScript thành công.';
    $current_file = $selected_file;
    $current_path = $target_path;
  } else {
    $error = 'Không thể lưu file JavaScript.';
  }
}

$current_js = file_get_contents($current_path);

$pageTitle = 'Chỉnh sửa JavaScript';
require __DIR__ . '/_layout-header.php';
?>

      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent mb-2">Chỉnh sửa JavaScript</h1>
            <p class="text-gray-600">Tùy chỉnh chức năng website</p>
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

      <form method="post" class="glass-card p-8 mb-6">
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file JavaScript</label>
          <select name="selected_file" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <?php foreach ($js_files as $key => $path): ?>
              <option value="<?= $key ?>" <?= $key === $current_file ? 'selected' : '' ?>>
                <?= basename($path) ?> (<?= $key ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>

      <form method="post" class="glass-card p-8">
        <input type="hidden" name="selected_file" value="<?= htmlspecialchars($current_file) ?>">

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Nội dung JavaScript - <code class="bg-gray-100 px-2 py-1 rounded text-xs"><?= htmlspecialchars(basename($current_path)) ?></code>
          </label>
          <textarea name="js_content" rows="30" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm" placeholder="Nhập JavaScript..."><?= htmlspecialchars($current_js) ?></textarea>
        </div>

        <div class="flex justify-end space-x-4">
          <button type="submit" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-2 rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all inline-flex items-center font-semibold shadow-md hover:shadow-lg">
            <i class="fas fa-save mr-2"></i> Lưu thay đổi
          </button>
        </div>

        <?= csrf_field(); ?>
      </form>

<?php require __DIR__ . '/_layout-footer.php'; ?>
