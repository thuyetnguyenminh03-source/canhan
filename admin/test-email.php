<?php
// Test email functionality
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/email-smtp.php';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">
                    <i class="fas fa-envelope text-blue-500 mr-2"></i>
                    Test Email
                </h2>
                
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    try {
                        $test_type = $_POST['test_type'] ?? '';
                        $email = $_POST['email'] ?? 'thuyet.nguyenminh03@gmail.com';
                        
                        if ($test_type === 'reset') {
                            $reset_link = "http://localhost:8000/admin/forgot-password.php?token=test_" . bin2hex(random_bytes(16));
                            $result = sendPasswordResetSMTPEmail($email, $reset_link);
                            $message = $result['success'] ? '✅ Email reset password đã được gửi qua SMTP!' : '❌ Lỗi: ' . $result['error'];
                        } else {
                            $result = sendSMTPEmail($email, 'Test Email', '<h3>Test Email</h3><p>Đây là email test từ admin panel qua SMTP.</p>');
                            $message = $result['success'] ? '✅ Email test đã được gửi qua SMTP!' : '❌ Lỗi: ' . $result['error'];
                        }
                        
                        $alert_class = $result['success'] ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                        ?>
                        <div class="border rounded-lg p-4 mb-4 <?= $alert_class ?>">
                            <p class="font-semibold"><?= htmlspecialchars($message) ?></p>
                            <p class="text-sm mt-2">Thời gian: <?= date('Y-m-d H:i:s') ?></p>
                        </div>
                        <?php
                    } catch (Exception $e) {
                        ?>
                        <div class="border rounded-lg p-4 mb-4 bg-red-100 border-red-400 text-red-700">
                            <p class="font-semibold">❌ Lỗi hệ thống:</p>
                            <p><?= htmlspecialchars($e->getMessage()) ?></p>
                        </div>
                        <?php
                    }
                }
                ?>
                
                <form method="post" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email người nhận:</label>
                        <input type="email" name="email" value="thuyet.nguyenminh03@gmail.com" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loại test:</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="test_type" value="simple" checked class="mr-2">
                                <span>Email đơn giản</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="test_type" value="reset" class="mr-2">
                                <span>Email reset password</span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Gửi Email Test
                    </button>
                </form>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-700 mb-2">Cấu hình email hiện tại:</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Email:</strong> thuyet.nguyenminh03@gmail.com</p>
                        <p><strong>SMTP:</strong> smtp.gmail.com:587</p>
                        <p><strong>Trạng thái:</strong> 
                            <span class="text-green-600 font-semibold">
                                <?php 
                                global $config;
                                echo $config['email']['enabled'] ? 'Đã bật' : 'Đã tắt';
                                ?>
                            </span>
                        </p>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="password-manager.php" class="text-blue-500 hover:text-blue-700 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Quay lại Password Manager
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>