<?php
require __DIR__ . '/bootstrap.php';
require_login();

$projectId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?'); 
$stmt->execute([$projectId]); 
$project = $stmt->fetch();
if (!$project) redirect_with_message('projects.php','Không tìm thấy dự án','error');

$error = '';
$uploadDir = __DIR__ . '/../uploads/projects';
$uploadBaseUrl = '../uploads/projects';

if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0755, true); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();

  if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    // Handle edit
    $editId = (int)($_POST['edit_id'] ?? 0);
    $editTitle = trim($_POST['edit_title'] ?? '');
    $editSection = $_POST['edit_section'] ?? 'gallery';
    $editSortOrder = (int)($_POST['edit_sort_order'] ?? 0);

    if ($editId > 0) {
      $stmt = $pdo->prepare('UPDATE project_media SET title=?, section=?, sort_order=? WHERE id=? AND project_id=?');
      $stmt->execute([$editTitle, $editSection, $editSortOrder, $editId, $projectId]);
      redirect_with_message("project-media.php?id={$projectId}", 'Đã cập nhật ảnh.');
    } else {
      $error = 'ID ảnh không hợp lệ.';
    }
  } else {
    // Handle add new image
    $section = $_POST['section'] ?? 'gallery';
    $title   = trim($_POST['title'] ?? '');
    $order   = (int)($_POST['sort_order'] ?? 0);
    $finalUrl = trim($_POST['url'] ?? '');

    if (!empty($_FILES['image']['name'])) {
      $file = $_FILES['image'];
      if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext,$allowed,true)) $error='Định dạng ảnh không hợp lệ.';
        else {
          $safeName = bin2hex(random_bytes(8)).'.'.$ext;
          $targetPath = $uploadDir.'/'.$safeName;
          if (move_uploaded_file($file['tmp_name'],$targetPath)) $finalUrl = $uploadBaseUrl.'/'.$safeName;
          else $error='Không thể lưu file upload.';
        }
      } else $error='Upload lỗi (mã: '.(int)$file['error'].').';
    }

    if (!$error) {
      if ($finalUrl) {
        $pdo->prepare('INSERT INTO project_media (project_id, section, url, title, sort_order) VALUES (?,?,?,?,?)')
            ->execute([$projectId, $section, $finalUrl, $title, $order]);
        redirect_with_message("project-media.php?id={$projectId}", 'Đã thêm ảnh.');
      } else $error='Vui lòng chọn file ảnh hoặc nhập URL ảnh.';
    }
  }
}

if (isset($_GET['delete'])) {
  $mediaId = (int)$_GET['delete'];
  $stmt = $pdo->prepare('SELECT url FROM project_media WHERE id=? AND project_id=?'); 
  $stmt->execute([$mediaId,$projectId]); 
  $row=$stmt->fetch();
  if ($row) {
    $pdo->prepare('DELETE FROM project_media WHERE id=? AND project_id=?')->execute([$mediaId,$projectId]);
    if (strpos($row['url'],$uploadBaseUrl)!==false) {
      $fileName = basename($row['url']); 
      $phys = $uploadDir.'/'.$fileName; 
      if (is_file($phys)) @unlink($phys);
    }
    redirect_with_message("project-media.php?id={$projectId}", 'Đã xóa ảnh.');
  } else redirect_with_message("project-media.php?id={$projectId}", 'Không tìm thấy ảnh.','error');
}

$media = $pdo->prepare('SELECT * FROM project_media WHERE project_id = ? ORDER BY section, sort_order, id'); 
$media->execute([$projectId]); 
$mediaList = $media->fetchAll();

