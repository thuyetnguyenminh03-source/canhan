<?php
// Trang quên mật khẩu admin - cho phép reset mật khẩu khi quên
require_once __DIR__ . '/bootstrap.php';

$error = '';
$success = '';

// Kiểm tra nếu có tham số reset token trong URL
$reset_token = $_GET['token'] ?? '';

if ($reset_token) {
    // Xử lý reset mật khẩu từ token
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            verify_csrf_or_die();
            
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate password
            if (strlen($new_password) < 8) {
                $error = 'Mật khẩu phải có ít nhất 8 ký tự.';
            } elseif (!preg_match('/[A-Z]/', $new_password)) {
                $error = 'Mật khẩu phải có ít nhất 1 chữ hoa.';
            } elseif (!preg_match('/[a-z]/', $new_password)) {
                $error = 'Mật khẩu phải có ít nhất 1 chữ thường.';
            } elseif (!preg_match('/[0-9]/', $new_password)) {
                $error = 'Mật khẩu phải có ít nhất 1 số.';
            } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
                $error = 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt.';
            } elseif ($new_password !== $confirm_password) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } else {
                // Kiểm tra token có hợp lệ không - sử dụng PHP time để tránh lỗi múi giờ
                $current_time = date('Y-m-d H:i:s');
                $stmt = $pdo->prepare("SELECT id FROM admins WHERE reset_token = ? AND reset_expires > ?");
                $stmt->execute([$reset_token, $current_time]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$admin) {
                    $error = 'Token không hợp lệ hoặc đã hết hạn.';
                } else {
                    // Cập nhật mật khẩu mới
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE admins SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
                    $stmt->execute([$hashed_password, $admin['id']]);
                    
                    // Ghi log
                    $logMessage = sprintf(
                        "[%s] Admin password reset via token for user ID: %d\n",
                        date('Y-m-d H:i:s'),
                        $admin['id']
                    );
                    file_put_contents(__DIR__ . '/admin_password_changes.log', $logMessage, FILE_APPEND | LOCK_EX);
                    
                    // Gửi email xác nhận đã đổi mật khẩu thành công
                    try {
                        require_once __DIR__ . '/email-smtp.php';
                        
                        // Lấy email admin từ database
                        $stmt = $pdo->prepare("SELECT email FROM admins WHERE id = ?");
                        $stmt->execute([$admin['id']]);
                        $admin_info = $stmt->fetch(PDO::FETCH_ASSOC);
                        $admin_email = $admin_info['email'] ?? '';
                        
                        if ($admin_email && filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
                            // Tạo email thông báo đã đổi mật khẩu thành công
                            $subject = 'Mật khẩu Admin đã được đặt lại thành công';
                            $body = '
                            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f8f9fa;">
                                <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center; color: white;">
                                    <h1 style="margin: 0; font-size: 28px;">✅ Thay đổi mật khẩu thành công</h1>
                                    <p style="margin: 10px 0 0 0; opacity: 0.9;">Portfolio Admin System</p>
                                </div>
                                
                                <div style="padding: 40px; background: white; margin: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    <h2 style="color: #333; margin-bottom: 20px;">Xin chào!</h2>
                                    
                                    <p style="color: #555; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                                        Mật khẩu admin của bạn đã được đặt lại thành công vào lúc ' . date('Y-m-d H:i:s') . '.
                                    </p>
                                    
                                    <div style="background: #d1ecf1; border: 1px solid #bee5eb; padding: 20px; border-radius: 8px; margin: 25px 0;">
                                        <p style="color: #0c5460; margin: 0; font-size: 14px;">
                                            <strong>💡 Gợi ý bảo mật:</strong><br>
                                            • Đăng nhập và đổi mật khẩu thành mật khẩu của riêng bạn<br>
                                            • Sử dụng mật khẩu mạnh và duy nhất<br>
                                            • Không chia sẻ mật khẩu với người khác
                                        </p>
                                    </div>
                                    
                                    <div style="text-align: center; margin-top: 30px;">
                                        <p style="color: #666; font-size: 14px; margin: 0;">
                                            Trân trọng,<br>
                                            <strong style="color: #333;">Portfolio Admin System</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>';
                            
                            // Dùng SMTP thực sự để gửi email thông báo
                            sendSMTPEmail($admin_email, $subject, $body);
                        }
                    } catch (Exception $e) {
                        // Không làm gián đoạn nếu gửi email thất bại
                    }
                    
                    $success = 'Mật khẩu đã được đặt lại thành công! Bạn có thể đăng nhập ngay bây giờ.';
                    
                    // Chuyển hướng về trang login sau 3 giây
                    header("Refresh: 3; URL=login.php");
                }
            }
        } catch (Exception $e) {
            $error = 'Lỗi: ' . $e->getMessage();
        }
    }
} else {
    // Form yêu cầu reset mật khẩu
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            verify_csrf_or_die();
            
            // Tạo reset token và lưu vào database
            $reset_token = bin2hex(random_bytes(32));
            $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("UPDATE admins SET reset_token = ?, reset_expires = ? WHERE id = 1");
            $stmt->execute([$reset_token, $reset_expires]);
            
            // Tạo link reset
            $reset_link = "http://localhost:8000/admin/forgot-password.php?token=" . $reset_token;
            
            // Gửi email thông báo với SMTP thực sự
                    try {
                        require_once __DIR__ . '/email-smtp.php';
                        
                        // Lấy email admin từ database
                         $stmt = $pdo->prepare("SELECT email FROM admins WHERE id = 1");
                         $stmt->execute();
                        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                        $admin_email = $admin['email'] ?? '';
                        
                        if ($admin_email && filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
                            // Dùng SMTP thực sự để gửi email
                            $result = sendPasswordResetSMTPEmail($admin_email, $reset_link);
                            
                            if ($result['success']) {
                                $success = "Email reset mật khẩu đã được gửi đến: " . htmlspecialchars($admin_email) . ". Vui lòng kiểm tra email của bạn.";
                                
                                // Ghi log
                                $logMessage = sprintf(
                                    "[%s] Password reset email sent to: %s\n",
                                    date('Y-m-d H:i:s'),
                                    $admin_email
                                );
                                file_put_contents(__DIR__ . '/admin_password_changes.log', $logMessage, FILE_APPEND | LOCK_EX);
                            } else {
                                $error = 'Không thể gửi email: ' . $result['error'];
                                // Fallback về file nếu SMTP lỗi
                                file_put_contents(__DIR__ . '/reset_password_link.txt', $reset_link);
                            }
                        } else {
                            // Nếu không có email hợp lệ, lưu vào file như cũ
                            $success = "Link reset mật khẩu đã được tạo. Vui lòng sao chép link sau để reset mật khẩu:";
                            file_put_contents(__DIR__ . '/reset_password_link.txt', $reset_link);
                        }
                    } catch (Exception $e) {
                        // Nếu có lỗi, fallback về file
                        $success = "Link reset mật khẩu đã được tạo. Vui lòng sao chép link sau để reset mật khẩu:";
                        file_put_contents(__DIR__ . '/reset_password_link.txt', $reset_link);
                        $error = 'Lỗi hệ thống email: ' . $e->getMessage();
                    }
            
        } catch (Exception $e) {
            $error = 'Lỗi: ' . $e->getMessage();
        }
    }
}

