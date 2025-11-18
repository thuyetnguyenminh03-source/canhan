<?php
require __DIR__ . '/bootstrap.php';
require_login();

ini_set('display_errors', '1');
error_reporting(E_ALL);

$error = '';
$editing = [];

// Lấy danh sách tin nhắn liên hệ
try {
  $items = $pdo->query('SELECT * FROM contact ORDER BY created_at DESC')->fetchAll();
} catch (Throwable $e) {
  $error = 'Lỗi: ' . $e->getMessage();
}

// Xóa tin nhắn
if (isset($_GET['delete'])) {
  try {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM contact WHERE id=?')->execute([$id]);
    redirect_with_message('contact-messages.php', 'Đã xóa tin nhắn.');
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}

// Đánh dấu đã đọc/chưa đọc
if (isset($_GET['toggle_read'])) {
  try {
    $id = (int)$_GET['toggle_read'];
    $stmt = $pdo->prepare('SELECT is_read FROM contact WHERE id=?');
    $stmt->execute([$id]);
    $current = $stmt->fetchColumn();
    $new_status = $current ? 0 : 1;
    $pdo->prepare('UPDATE contact SET is_read=? WHERE id=?')->execute([$new_status, $id]);
    redirect_with_message('contact-messages.php', $new_status ? 'Đã đánh dấu đã đọc.' : 'Đã đánh dấu chưa đọc.');
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}

// Thống kê
try {
  $stats = [
    'total' => $pdo->query('SELECT COUNT(*) FROM contact')->fetchColumn(),
    'unread' => $pdo->query('SELECT COUNT(*) FROM contact WHERE is_read=0')->fetchColumn(),
    'today' => $pdo->query('SELECT COUNT(*) FROM contact WHERE DATE(created_at) = CURDATE()')->fetchColumn()
  ];
} catch (Throwable $e) {
  $stats = ['total' => 0, 'unread' => 0, 'today' => 0];
}

$pageTitle = 'Quản lý Tin nhắn Liên hệ';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent mb-2">Quản lý Tin nhắn Liên hệ</h1>
            <p class="text-gray-600">Xem và quản lý tin nhắn từ form liên hệ</p>
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

      <!-- Thống kê -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Tổng tin nhắn</p>
              <p class="text-3xl font-bold text-blue-600"><?= $stats['total'] ?></p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-envelope text-white text-xl"></i>
            </div>
          </div>
        </div>
        <div class="glass-card p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Chưa đọc</p>
              <p class="text-3xl font-bold text-orange-600"><?= $stats['unread'] ?></p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-envelope-open text-white text-xl"></i>
            </div>
          </div>
        </div>
        <div class="glass-card p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-600">Hôm nay</p>
              <p class="text-3xl font-bold text-green-600"><?= $stats['today'] ?></p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-calendar-day text-white text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Danh sách tin nhắn -->
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-list text-blue-400 mr-3"></i>
            Danh sách tin nhắn
          </h2>
          <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-semibold shadow-lg">
            <i class="fas fa-envelope mr-2"></i>
            Tổng: <?= count($items) ?>
          </span>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700">
                <th class="text-left p-4 font-bold rounded-tl-xl">Trạng thái</th>
                <th class="text-left p-4 font-bold">Người gửi</th>
                <th class="text-left p-4 font-bold">Email</th>
                <th class="text-left p-4 font-bold">Nội dung</th>
                <th class="text-left p-4 font-bold">Thời gian</th>
                <th class="text-left p-4 font-bold rounded-tr-xl">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($items)): foreach ($items as $it): ?>
                <tr class="border-b border-gray-100 hover:bg-blue-50/50 transition-colors <?= !$it['is_read'] ? 'bg-blue-50/30' : '' ?>">
                  <td class="p-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center
                      <?= $it['is_read'] ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' ?>">
                      <i class="fas fa-<?= $it['is_read'] ? 'check-circle' : 'circle' ?> mr-1"></i>
                      <?= $it['is_read'] ? 'Đã đọc' : 'Chưa đọc' ?>
                    </span>
                  </td>
                  <td class="p-4">
                    <div class="font-semibold text-gray-800">
                      <?= htmlspecialchars($it['name']) ?>
                    </div>
                  </td>
                  <td class="p-4">
                    <a href="mailto:<?= htmlspecialchars($it['email']) ?>" class="text-blue-600 hover:text-blue-700 underline">
                      <i class="fas fa-envelope mr-1"></i>
                      <?= htmlspecialchars($it['email']) ?>
                    </a>
                  </td>
                  <td class="p-4">
                    <div class="text-gray-600 text-sm line-clamp-2 max-w-xs">
                      <?= htmlspecialchars(substr($it['message'], 0, 100)) ?>...
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="text-xs text-gray-500">
                      <i class="fas fa-clock mr-1"></i>
                      <?= date('d/m/Y H:i', strtotime($it['created_at'])) ?>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <a href="?toggle_read=<?= $it['id'] ?>" class="px-3 py-2 bg-gradient-to-r from-<?= $it['is_read'] ? 'orange' : 'green' ?>-500 to-<?= $it['is_read'] ? 'orange' : 'green' ?>-600 text-white rounded-lg hover:from-<?= $it['is_read'] ? 'orange' : 'green' ?>-600 hover:to-<?= $it['is_read'] ? 'orange' : 'green' ?>-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-<?= $it['is_read'] ? 'undo' : 'check' ?> mr-1"></i>
                        <?= $it['is_read'] ? 'Chưa đọc' : 'Đã đọc' ?>
                      </a>
                      <button onclick="showMessageDetail(<?= $it['id'] ?>, '<?= htmlspecialchars(addslashes($it['name'])) ?>', '<?= htmlspecialchars(addslashes($it['email'])) ?>', '<?= htmlspecialchars(addslashes($it['message'])) ?>', '<?= $it['created_at'] ?>')" class="px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg">
                        <i class="fas fa-eye mr-1"></i> Xem
                      </button>
                      <a href="?delete=<?= $it['id'] ?>" class="px-3 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all inline-flex items-center text-xs font-semibold shadow-md hover:shadow-lg" onclick="return confirm('Bạn có chắc muốn xóa tin nhắn này?')">
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
                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                      </div>
                      <p class="text-gray-500 text-lg font-medium">Chưa có tin nhắn nào</p>
                      <p class="text-gray-400 text-sm mt-2">Tin nhắn từ form liên hệ sẽ hiển thị ở đây</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal chi tiết tin nhắn -->
      <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
          <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">Chi tiết tin nhắn</h3>
                <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                  <i class="fas fa-times text-xl"></i>
                </button>
              </div>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Người gửi</label>
                  <p class="text-gray-800 font-medium" id="modalName"></p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                  <p class="text-gray-800">
                    <a href="#" id="modalEmailLink" class="text-blue-600 hover:text-blue-700 underline"></a>
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian</label>
                  <p class="text-gray-600 text-sm" id="modalTime"></p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Nội dung</label>
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-800 whitespace-pre-wrap" id="modalMessage"></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="p-6 border-t bg-gray-50 rounded-b-2xl">
              <div class="flex justify-end space-x-3">
                <button onclick="closeMessageModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold">
                  Đóng
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script>
        function showMessageDetail(id, name, email, message, createdAt) {
          document.getElementById('modalName').textContent = name;
          document.getElementById('modalEmailLink').textContent = email;
          document.getElementById('modalEmailLink').href = 'mailto:' + email;
          document.getElementById('modalMessage').textContent = message;
          document.getElementById('modalTime').textContent = new Date(createdAt).toLocaleString('vi-VN');
          document.getElementById('messageModal').classList.remove('hidden');
        }

        function closeMessageModal() {
          document.getElementById('messageModal').classList.add('hidden');
        }

        // Đóng modal khi click bên ngoài
        document.getElementById('messageModal').addEventListener('click', function(e) {
          if (e.target === this) {
            closeMessageModal();
          }
        });
      </script>

<?php require __DIR__ . '/_layout-footer.php'; ?>
