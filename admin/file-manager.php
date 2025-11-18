<?php
require __DIR__ . '/bootstrap.php';
require_login();

$error = '';
$success = '';

$base_dir = __DIR__ . '/../assets/img';
$current_dir = $_GET['dir'] ?? '';
$full_path = $base_dir . ($current_dir ? '/' . $current_dir : '');

if (!is_dir($full_path)) {
  $full_path = $base_dir;
  $current_dir = '';
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload_file'])) {
  verify_csrf_or_die();

  $file = $_FILES['upload_file'];
  if ($file['error'] === UPLOAD_ERR_OK) {
    $safe_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
    $target_path = $full_path . '/' . $safe_name;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
      $success = 'Đã upload file thành công.';
    } else {
      $error = 'Không thể upload file.';
    }
  } else {
    $error = 'Lỗi upload file (mã: ' . (int)$file['error'] . ').';
  }
}

// Handle file deletion
if (isset($_GET['delete'])) {
  $delete_file = basename($_GET['delete']);
  $delete_path = $full_path . '/' . $delete_file;

  if (is_file($delete_path) && unlink($delete_path)) {
    $success = 'Đã xóa file thành công.';
  } else {
    $error = 'Không thể xóa file.';
  }
}

// Get directory contents
$contents = [];
if (is_dir($full_path)) {
  $items = scandir($full_path);
  foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $item_path = $full_path . '/' . $item;
    $contents[] = [
      'name' => $item,
      'is_dir' => is_dir($item_path),
      'size' => is_file($item_path) ? filesize($item_path) : 0,
      'modified' => filemtime($item_path)
    ];
  }
}

$pageTitle = 'Quản lý File';
require __DIR__ . '/_layout-header.php';
?>

      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-400 to-teal-400 bg-clip-text text-transparent mb-2">Quản lý File</h1>
            <p class="text-gray-600">Upload và quản lý hình ảnh</p>
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

      <!-- Breadcrumb -->
      <div class="glass-card p-4 mb-6">
        <nav class="flex items-center space-x-2 text-sm">
          <a href="?dir=" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-home mr-1"></i>assets/img
          </a>
          <?php if ($current_dir): ?>
            <?php
            $parts = explode('/', $current_dir);
            $path = '';
            foreach ($parts as $part):
              $path .= ($path ? '/' : '') . $part;
            ?>
              <span class="text-gray-400">/</span>
              <a href="?dir=<?= urlencode($path) ?>" class="text-blue-600 hover:text-blue-800">
                <?= htmlspecialchars($part) ?>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </nav>
      </div>

      <!-- Upload Form -->
      <form method="post" enctype="multipart/form-data" class="glass-card p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4 flex items-center">
          <i class="fas fa-upload mr-2 text-green-600"></i> Upload File
        </h3>
        <div class="flex items-center space-x-4">
          <input type="file" name="upload_file" accept="image/*" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
          <button type="submit" class="bg-gradient-to-r from-green-500 to-teal-500 text-white px-6 py-2 rounded-lg hover:from-green-600 hover:to-teal-600 transition-all inline-flex items-center font-semibold shadow-md hover:shadow-lg">
            <i class="fas fa-upload mr-2"></i> Upload
          </button>
        </div>
        <?= csrf_field(); ?>
      </form>

      <!-- File List -->
      <div class="glass-card p-6">
        <h3 class="text-lg font-semibold mb-6 flex items-center">
          <i class="fas fa-folder-open mr-2 text-blue-600"></i> Nội dung thư mục
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          <?php foreach ($contents as $item): ?>
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                  <?php if ($item['is_dir']): ?>
                    <i class="fas fa-folder text-blue-500 text-xl mr-3"></i>
                    <a href="?dir=<?= urlencode(($current_dir ? $current_dir . '/' : '') . $item['name']) ?>" class="font-medium text-blue-600 hover:text-blue-800">
                      <?= htmlspecialchars($item['name']) ?>
                    </a>
                  <?php else: ?>
                    <i class="fas fa-file-image text-green-500 text-xl mr-3"></i>
                    <span class="font-medium text-gray-800">
                      <?= htmlspecialchars($item['name']) ?>
                    </span>
                  <?php endif; ?>
                </div>
                <?php if (!$item['is_dir']): ?>
                  <a href="?dir=<?= urlencode($current_dir) ?>&delete=<?= urlencode($item['name']) ?>"
                     onclick="return confirm('Bạn có chắc muốn xóa file này?')"
                     class="text-red-500 hover:text-red-700 p-1">
                    <i class="fas fa-trash text-sm"></i>
                  </a>
                <?php endif; ?>
              </div>

              <?php if (!$item['is_dir']): ?>
                <div class="mt-2">
                  <img src="../assets/img/<?= htmlspecialchars(($current_dir ? $current_dir . '/' : '') . $item['name']) ?>"
                       alt="<?= htmlspecialchars($item['name']) ?>"
                       class="w-full h-24 object-cover rounded border">
                </div>
                <div class="mt-2 text-xs text-gray-500">
                  <div>Kích thước: <?= number_format($item['size']) ?> bytes</div>
                  <div>Cập nhật: <?= date('d/m/Y H:i', $item['modified']) ?></div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>

          <?php if (empty($contents)): ?>
            <div class="col-span-full text-center py-12">
              <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4 mx-auto">
                <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
              </div>
              <p class="text-gray-500 text-lg font-medium">Thư mục trống</p>
              <p class="text-gray-400 text-sm mt-2">Upload file đầu tiên của bạn</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

<?php require __DIR__ . '/_layout-footer.php'; ?>
