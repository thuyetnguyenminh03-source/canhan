<?php
require __DIR__ . '/bootstrap.php';
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $id = (int)($_POST['id'] ?? 0);
  $time_range = trim($_POST['time_range'] ?? '');
  $company = trim($_POST['company'] ?? '');
  $description_vi = trim($_POST['description_vi'] ?? '');
  $description_en = trim($_POST['description_en'] ?? '');
  $sort_order = (int)($_POST['sort_order'] ?? 0);

  if ($time_range && $company) {
    if ($id) {
      $stmt = $pdo->prepare('UPDATE timeline SET time_range=?, company=?, description_vi=?, description_en=?, sort_order=? WHERE id=?');
      $stmt->execute([$time_range, $company, $description_vi, $description_en, $sort_order, $id]);
      redirect_with_message('timeline.php', 'Đã cập nhật mục timeline.');
    } else {
      $stmt = $pdo->prepare('INSERT INTO timeline (time_range, company, description_vi, description_en, sort_order) VALUES (?,?,?,?,?)');
      $stmt->execute([$time_range, $company, $description_vi, $description_en, $sort_order]);
      redirect_with_message('timeline.php', 'Đã thêm mục timeline.');
    }
  } else {
    $error = 'Vui lòng nhập đầy đủ Thời gian và Công ty.';
  }
}

if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $pdo->prepare('DELETE FROM timeline WHERE id=?')->execute([$id]);
  redirect_with_message('timeline.php', 'Đã xóa mục timeline.');
}

$items = $pdo->query('SELECT * FROM timeline ORDER BY sort_order, id')->fetchAll();
$editing = null;
if (isset($_GET['edit'])) {
  $id = (int)$_GET['edit'];
  $stmt = $pdo->prepare('SELECT * FROM timeline WHERE id=?');
  $stmt->execute([$id]);
  $editing = $stmt->fetch();
}
$pageTitle = 'Quản lý Timeline';
require __DIR__ . '/_layout-header.php';
?>

      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-400 to-blue-400 bg-clip-text text-transparent mb-2">Quản lý Timeline</h1>
            <p class="text-gray-600">Thêm, sửa, xóa các mục trong timeline của bạn</p>
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

      <form method="post" class="glass-card p-8 mb-8">
        <h2 class="text-xl font-bold mb-6 flex items-center">
          <i class="fas fa-edit text-green-400 mr-3"></i>
          <?= $editing ? 'Chỉnh sửa mục timeline' : 'Thêm mục timeline mới' ?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?= csrf_field(); ?>
          <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Thời gian *</label>
            <input type="text" name="time_range" value="<?= htmlspecialchars($editing['time_range'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="2020-2023" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Công ty *</label>
            <input type="text" name="company" value="<?= htmlspecialchars($editing['company'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Công ty ABC" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả (VI)</label>
            <textarea name="description_vi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Mô tả bằng tiếng Việt"><?= htmlspecialchars($editing['description_vi'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả (EN)</label>
            <textarea name="description_en" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Description in English"><?= htmlspecialchars($editing['description_en'] ?? '') ?></textarea>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Thứ tự</label>
            <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="0">
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-sort-numeric-down mr-1"></i>
              Số nhỏ hơn hiển thị trước
            </p>
          </div>

          <div class="md:col-span-2 flex items-center space-x-3 pt-4">
            <button type="submit" class="btn-elegant inline-flex items-center">
              <i class="fas fa-<?= $editing ? 'save' : 'plus' ?> mr-2"></i>
              <?= $editing ? 'Cập nhật mục timeline' : 'Thêm mục timeline mới' ?>
            </button>
            <?php if ($editing): ?>
              <a href="timeline.php" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold inline-flex items-center">
                <i class="fas fa-times mr-2"></i> Hủy chỉnh sửa
              </a>
            <?php endif; ?>
          </div>
        </div>
      </form>

      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-list text-green-400 mr-3"></i>
            Danh sách timeline
          </h2>
          <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-xl font-semibold shadow-lg">
            <i class="fas fa-history mr-2"></i>
            Tổng: <?= count($items) ?>
          </span>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                <th class="text-left p-4 font-bold rounded-tl-xl">ID</th>
                <th class="text-left p-4 font-bold">Thời gian</th>
                <th class="text-left p-4 font-bold">Công ty</th>
                <th class="text-left p-4 font-bold">Thứ tự</th>
                <th class="text-left p-4 font-bold rounded-tr-xl">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $it): ?>
                <tr class="border-b border-gray-100 hover:bg-green-50/50 transition-colors">
                  <td class="p-4">
                    <span class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-500 text-white rounded-lg inline-flex items-center justify-center font-bold text-xs">
                      <?= $it['id'] ?>
                    </span>
                  </td>
                  <td class="p-4 font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                    <?= htmlspecialchars($it['time_range']) ?>
                  </td>
                  <td class="p-4 font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-building mr-2 text-blue-500"></i>
                    <?= htmlspecialchars($it['company']) ?>
                  </td>
                  <td class="p-4">
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                      <?= (int)$it['sort_order'] ?>
                    </span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <a href="?edit=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-1"></i> Sửa
                      </a>
                      <a href="?delete=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg" onclick="return confirm('Bạn có chắc muốn xóa mục timeline này?')">
                        <i class="fas fa-trash mr-1"></i> Xóa
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($items)): ?>
                <tr>
                  <td colspan="5" class="p-12 text-center">
                    <div class="flex flex-col items-center">
                      <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-history text-gray-400 text-3xl"></i>
                      </div>
                      <p class="text-gray-500 text-lg font-medium">Chưa có mục timeline nào</p>
                      <p class="text-gray-400 text-sm mt-2">Hãy thêm mục timeline đầu tiên của bạn</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

<?php require __DIR__ . '/_layout-footer.php'; ?>
