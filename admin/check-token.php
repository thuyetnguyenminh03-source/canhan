<?php
// Kiểm tra token reset password
require_once __DIR__ . '/bootstrap.php';

$token = $_GET['token'] ?? '';
$error = '';
$info = [];

if ($token) {
    try {
        // Kiểm tra token có hợp lệ không - sử dụng PHP time để tránh lỗi múi giờ
        $current_time = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("SELECT id, username, email, reset_expires FROM admin_users WHERE reset_token = ? AND reset_expires > ?");
        $stmt->execute([$token, $current_time]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            $info = [
                'status' => 'valid',
                'username' => $admin['username'],
                'email' => $admin['email'],
                'expires' => $admin['reset_expires'],
                'message' => 'Token hợp lệ'
            ];
        } else {
            // Kiểm tra xem token có tồn tại nhưng đã hết hạn không
            $stmt = $pdo->prepare("SELECT reset_expires FROM admin_users WHERE reset_token = ?");
            $stmt->execute([$token]);
            $expired = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($expired) {
                $info = [
                    'status' => 'expired',
                    'expires' => $expired['reset_expires'],
                    'message' => 'Token đã hết hạn'
                ];
            } else {
                $info = [
                    'status' => 'invalid',
                    'message' => 'Token không tồn tại'
                ];
            }
        }
    } catch (Exception $e) {
        $error = 'Lỗi: ' . $e->getMessage();
    }
} else {
    $error = 'Vui lòng cung cấp token';
}

// Trả về JSON nếu là AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
    echo json_encode(['info' => $info, 'error' => $error]);
    exit;
}

// Hiển thị giao diện
echo '<!DOCTYPE html>';
echo '<html lang="vi">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Kiểm tra Token - Admin Panel</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">';
echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">';
echo '</head>';
echo '<body class="bg-gray-100 min-h-screen">';
echo '<div class="container mx-auto px-4 py-8">';
echo '<div class="max-w-md mx-auto">';
echo '<div class="bg-white rounded-lg shadow-lg p-6">';
echo '<h2 class="text-2xl font-bold text-center mb-6 text-gray-800"><i class="fas fa-key text-blue-500 mr-2"></i>Kiểm tra Token</h2>';

if ($error) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">';
    echo '<p>' . htmlspecialchars($error) . '</p>';
    echo '</div>';
}

if (!empty($info)) {
    $bg_class = $info['status'] === 'valid' ? 'bg-green-100 border-green-400 text-green-700' : 
                 ($info['status'] === 'expired' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' : 'bg-red-100 border-red-400 text-red-700');
    
    echo '<div class="border rounded-lg p-4 mb-4 ' . $bg_class . '">';
    echo '<p class="font-semibold mb-2">Trạng thái: ' . $info['message'] . '</p>';
    
    if (isset($info['username'])) {
        echo '<p><strong>Username:</strong> ' . htmlspecialchars($info['username']) . '</p>';
    }
    if (isset($info['email'])) {
        echo '<p><strong>Email:</strong> ' . htmlspecialchars($info['email']) . '</p>';
    }
    if (isset($info['expires'])) {
        echo '<p><strong>Hết hạn:</strong> ' . htmlspecialchars($info['expires']) . '</p>';
        
        $now = new DateTime();
        $expires = new DateTime($info['expires']);
        $diff = $now->diff($expires);
        
        if ($info['status'] === 'valid') {
            echo '<p><strong>Còn lại:</strong> ' . $diff->format('%H giờ %I phút') . '</p>';
        }
    }
    echo '</div>';
}

echo '<form method="get" class="space-y-4">';
echo '<div>';
echo '<label class="block text-sm font-medium text-gray-700 mb-2">Token:</label>';
echo '<input type="text" name="token" value="' . htmlspecialchars($token) . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập token reset password">';
echo '</div>';
echo '<button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">Kiểm tra Token</button>';
echo '</form>';

echo '<div class="mt-6 text-center">';
echo '<a href="password-manager.php" class="text-blue-500 hover:text-blue-700 text-sm"><i class="fas fa-arrow-left mr-1"></i>Quay lại Password Manager</a>';
echo '</div>';

echo '</div>';
echo '</div>';
echo '</div>';
echo '</body>';
echo '</html>';