$pageTitle = $reset_token ? 'Đặt lại mật khẩu' : 'Quên mật khẩu';
require __DIR__ . '/_layout-header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="glass-card p-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent mb-2">
                    <?= $reset_token ? 'Đặt lại mật khẩu' : 'Quên mật khẩu' ?>
                </h2>
                <p class="text-gray-600">
                    <?= $reset_token ? 'Nhập mật khẩu mới của bạn' : 'Tạo link reset mật khẩu' ?>
                </p>
            </div>

            <?php if ($error): ?>
                <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($reset_token && $success): ?>
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">Chuyển hướng về trang đăng nhập trong 3 giây...</p>
                </div>
            <?php elseif ($reset_token): ?>
                <!-- Form đặt lại mật khẩu -->
                <form method="post" class="mt-6 space-y-4">
                    <?= csrf_field(); ?>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu mới</label>
                        <div class="relative">
                            <input type="password" name="new_password" id="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all pr-12" placeholder="Nhập mật khẩu mới" required>
                            <button type="button" onclick="togglePassword('new_password', 'toggle_new')" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                <i id="toggle_new" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                        <div class="relative">
                            <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all pr-12" placeholder="Xác nhận mật khẩu mới" required>
                            <button type="button" onclick="togglePassword('confirm_password', 'toggle_confirm')" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                <i id="toggle_confirm" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h4 class="font-semibold text-gray-700 mb-2">Yêu cầu mật khẩu:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Ít nhất 8 ký tự</li>
                            <li>• Có chữ hoa và chữ thường</li>
                            <li>• Có ít nhất 1 số</li>
                            <li>• Có ít nhất 1 ký tự đặc biệt</li>
                        </ul>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-red-600 hover:to-pink-700 transition-all duration-200 shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Đặt lại mật khẩu
                        </button>
                    </div>
                </form>
            <?php elseif ($success && file_exists(__DIR__ . '/reset_password_link.txt')): ?>
                <!-- Hiển thị link reset -->
                <?php $reset_link = file_get_contents(__DIR__ . '/reset_password_link.txt'); ?>
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-sm text-blue-700 mb-2">Link reset mật khẩu:</p>
                    <div class="bg-white p-3 rounded border text-xs break-all font-mono">
                        <?= htmlspecialchars($reset_link) ?>
                    </div>
                    <button onclick="copyResetLink()" class="mt-2 w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition-colors">
                        <i class="fas fa-copy mr-2"></i>Sao chép link
                    </button>
                </div>
            <?php else: ?>
                <!-- Form yêu cầu reset -->
                <form method="post" class="mt-6 space-y-4">
                    <?= csrf_field(); ?>
                    
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <span class="font-semibold text-blue-800">Thông tin:</span>
                        </div>
                        <p class="text-sm text-blue-700">
                            Chức năng này sẽ gửi email đặt lại mật khẩu đến email admin. Nếu email không được cấu hình, link sẽ được lưu vào file.
                        </p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-red-600 hover:to-pink-700 transition-all duration-200 shadow-lg">
                            <i class="fas fa-key mr-2"></i>
                            Tạo link reset mật khẩu
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="mt-6 text-center">
                <a href="login.php" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Quay lại đăng nhập
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function copyResetLink() {
    const linkText = document.querySelector('.font-mono').textContent;
    navigator.clipboard.writeText(linkText).then(function() {
        alert('Link đã được sao chép!');
    }, function(err) {
        alert('Không thể sao chép link: ' + err);
    });
}
</script>

<?php require __DIR__ . '/_layout-footer.php'; ?>