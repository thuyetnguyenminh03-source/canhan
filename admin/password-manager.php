<?php
// Trang quản lý mật khẩu trong admin dashboard
require_once __DIR__ . '/bootstrap.php';

require_login();

$error = '';
$success = '';

// Xử lý các hành động
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verify_csrf_or_die();
        
        $action = $_POST['action'] ?? '';
        
        if ($action === 'generate_new_password') {
            // Tạo mật khẩu mới ngẫu nhiên
            function generateStrongPassword($length = 16) {
                $lowercase = 'abcdefghijklmnopqrstuvwxyz';
                $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $numbers = '0123456789';
                $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';
                
                $all = $lowercase . $uppercase . $numbers . $special;
                $password = '';
                
                // Đảm bảo có ít nhất 1 ký tự từ mỗi loại
                $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
                $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
                $password .= $numbers[random_int(0, strlen($numbers) - 1)];
                $password .= $special[random_int(0, strlen($special) - 1)];
                
                // Thêm ngẫu nhiên cho đủ độ dài
                for ($i = 4; $i < $length; $i++) {
                    $password .= $all[random_int(0, strlen($all) - 1)];
                }
                
                return str_shuffle($password);
            }
            
            $new_password = generateStrongPassword();
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Cập nhật mật khẩu
            $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = 1");
            $stmt->execute([$hashed_password]);
            
            // Ghi log
            $logMessage = sprintf(
                "[%s] Admin password generated via dashboard by user: %s\n",
                date('Y-m-d H:i:s'),
                current_admin()
            );
            file_put_contents(__DIR__ . '/admin_password_changes.log', $logMessage, FILE_APPEND | LOCK_EX);
            
            $success = "Mật khẩu mới đã được tạo: <strong>" . htmlspecialchars($new_password) . "</strong><br>";
            $success .= "Vui lòng ghi nhớ mật khẩu này!";
            
        } elseif ($action === 'create_reset_link') {
            // Tạo link reset mật khẩu
            $reset_token = bin2hex(random_bytes(32));
            $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("UPDATE admin_users SET reset_token = ?, reset_expires = ? WHERE id = 1");
            $stmt->execute([$reset_token, $reset_expires]);
            
            $reset_link = "http://localhost:8000/admin/forgot-password.php?token=" . $reset_token;
            
            // Lưu link vào file để dễ lấy
            file_put_contents(__DIR__ . '/reset_password_link.txt', $reset_link);
            
            $success = "Link reset mật khẩu đã được tạo:<br>";
            $success .= "<div class='bg-gray-100 p-3 rounded mt-2 text-sm break-all'>" . htmlspecialchars($reset_link) . "</div>";
            $success .= "<small class='text-muted'>Link có hiệu lực trong 1 giờ</small>";
        }
    } catch (Exception $e) {
        $error = 'Lỗi: ' . $e->getMessage();
    }
}

// Lấy thông tin admin
$stmt = $pdo->query("SELECT id, username, created_at, reset_expires FROM admin_users WHERE id = 1");
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra xem có link reset nào đang hoạt động không
$has_active_reset = false;
if ($admin['reset_expires'] && strtotime($admin['reset_expires']) > time()) {
    $has_active_reset = true;
}

$pageTitle = 'Quản lý mật khẩu';
require __DIR__ . '/_layout-header.php';
?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-shield-alt text-primary"></i>
                Quản lý mật khẩu Admin
            </h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Thông tin admin -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-shield"></i>
                        Thông tin tài khoản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tên đăng nhập:</strong> <?= htmlspecialchars($admin['username']) ?></p>
                            <p><strong>ID:</strong> <?= $admin['id'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày tạo:</strong> <?= $admin['created_at'] ?></p>
                            <?php if ($has_active_reset): ?>
                                <p><strong>Link reset:</strong> <span class="badge bg-warning">Đang hoạt động</span></p>
                                <p><strong>Hết hạn:</strong> <?= $admin['reset_expires'] ?></p>
                            <?php else: ?>
                                <p><strong>Link reset:</strong> <span class="badge bg-secondary">Không có</span></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Các hành động -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-key"></i>
                                Đổi mật khẩu thủ công
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Đổi mật khẩu theo ý muốn với giao diện an toàn.</p>
                            <a href="change-admin-password.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                                Đổi mật khẩu
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-magic"></i>
                                Tạo mật khẩu ngẫu nhiên
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Tạo mật khẩu mạnh ngẫu nhiên 16 ký tự.</p>
                            <form method="post" onsubmit="return confirm('Bạn có chắc muốn tạo mật khẩu mới ngẫu nhiên?');">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="generate_new_password">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-random"></i>
                                    Tạo mật khẩu mới
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-link"></i>
                                Tạo link reset
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Tạo link để reset mật khẩu nếu quên.</p>
                            <form method="post" onsubmit="return confirm('Tạo link reset mật khẩu mới? Link cũ sẽ bị hủy.');">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="create_reset_link">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key"></i>
                                    Tạo link reset
                                </button>
                            </form>
                            <?php if ($has_active_reset): ?>
                                <div class="mt-2">
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Đã có link reset đang hoạt động
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-history"></i>
                                Lịch sử thay đổi
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Xem log các lần thay đổi mật khẩu.</p>
                            <button onclick="viewPasswordHistory()" class="btn btn-info">
                                <i class="fas fa-list"></i>
                                Xem lịch sử
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Log mật khẩu -->
            <div class="card mt-4" id="passwordHistory" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt"></i>
                        Lịch sử thay đổi mật khẩu
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $log_file = __DIR__ . '/admin_password_changes.log';
                    if (file_exists($log_file) && filesize($log_file) > 0):
                        $logs = file_get_contents($log_file);
                        $log_lines = array_filter(array_reverse(explode("\n", $logs)));
                        $recent_logs = array_slice($log_lines, 0, 10); // Lấy 10 dòng gần nhất
                        ?>
                        <div class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">
                            <?php foreach ($recent_logs as $log): ?>
                                <?php if (trim($log)): ?>
                                    <div class="mb-2 font-monospace small">
                                        <?= htmlspecialchars($log) ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Chỉ hiển thị 10 lần thay đổi gần nhất
                            </small>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Chưa có lịch sử thay đổi mật khẩu.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewPasswordHistory() {
    const historyDiv = document.getElementById('passwordHistory');
    if (historyDiv.style.display === 'none') {
        historyDiv.style.display = 'block';
        historyDiv.scrollIntoView({ behavior: 'smooth' });
    } else {
        historyDiv.style.display = 'none';
    }
}
</script>

<?php require __DIR__ . '/_layout-footer.php'; ?>