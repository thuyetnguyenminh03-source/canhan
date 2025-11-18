<?php
require __DIR__ . '/bootstrap.php';
require_login();

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Fetch footer info (single record)
$stmt = $pdo->query('SELECT * FROM footer_info ORDER BY id DESC LIMIT 1');
$footer = $stmt->fetch() ?: [
  'id' => null,
  'copyright_vi' => '',
  'copyright_en' => '',
  'extra_html' => ''
];

$error_info = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_form'] ?? '') === 'info') {
  try {
    verify_csrf_or_die();
    $data = [
      'copyright_vi' => trim($_POST['copyright_vi'] ?? ''),
      'copyright_en' => trim($_POST['copyright_en'] ?? ''),
      'extra_html'   => trim($_POST['extra_html'] ?? ''),
    ];
    if (!empty($footer['id'])) {
      $sql = 'UPDATE footer_info SET copyright_vi=:copyright_vi, copyright_en=:copyright_en, extra_html=:extra_html WHERE id=:id';
      $stmt = $pdo->prepare($sql);
      $data['id'] = $footer['id'];
      $stmt->execute($data);
      redirect_with_message('footer.php', 'Đã cập nhật Footer info.');
    } else {
      $sql = 'INSERT INTO footer_info (copyright_vi, copyright_en, extra_html) VALUES (:copyright_vi, :copyright_en, :extra_html)';
      $stmt = $pdo->prepare($sql);
      $stmt->execute($data);
      redirect_with_message('footer.php', 'Đã tạo Footer info.');
    }
  } catch (Throwable $e) {
    $error_info = 'Lỗi: ' . $e->getMessage();
  }
}

