<?php
require __DIR__ . '/bootstrap.php';
require_login();

ini_set('display_errors', '1');
error_reporting(E_ALL);

$error = '';
$editing = null; // tránh Notice

// Lấy danh sách testimonials
try {
  $items = $pdo->query('
    SELECT id, author_name, author_title, avatar_url, content_vi, content_en, sort_order
    FROM testimonials
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
    $author_name  = trim($_POST['author_name']  ?? '');
    $author_title = trim($_POST['author_title'] ?? '');
    $avatar_url   = trim($_POST['avatar_url']   ?? '');
    $content_vi   = trim($_POST['content_vi']   ?? '');
    $content_en   = trim($_POST['content_en']   ?? '');
    $sort_order   = (int)($_POST['sort_order']  ?? 0);

    if ($author_name === '' || $content_vi === '') {
      $error = 'Tên người đánh giá và Nội dung (VI) không được trống.';
    } else {
      if ($id > 0) {
        $stmt = $pdo->prepare('
          UPDATE testimonials
          SET author_name=?, author_title=?, avatar_url=?, content_vi=?, content_en=?, sort_order=?
          WHERE id=?
        ');
        $stmt->execute([$author_name, $author_title, $avatar_url, $content_vi, $content_en, $sort_order, $id]);
        redirect_with_message('testimonials.php', 'Đã cập nhật đánh giá.');
      } else {
        $stmt = $pdo->prepare('
          INSERT INTO testimonials (author_name, author_title, avatar_url, content_vi, content_en, sort_order)
          VALUES (?,?,?,?,?,?)
        ');
        $stmt->execute([$author_name, $author_title, $avatar_url, $content_vi, $content_en, $sort_order]);
        redirect_with_message('testimonials.php', 'Đã thêm đánh giá.');
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
    $pdo->prepare('DELETE FROM testimonials WHERE id=?')->execute([$id]);
    redirect_with_message('testimonials.php', 'Đã xóa đánh giá.');
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}

// Dữ liệu chỉnh sửa
if (isset($_GET['edit'])) {
  try {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('
      SELECT id, author_name, author_title, avatar_url, content_vi, content_en, sort_order
      FROM testimonials
      WHERE id=?
    ');
    $stmt->execute([$id]);
    $editing = $stmt->fetch() ?: [];
    if (!$editing) $error = 'Không tìm thấy đánh giá cần sửa.';
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}
$pageTitle = 'Quản lý Testimonials';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-400 to-blue-400 bg-clip-text text-transparent mb-2">Quản lý Testimonials</h1>
            <p class="text-gray-600">Thêm, sửa, xóa đánh giá của khách hàng</p>
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
          <i class="fas fa-edit text-green-400 mr-3"></i>
          <?= !empty($editing) ? 'Chỉnh sửa testimonial' : 'Thêm testimonial mới' ?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?= csrf_field(); ?>
        <input type="hidden" name="id" value="<?= $editing['id'] ?? '' ?>">

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên người đánh giá *</label>
            <input type="text" name="author_name" value="<?= htmlspecialchars($editing['author_name'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Nguyễn Văn A" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Chức danh / Vai trò</label>
            <input type="text" name="author_title" value="<?= htmlspecialchars($editing['author_title'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Giám đốc, Product Manager...">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Avatar URL</label>
            <input type="text" name="avatar_url" value="<?= htmlspecialchars($editing['avatar_url'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="https://... hoặc /assets/img/...">
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-info-circle mr-1"></i>
              Để trống nếu không dùng ảnh
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Thứ tự</label>
            <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="0">
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-sort-numeric-down mr-1"></i>
              Số nhỏ hơn hiển thị trước
            </p>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nội dung (VI) *</label>
            <textarea name="content_vi" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Nội dung đánh giá bằng tiếng Việt" required><?= htmlspecialchars($editing['content_vi'] ?? '') ?></textarea>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nội dung (EN)</label>
            <textarea name="content_en" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Testimonial content in English"><?= htmlspecialchars($editing['content_en'] ?? '') ?></textarea>
          </div>

          <div class="md:col-span-2 flex items-center space-x-3 pt-4">
            <button type="submit" class="btn-elegant inline-flex items-center">
              <i class="fas fa-<?= !empty($editing) ? 'save' : 'plus' ?> mr-2"></i>
              <?= !empty($editing) ? 'Cập nhật testimonial' : 'Thêm testimonial mới' ?>
            </button>
            <?php if (!empty($editing)): ?>
              <a href="testimonials.php" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold inline-flex items-center">
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
            <i class="fas fa-list text-green-400 mr-3"></i>
            Danh sách testimonials
          </h2>
          <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-xl font-semibold shadow-lg">
            <i class="fas fa-comments mr-2"></i>
            Tổng: <?= count($items) ?>
          </span>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                <th class="text-left p-4 font-bold rounded-tl-xl">ID</th>
                <th class="text-left p-4 font-bold">Tên</th>
                <th class="text-left p-4 font-bold">Chức danh</th>
                <th class="text-left p-4 font-bold">Nội dung</th>
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
                  <td class="p-4">
                    <div class="flex items-center space-x-3">
                      <?php if ($it['avatar_url']): ?>
                        <img src="<?= htmlspecialchars($it['avatar_url']) ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-green-200">
                      <?php else: ?>
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-400 rounded-full flex items-center justify-center text-white font-bold">
                          <?= strtoupper(substr($it['author_name'], 0, 1)) ?>
                        </div>
                      <?php endif; ?>
                      <span class="font-semibold text-gray-800"><?= htmlspecialchars($it['author_name']) ?></span>
                    </div>
                  </td>
                  <td class="p-4 text-gray-600"><?= htmlspecialchars($it['author_title']) ?></td>
                  <td class="p-4">
                    <p class="text-gray-600 italic text-xs line-clamp-2">"<?= htmlspecialchars(substr($it['content_vi'], 0, 80)) ?>..."</p>
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
                      <a href="?delete=<?= $it['id'] ?>" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg" onclick="return confirm('Bạn có chắc muốn xóa testimonial này?')">
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
                        <i class="fas fa-comments text-gray-400 text-3xl"></i>
                      </div>
                      <p class="text-gray-500 text-lg font-medium">Chưa có testimonial nào</p>
                      <p class="text-gray-400 text-sm mt-2">Hãy thêm đánh giá đầu tiên từ khách hàng</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

<?php require __DIR__ . '/_layout-footer.php'; ?>
