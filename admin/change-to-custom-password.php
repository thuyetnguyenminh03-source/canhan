<?php
/**
 * Change password to thuyet164@
 * Custom password change script
 */

$config = include __DIR__ . '/config.php';

$newPassword = 'thuyet164@';

echo "🔐 ĐỔI PASSWORD THÀNH: thuyet164@\n";
echo "======================================\n\n";

// Test current connection
echo "1. Kiểm tra kết nối hiện tại...\n";
try {
    $db = $config['db'];
    $pdo = new PDO(
        "mysql:host={$db['host']};charset=utf8mb4",
        $db['user'],
        $db['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "   ✅ Kết nối thành công\n\n";
    
    // Change password
    echo "2. Đổi password thành 'thuyet164@'...\n";
    try {
        $stmt = $pdo->prepare("ALTER USER ?@? IDENTIFIED BY ?");
        $stmt->execute([$db['user'], $db['host'], $newPassword]);
        echo "   ✅ Password đã được thay đổi!\n\n";
        
        // Test new password
        echo "3. Kiểm tra mật khẩu mới...\n";
        try {
            $newPdo = new PDO(
                "mysql:host={$db['host']};dbname={$db['name']};charset=utf8mb4",
                $db['user'],
                $newPassword,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Test query
            $stmt = $newPdo->query("SELECT COUNT(*) FROM projects");
            $count = $stmt->fetchColumn();
            echo "   ✅ Kết nối với mật khẩu mới thành công!\n";
            echo "   📊 Có $count projects trong database\n\n";
            
            // Update config file
            echo "4. Cập nhật config.php...\n";
            $newConfig = $config;
            $newConfig['db']['pass'] = $newPassword;
            
            $configContent = "<?php\nreturn " . var_export($newConfig, true) . ";\n";
            file_put_contents(__DIR__ . '/config.php', $configContent);
            echo "   ✅ Config đã được cập nhật!\n\n";
            
            echo "🎉 HOÀN THÀNH! Password mới: thuyet164@\n";
            
        } catch (PDOException $e) {
            echo "   ❌ Lỗi kết nối với mật khẩu mới: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Không thể đổi password: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "   ❌ Kết nối thất bại: " . $e->getMessage() . "\n";
}

echo "\n⚠️  CẢNH BÁO BẢO MẬT:\n";
echo "   Mật khẩu này chứa thông tin cá nhân và pattern đơn giản.\n";
echo "   Nên dùng mật khẩu ngẫu nhiên mạnh hơn như: c6VOtRnQYVt7eJz4\n";
echo "   Hoặc tạo mật khẩu có: chữ hoa, thường, số, ký tự đặc biệt, >12 ký tự\n";

?>

<style>
body { font-family: monospace; background: #f5f5f5; padding: 20px; }
pre { background: #2c3e50; color: #ecf0f1; padding: 20px; border-radius: 8px; }
.warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 8px; margin: 20px 0; }
</style>

<div class="warning">
    <strong>⚠️ CẢNH BÁO BẢO MẬT:</strong><br>
    Mật khẩu 'thuyet164@' chứa thông tin cá nhân và pattern dễ đoán.<br>
    Khuyên dùng mật khẩu ngẫu nhiên mạnh hơn để bảo vệ tốt hơn.
</div>

<pre><?php 
ob_start();
include __FILE__;
$output = ob_get_clean();
echo htmlspecialchars($output);
?></pre>