// Footer links management
$error_links = '';
$editing = [];
try {
  // Create / Update links
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_form'] ?? '') === 'links') {
    verify_csrf_or_die();
    $id = (int)($_POST['id'] ?? 0);
    $name_vi = trim($_POST['name_vi'] ?? '');
    $name_en = trim($_POST['name_en'] ?? '');
    $url     = trim($_POST['url'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($name_vi === '' || $url === '') {
      $error_links = 'Tên (VI) và URL không được trống.';
    } else {
      if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE footer_links SET name_vi=?, name_en=?, url=?, sort_order=? WHERE id=?');
        $stmt->execute([$name_vi, $name_en, $url, $sort_order, $id]);
        redirect_with_message('footer.php', 'Đã cập nhật liên kết.');
      } else {
        $stmt = $pdo->prepare('INSERT INTO footer_links (name_vi, name_en, url, sort_order) VALUES (?,?,?,?)');
        $stmt->execute([$name_vi, $name_en, $url, $sort_order]);
        redirect_with_message('footer.php', 'Đã thêm liên kết.');
      }
    }
  }

  // Delete
  if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM footer_links WHERE id=?')->execute([$id]);
    redirect_with_message('footer.php', 'Đã xóa liên kết.');
  }

  // Edit
  if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('SELECT id, name_vi, name_en, url, sort_order FROM footer_links WHERE id=?');
    $stmt->execute([$id]);
    $editing = $stmt->fetch() ?: [];
    if (!$editing) {
      $error_links = 'Không tìm thấy liên kết cần sửa.';
    }
  }

  // List - fallback: if columns missing, select available columns with aliases
  // Attempt ideal select first
  try {
    $links = $pdo->query('SELECT id, name_vi, name_en, url, sort_order FROM footer_links ORDER BY sort_order, id')->fetchAll();
  } catch (Throwable $e) {
    // Fallback: select common existing columns and map them to expected keys
    $raw = $pdo->query('SELECT * FROM footer_links ORDER BY id')->fetchAll();
    $links = [];
    foreach ($raw as $r) {
      $links[] = [
        'id' => $r['id'] ?? null,
        'name_vi' => $r['name_vi'] ?? ($r['name_vn'] ?? ($r['name'] ?? '')),
        'name_en' => $r['name_en'] ?? '',
        'url' => $r['url'] ?? '',
        'sort_order' => isset($r['sort_order']) ? (int)$r['sort_order'] : 0
      ];
    }
  }
} catch (Throwable $e) {
  $error_links = 'Lỗi: ' . $e->getMessage();
}
$pageTitle = 'Quản lý Footer';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-rose-400 to-pink-400 bg-clip-text text-transparent mb-2">Quản lý Footer</h1>
            <p class="text-gray-600">Cập nhật thông tin bản quyền và liên kết footer</p>
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

      <!-- Footer Info Section -->
      <div class="glass-card p-8 mb-8">
        <h2 class="text-xl font-bold mb-6 flex items-center">
          <i class="fas fa-copyright text-rose-400 mr-3"></i>
          Thông tin bản quyền
        </h2>
        <?php if ($error_info): ?>
          <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= htmlspecialchars($error_info) ?></div>
        <?php endif; ?>
        <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?= csrf_field(); ?>
          <input type="hidden" name="_form" value="info">

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Copyright (VI)</label>
            <input type="text" name="copyright_vi" value="<?= htmlspecialchars($footer['copyright_vi']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" placeholder="© 2025 Tên công ty.">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Copyright (EN)</label>
            <input type="text" name="copyright_en" value="<?= htmlspecialchars($footer['copyright_en']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" placeholder="© 2025 Company name.">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Extra HTML (tùy chọn)</label>
            <textarea name="extra_html" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" placeholder="HTML bổ sung cho footer"><?= htmlspecialchars($footer['extra_html']) ?></textarea>
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-info-circle mr-1"></i>
              Bạn có thể chèn HTML đơn giản (text/link). Tránh script.
            </p>
          </div>

          <div class="md:col-span-2 flex items-center space-x-3 pt-4">
            <button type="submit" class="btn-elegant inline-flex items-center">
              <i class="fas fa-save mr-2"></i>
              Lưu thông tin bản quyền
            </button>
          </div>
        </form>
      </div>

      <!-- Footer Links Section -->
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-link text-pink-400 mr-3"></i>
            Liên kết nhanh
          </h2>
          <span class="px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 text-white rounded-xl font-semibold shadow-lg">
            <i class="fas fa-external-link-alt mr-2"></i>
            Tổng: <?= isset($links) ? count($links) : 0 ?>
          </span>
        </div>

        <?php if ($error_links): ?>
          <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= htmlspecialchars($error_links) ?></div>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <form method="post" class="glass-card p-6 mb-6 border border-gray-200">
          <h3 class="text-lg font-semibold mb-4 flex items-center">
            <i class="fas fa-plus-circle text-rose-400 mr-2"></i>
            <?= !empty($editing) ? 'Chỉnh sửa liên kết' : 'Thêm liên kết mới' ?>
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?= csrf_field(); ?>
            <input type="hidden" name="_form" value="links">
            <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Tên (VI) *</label>
              <input type="text" name="name_vi" value="<?= htmlspecialchars($editing['name_vi'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" placeholder="Về chúng tôi" required>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Tên (EN)</label>
              <input type="text" name="name_en" value="<?= htmlspecialchars($editing['name_en'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" placeholder="About us">
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2">URL *</label>
              <input type="url" name="url" value="<?= htmlspecialchars($editing['url'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" placeholder="/about, https://... " required>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Thứ tự</label>
              <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" placeholder="0">
              <p class="text-xs text-gray-500 mt-2 flex items-center">
                <i class="fas fa-sort-numeric-down mr-1"></i>
                Số nhỏ hơn hiển thị trước
              </p>
            </div>

            <div class="md:col-span-2 flex items-center space-x-3 pt-4">
              <button type="submit" class="btn-elegant inline-flex items-center">
                <i class="fas fa-<?= !empty($editing) ? 'save' : 'plus' ?> mr-2"></i>
                <?= !empty($editing) ? 'Cập nhật liên kết' : 'Thêm liên kết mới' ?>
              </button>
              <?php if (!empty($editing)): ?>
                <a href="footer.php" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold inline-flex items-center">
                  <i class="fas fa-times mr-2"></i> Hủy chỉnh sửa
                </a>
              <?php endif; ?>
            </div>
          </div>
        </form>

        <!-- Links Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                <th class="text-left p-4 font-bold rounded-tl-xl">ID</th>
                <th class="text-left p-4 font-bold">Tên (VI)</th>
                <th class="text-left p-4 font-bold">Tên (EN)</th>
                <th class="text-left p-4 font-bold">URL</th>
                <th class="text-left p-4 font-bold">Thứ tự</th>
                <th class="text-left p-4 font-bold rounded-tr-xl">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($links)): foreach ($links as $it): ?>
                <tr class="border-b border-gray-100 hover:bg-rose-50/50 transition-colors">
                  <td class="p-4">
                    <span class="w-8 h-8 bg-gradient-to-br from-rose-500 to-pink-500 text-white rounded-lg inline-flex items-center justify-center font-bold text-xs">
                      <?= htmlspecialchars($it['id']) ?>
                    </span>
                  </td>
                  <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($it['name_vi']) ?></td>
                  <td class="p-4 text-gray-600"><?= htmlspecialchars($it['name_en']) ?></td>
                  <td class="p-4">
                    <a href="<?= htmlspecialchars($it['url']) ?>" target="_blank" class="text-blue-600 hover:text-blue-700 underline flex items-center">
                      <i class="fas fa-external-link-alt mr-1"></i>
                      <?= htmlspecialchars($it['url']) ?>
                    </a>
                  </td>
                  <td class="p-4">
                    <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold">
                      <?= (int)($it['sort_order'] ?? 0) ?>
                    </span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <a href="?edit=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-1"></i> Sửa
                      </a>
                      <a href="?delete=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg" onclick="return confirm('Bạn có chắc muốn xóa liên kết này?')">
                        <i class="fas fa-trash mr-1"></i> Xóa
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="6" class="p-12 text-center">
                    <div class="flex flex-col items-center">
                      <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-link text-gray-400 text-3xl"></i>
                      </div>
                      <p class="text-gray-500 text-lg font-medium">Chưa có liên kết nào</p>
                      <p class="text-gray-400 text-sm mt-2">Hãy thêm liên kết footer đầu tiên của bạn</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

<?php require __DIR__ . '/_layout-footer.php'; ?>