$pageTitle = 'Quản lý Hình ảnh Dự án';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">
              Hình ảnh: <?= htmlspecialchars($project['title_vi']) ?>
            </h1>
            <p class="text-gray-600">Upload và quản lý hình ảnh cho dự án này</p>
          </div>
          <div class="flex items-center space-x-3">
            <button onclick="toggleTheme()" class="theme-toggle p-3">
              <i class="fas fa-moon text-gray-600"></i>
            </button>
            <a href="projects.php" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold inline-flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
          </div>
        </div>
      </div>

      <?= flash_message(); ?>
      <?php if ($error): ?>
        <div class="glass-card p-4 mb-6 border-l-4 border-red-500 bg-red-50/50">
          <p class="text-red-700 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= htmlspecialchars($error) ?>
          </p>
        </div>
      <?php endif; ?>

      <!-- Upload Form -->
      <form method="post" enctype="multipart/form-data" class="glass-card p-8 mb-8">
        <h2 class="text-xl font-bold mb-6 flex items-center">
          <i class="fas fa-cloud-upload-alt text-blue-400 mr-3"></i>
          Thêm hình ảnh mới
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?= csrf_field(); ?>
          
          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-image mr-2"></i>Chọn file ảnh
            </label>
            <div class="relative">
              <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp" 
                     class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer hover:border-blue-400"
                     id="imageInput">
              <div class="mt-2 text-xs text-gray-500 flex items-center">
                <i class="fas fa-info-circle mr-1"></i>
                Định dạng: JPG, JPEG, PNG, GIF, WEBP (Max: 5MB)
              </div>
            </div>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-link mr-2"></i>Hoặc nhập URL ảnh
            </label>
            <input type="text" name="url" placeholder="https://example.com/image.jpg" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-tag mr-2"></i>Tiêu đề ảnh
            </label>
            <input type="text" name="title" placeholder="Mô tả ngắn về ảnh" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-folder mr-2"></i>Phân loại
            </label>
            <select name="section" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
              <option value="cover">🏠 Ảnh đại diện (Cover)</option>
              <option value="gallery">📸 Hình ảnh triển khai</option>
              <option value="policy">📋 Chính sách</option>
              <option value="floor">🏗️ Vẽ căn</option>
              <option value="recruitment">👥 Tuyển dụng</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-sort-numeric-down mr-2"></i>Thứ tự hiển thị
            </label>
            <input type="number" name="sort_order" value="0" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>

          <div class="md:col-span-2 flex items-center space-x-3 pt-4">
            <button type="submit" class="btn-elegant inline-flex items-center">
              <i class="fas fa-plus mr-2"></i>
              Thêm hình ảnh
            </button>
          </div>
        </div>
      </form>

      <!-- Media Gallery -->
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-images text-purple-400 mr-3"></i>
            Thư viện hình ảnh
          </h2>
          <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl font-semibold shadow-lg">
            <i class="fas fa-image mr-2"></i>
            Tổng: <?= count($mediaList) ?> ảnh
          </span>
        </div>

        <?php if (empty($mediaList)): ?>
          <div class="text-center py-16">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-images text-gray-400 text-3xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-700 mb-2">Chưa có hình ảnh nào</h4>
            <p class="text-gray-500 mb-6">Hãy upload hình ảnh đầu tiên cho dự án này</p>
          </div>
        <?php else: ?>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php 
            $sectionColors = [
              'cover' => 'from-red-500 to-red-600',
              'gallery' => 'from-blue-500 to-blue-600',
              'policy' => 'from-green-500 to-green-600',
              'floor' => 'from-orange-500 to-orange-600',
              'recruitment' => 'from-purple-500 to-purple-600'
            ];
            $sectionIcons = [
              'cover' => 'fa-home',
              'gallery' => 'fa-images',
              'policy' => 'fa-file-alt',
              'floor' => 'fa-building',
              'recruitment' => 'fa-users'
            ];
            foreach ($mediaList as $item): 
              $colorClass = $sectionColors[$item['section']] ?? 'from-gray-500 to-gray-600';
              $iconClass = $sectionIcons[$item['section']] ?? 'fa-image';
            ?>
              <div class="group relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- Image -->
                <div class="relative aspect-video bg-gray-100 overflow-hidden">
                  <img src="<?= htmlspecialchars($item['url']) ?>" 
                       alt="<?= htmlspecialchars($item['title']) ?>" 
                       class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                       loading="lazy">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  
                  <!-- Quick Actions Overlay -->
                  <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank"
                       class="mx-2 w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-lg">
                      <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="openEditModal(<?= $item['id'] ?>, '<?= htmlspecialchars($item['title']) ?>', '<?= $item['section'] ?>', <?= $item['sort_order'] ?>)"
                       class="mx-2 w-10 h-10 bg-white rounded-full flex items-center justify-center text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-lg">
                      <i class="fas fa-edit"></i>
                    </button>
                    <a href="?id=<?= $projectId ?>&delete=<?= $item['id'] ?>"
                       onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')"
                       class="mx-2 w-10 h-10 bg-white rounded-full flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-lg">
                      <i class="fas fa-trash"></i>
                    </a>
                  </div>
                </div>

                <!-- Info -->
                <div class="p-4">
                  <div class="flex items-center justify-between mb-2">
                    <span class="px-3 py-1 bg-gradient-to-r <?= $colorClass ?> text-white rounded-full text-xs font-semibold inline-flex items-center">
                      <i class="fas <?= $iconClass ?> mr-1"></i>
                      <?= htmlspecialchars($item['section']) ?>
                    </span>
                    <span class="text-xs text-gray-500 font-mono">#<?= $item['id'] ?></span>
                  </div>
                  
                  <?php if ($item['title']): ?>
                    <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2">
                      <?= htmlspecialchars($item['title']) ?>
                    </h3>
                  <?php endif; ?>
                  
                  <div class="flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center">
                      <i class="fas fa-sort-numeric-down mr-1"></i>
                      Thứ tự: <?= (int)$item['sort_order'] ?>
                    </span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Edit Modal -->
      <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Chỉnh sửa ảnh</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>

          <form id="editForm" method="post" class="space-y-4">
            <?= csrf_field(); ?>
            <input type="hidden" name="edit_id" id="editId">

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề ảnh</label>
              <input type="text" name="edit_title" id="editTitle" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Phân loại</label>
              <select name="edit_section" id="editSection" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                <option value="cover">🏠 Ảnh đại diện (Cover)</option>
                <option value="gallery">📸 Hình ảnh triển khai</option>
                <option value="policy">📋 Chính sách</option>
                <option value="floor">🏗️ Vẽ căn</option>
                <option value="recruitment">👥 Tuyển dụng</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Thứ tự hiển thị</label>
              <input type="number" name="edit_sort_order" id="editSortOrder" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
            </div>

            <div class="flex items-center space-x-3 pt-4">
              <button type="submit" name="action" value="edit" class="btn-elegant inline-flex items-center">
                <i class="fas fa-save mr-2"></i>
                Cập nhật
              </button>
              <button type="button" onclick="closeEditModal()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-semibold">
                Hủy
              </button>
            </div>
          </form>
        </div>
      </div>

      <script>
        // Preview image before upload
        document.getElementById('imageInput')?.addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
          }
        });

        // Edit modal functions
        function openEditModal(id, title, section, sortOrder) {
          document.getElementById('editId').value = id;
          document.getElementById('editTitle').value = title;
          document.getElementById('editSection').value = section;
          document.getElementById('editSortOrder').value = sortOrder;
          document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
          document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
          if (e.target === this) {
            closeEditModal();
          }
        });
      </script>

<?php require __DIR__ . '/_layout-footer.php'; ?>
