<?php
/**
 * Database Password Change Utility
 * Security tool to change database password
 */

// Include current config to get connection info
$config = include __DIR__ . '/config.php';

// Function to generate strong password
function generateStrongPassword($length = 16) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
    $password = '';
    $charLength = strlen($chars);
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $charLength - 1)];
    }
    
    return $password;
}

// Function to test database connection
function testDatabaseConnection($host, $user, $pass, $dbname) {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5
        ]);
        
        // Test query
        $pdo->query("SELECT 1");
        return ['success' => true, 'message' => 'Connection successful'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()];
    }
}

// Handle form submission
$message = '';
$messageType = '';
$newPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'generate') {
        $newPassword = generateStrongPassword(16);
        $message = 'Generated new password: ' . $newPassword;
        $messageType = 'info';
    } elseif ($action === 'change') {
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($newPassword)) {
            $message = 'Please enter a new password';
            $messageType = 'error';
        } elseif (strlen($newPassword) < 8) {
            $message = 'Password must be at least 8 characters long';
            $messageType = 'error';
        } elseif ($newPassword !== $confirmPassword) {
            $message = 'Passwords do not match';
            $messageType = 'error';
        } else {
            // Attempt to change password
            try {
                // Connect with current credentials
                $currentConfig = $config['db'];
                $pdo = new PDO(
                    "mysql:host={$currentConfig['host']};charset=utf8mb4",
                    $currentConfig['user'],
                    $currentConfig['pass'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                // Change password
                $stmt = $pdo->prepare("ALTER USER ?@? IDENTIFIED BY ?");
                $stmt->execute([$currentConfig['user'], $currentConfig['host'], $newPassword]);
                
                // Test new connection
                $testResult = testDatabaseConnection(
                    $currentConfig['host'],
                    $currentConfig['user'],
                    $newPassword,
                    $currentConfig['name']
                );
                
                if ($testResult['success']) {
                    // Update config file
                    $newConfig = $config;
                    $newConfig['db']['pass'] = $newPassword;
                    
                    $configContent = "<?php\nreturn " . var_export($newConfig, true) . ";\n";
                    file_put_contents(__DIR__ . '/config.php', $configContent);
                    
                    $message = 'Password changed successfully! New password: ' . $newPassword;
                    $messageType = 'success';
                    
                    // Log the change
                    $logMessage = date('Y-m-d H:i:s') . " - Password changed for user: " . $currentConfig['user'] . "\n";
                    file_put_contents(__DIR__ . '/password_changes.log', $logMessage, FILE_APPEND | LOCK_EX);
                    
                } else {
                    $message = 'Password changed but connection test failed: ' . $testResult['message'];
                    $messageType = 'error';
                }
                
            } catch (Exception $e) {
                $message = 'Error changing password: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đổi Mật Khẩu Database - Security Tool</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="password"]:focus, input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #ecf0f1;
            color: #2c3e50;
        }
        
        .btn-secondary:hover {
            background: #d5dbdb;
        }
        
        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .current-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .current-info strong {
            color: #2c3e50;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Đổi Mật Khẩu Database</h1>
        <p class="subtitle">Công cụ bảo mật để thay đổi mật khẩu database</p>
        
        <div class="warning">
            <strong>⚠️ Lưu ý quan trọng:</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
                <li>Sao lưu database trước khi thay đổi</li>
                <li>Lưu lại mật khẩu mới ở nơi an toàn</li>
                <li>Cập nhật mật khẩu trong file config</li>
                <li>Kiểm tra lại toàn bộ website sau khi đổi</li>
            </ul>
        </div>
        
        <div class="current-info">
            <strong>Thông tin hiện tại:</strong><br>
            Host: <strong><?= htmlspecialchars($config['db']['host']) ?></strong><br>
            Database: <strong><?= htmlspecialchars($config['db']['name']) ?></strong><br>
            User: <strong><?= htmlspecialchars($config['db']['user']) ?></strong><br>
            Password hiện tại: <strong><?= str_repeat('*', strlen($config['db']['pass'])) ?></strong>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
                <?php if ($messageType === 'success' && !empty($newPassword)): ?>
                    <br><br>
                    <strong>Mật khẩu mới:</strong> <code style="background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 4px;"><?= htmlspecialchars($newPassword) ?></code>
                    <br><br>
                    <strong>⚠️ Hãy sao chép mật khẩu này và lưu ở nơi an toàn!</strong>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" id="new_password" name="new_password" value="<?= htmlspecialchars($newPassword) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="button-group">
                <button type="submit" name="action" value="generate" class="btn-secondary">
                    🎲 Tạo mật khẩu ngẫu nhiên
                </button>
                <button type="submit" name="action" value="change" class="btn-primary">
                    🔒 Đổi mật khẩu
                </button>
            </div>
        </form>
        
        <a href="dashboard.php" class="back-link">← Quay lại Dashboard</a>
    </div>
    
    <script>
        // Show password strength indicator
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            // Remove existing strength indicator
            const existingIndicator = document.querySelector('.strength-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            if (password.length > 0) {
                const indicator = document.createElement('div');
                indicator.className = 'strength-indicator';
                indicator.style.marginTop = '5px';
                indicator.style.fontSize = '12px';
                indicator.style.color = strength.color;
                indicator.textContent = strength.text;
                
                this.parentNode.appendChild(indicator);
            }
        });
        
        function calculatePasswordStrength(password) {
            let score = 0;
            
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            
            if (score <= 2) return { text: 'Yếu', color: '#e74c3c' };
            if (score <= 4) return { text: 'Trung bình', color: '#f39c12' };
            if (score <= 5) return { text: 'Mạnh', color: '#27ae60' };
            return { text: 'Rất mạnh', color: '#2ecc71' };
        }
        
        // Confirm before submitting
        document.querySelector('form').addEventListener('submit', function(e) {
            if (e.submitter.value === 'change') {
                if (!confirm('Bạn có chắc chắn muốn đổi mật khẩu database? Việc này sẽ ảnh hưởng đến toàn bộ website!')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>