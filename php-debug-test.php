<?php
/**
 * 🔍 PHP DEBUG COMPREHENSIVE TEST
 * Chạy script này để kiểm tra toàn bộ hệ thống
 */

// Maximum error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

// Start output
echo "<!DOCTYPE html>\n<html><head>";
echo "<meta charset='UTF-8'>";
echo "<title>🔍 PHP Debug Test - myntex.io.vn</title>";
echo "<style>";
echo "body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;line-height:1.6;}";
echo ".container{max-width:1000px;margin:0 auto;background:white;padding:20px;border-radius:10px;box-shadow:0 4px 6px rgba(0,0,0,0.1);}";
echo ".ok{color:#22c55e;font-weight:bold;}";
echo ".error{color:#ef4444;font-weight:bold;}";
echo ".warning{color:#f59e0b;font-weight:bold;}";
echo ".info{color:#3b82f6;font-weight:bold;}";
echo ".section{margin:20px 0;padding:15px;border-left:4px solid #ddd;background:#f9fafb;border-radius:5px;}";
echo ".section h3{margin-top:0;color:#1f2937;}";
echo ".code{background:#1f2937;color:#f9fafb;padding:10px;border-radius:5px;font-family:monospace;font-size:12px;overflow-x:auto;}";
echo "table{width:100%;border-collapse:collapse;margin:10px 0;}";
echo "th,td{padding:8px;text-align:left;border-bottom:1px solid #ddd;font-size:14px;}";
echo "th{background:#f3f4f6;font-weight:bold;}";
echo "</style></head><body>";

echo "<div class='container'>";
echo "<h1>🔍 PHP DEBUG COMPREHENSIVE TEST</h1>";
echo "<p><strong>Domain:</strong> myntex.io.vn</p>";
echo "<p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "</p>";

// 1. PHP Configuration Test
echo "<div class='section'>";
echo "<h3>⚙️ PHP Configuration</h3>";
$php_config = [
    'display_errors' => ini_get('display_errors'),
    'error_reporting' => ini_get('error_reporting'),
    'max_execution_time' => ini_get('max_execution_time'),
    'memory_limit' => ini_get('memory_limit'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'allow_url_fopen' => ini_get('allow_url_fopen'),
    'allow_url_include' => ini_get('allow_url_include'),
    'open_basedir' => ini_get('open_basedir'),
    'disable_functions' => ini_get('disable_functions'),
    'disable_classes' => ini_get('disable_classes')
];

echo "<table>";
foreach ($php_config as $key => $value) {
    $status = ($value === '' || $value === '0' || $value === 'Off') ? 'warning' : 'ok';
    if ($key === 'display_errors' && $value === '1') $status = 'ok';
    if ($key === 'error_reporting' && $value == '32767') $status = 'ok';
    echo "<tr><td><strong>$key</strong></td><td class='$status'>$value</td></tr>";
}
echo "</table>";
echo "</div>";

// 2. Extensions Test
echo "<div class='section'>";
echo "<h3>📦 PHP Extensions</h3>";
$required_extensions = [
    'pdo' => 'Required for database',
    'pdo_mysql' => 'Required for MySQL',
    'mbstring' => 'Required for UTF-8',
    'openssl' => 'Required for SSL/TLS',
    'json' => 'Required for API',
    'curl' => 'Required for email',
    'gd' => 'Required for images',
    'fileinfo' => 'Required for uploads',
    'intl' => 'Optional for internationalization',
    'zip' => 'Optional for archives'
];

echo "<table>";
foreach ($required_extensions as $ext => $description) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? 'ok' : 'error';
    $icon = $loaded ? '✅' : '❌';
    echo "<tr><td><strong>$ext</strong></td><td>$description</td><td class='$status'>$icon " . ($loaded ? 'Loaded' : 'Not loaded') . "</td></tr>";
}
echo "</table>";
echo "</div>";

// 3. Directory and File Permissions Test
echo "<div class='section'>";
echo "<h3>📁 Directory & File Permissions</h3>";
$test_paths = [
    '.' => 'Current directory',
    'admin' => 'Admin directory',
    'admin/config.php' => 'Config file',
    'admin/bootstrap.php' => 'Bootstrap file',
    'admin/uploads' => 'Uploads directory',
    'assets' => 'Assets directory',
    'api' => 'API directory',
    'database' => 'Database directory'
];

echo "<table>";
echo "<tr><th>Path</th><th>Description</th><th>Exists</th><th>Readable</th><th>Writable</th><th>Permissions</th></tr>";
foreach ($test_paths as $path => $description) {
    $exists = file_exists($path);
    $readable = $exists && is_readable($path);
    $writable = $exists && is_writable($path);
    $perms = $exists ? substr(sprintf('%o', fileperms($path)), -3) : 'N/A';
    
    $exists_class = $exists ? 'ok' : 'error';
    $readable_class = $readable ? 'ok' : 'error';
    $writable_class = $writable ? 'ok' : 'warning';
    
    echo "<tr>";
    echo "<td><strong>$path</strong></td>";
    echo "<td>$description</td>";
    echo "<td class='$exists_class'>" . ($exists ? '✅' : '❌') . "</td>";
    echo "<td class='$readable_class'>" . ($readable ? '✅' : '❌') . "</td>";
    echo "<td class='$writable_class'>" . ($writable ? '✅' : '⚠️') . "</td>";
    echo "<td>$perms</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// 4. Configuration Files Test
echo "<div class='section'>";
echo "<h3>⚙️ Configuration Files</h3>";
$config_files = [
    'admin/config.php' => 'Admin configuration',
    'admin/bootstrap.php' => 'Bootstrap file',
    'admin/email-smtp.php' => 'Email SMTP file'
];

echo "<table>";
foreach ($config_files as $file => $description) {
    if (file_exists($file)) {
        echo "<tr><td><strong>$file</strong></td><td class='ok'>✅ $description - Found</td></tr>";
        
        // Test config loading
        if ($file === 'admin/config.php') {
            try {
                $config = require $file;
                echo "<tr><td colspan='2'><div class='code'>";
                echo "Database: " . ($config['db']['name'] ?? 'Not set') . "<br>";
                echo "Email enabled: " . ($config['email']['enabled'] ? 'Yes' : 'No') . "<br>";
                echo "SMTP Host: " . ($config['email']['smtp_host'] ?? 'Not set') . "<br>";
                echo "</div></td></tr>";
            } catch (Exception $e) {
                echo "<tr><td colspan='2' class='error'>❌ Config error: " . $e->getMessage() . "</td></tr>";
            }
        }
    } else {
        echo "<tr><td><strong>$file</strong></td><td class='error'>❌ $description - Not found</td></tr>";
    }
}
echo "</table>";
echo "</div>";

// 5. Database Connection Test
echo "<div class='section'>";
echo "<h3>🗄️ Database Connection Test</h3>";
if (file_exists('admin/config.php')) {
    try {
        $config = require 'admin/config.php';
        $db = $config['db'];
        
        echo "<p><strong>Database:</strong> {$db['name']}</p>";
        echo "<p><strong>Host:</strong> {$db['host']}</p>";
        echo "<p><strong>User:</strong> {$db['user']}</p>";
        
        $pdo = new PDO(
            "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",
            $db['user'],
            $db['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        echo "<p class='ok'>✅ Database connection successful!</p>";
        
        // Test tables
        $tables = ['admins', 'projects', 'password_resets', 'site_settings'];
        echo "<table>";
        echo "<tr><th>Table</th><th>Status</th><th>Count</th></tr>";
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
                $count = $stmt->fetchColumn();
                echo "<tr><td><strong>$table</strong></td><td class='ok'>✅ Exists</td><td>$count</td></tr>";
            } catch (Exception $e) {
                echo "<tr><td><strong>$table</strong></td><td class='error'>❌ " . $e->getMessage() . "</td><td>-</td></tr>";
            }
        }
        echo "</table>";
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ Database connection failed: " . $e->getMessage() . "</p>";
        echo "<div class='code'>";
        echo "Error: " . $e->getMessage() . "\n";
        echo "Code: " . $e->getCode() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n";
        echo "</div>";
    }
} else {
    echo "<p class='error'>❌ Config file not found</p>";
}
echo "</div>";

// 6. Email Configuration Test
echo "<div class='section'>";
echo "<h3>📧 Email Configuration Test</h3>";
if (file_exists('admin/config.php')) {
    try {
        $config = require 'admin/config.php';
        $email = $config['email'];
        
        echo "<p><strong>Email enabled:</strong> " . ($email['enabled'] ? 'Yes' : 'No') . "</p>";
        echo "<p><strong>SMTP Host:</strong> {$email['smtp_host']}</p>";
        echo "<p><strong>SMTP Port:</strong> {$email['smtp_port']}</p>";
        echo "<p><strong>From Email:</strong> {$email['from_email']}</p>";
        echo "<p><strong>Admin Email:</strong> {$email['admin_email']}</p>";
        
        if ($email['enabled']) {
            // Test SMTP connection
            $smtp_host = $email['smtp_host'];
            $smtp_port = $email['smtp_port'];
            
            $timeout = 10;
            $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, $timeout);
            
            if ($socket) {
                echo "<p class='ok'>✅ SMTP connection successful to $smtp_host:$smtp_port</p>";
                fclose($socket);
                
                // Test email sending
                echo "<p class='info'>ℹ️ To test email sending, visit: <a href='admin/test-email.php'>admin/test-email.php</a></p>";
            } else {
                echo "<p class='error'>❌ SMTP connection failed: $errstr ($errno)</p>";
                echo "<p class='warning'>⚠️ This might be normal for localhost, but check on hosting</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ Email config error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>❌ Config file not found</p>";
}
echo "</div>";

// 7. JavaScript Files Test
echo "<div class='section'>";
echo "<h3>📜 JavaScript Files Test</h3>";
$js_files = [
    'assets/js/main.js' => 'Main JavaScript',
    'assets/js/load-projects.js' => 'Projects loader',
    'assets/js/image-modal.js' => 'Image modal',
    'assets/js/dark-mode.js' => 'Dark mode',
    'admin/assets/js/admin.js' => 'Admin JavaScript'
];

echo "<table>";
echo "<tr><th>File</th><th>Status</th><th>Size</th></tr>";
foreach ($js_files as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        $size_kb = round($size / 1024, 2);
        echo "<tr><td><strong>$file</strong></td><td class='ok'>✅ Found</td><td>$size_kb KB</td></tr>";
    } else {
        echo "<tr><td><strong>$file</strong></td><td class='error'>❌ Not found</td><td>-</td></tr>";
    }
}
echo "</table>";
echo "</div>";

// 8. CSS Files Test
echo "<div class='section'>";
echo "<h3>🎨 CSS Files Test</h3>";
$css_files = [
    'assets/css/style.css' => 'Main stylesheet',
    'admin/assets/css/admin.css' => 'Admin stylesheet'
];

echo "<table>";
echo "<tr><th>File</th><th>Status</th><th>Size</th></tr>";
foreach ($css_files as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        $size_kb = round($size / 1024, 2);
        echo "<tr><td><strong>$file</strong></td><td class='ok'>✅ Found</td><td>$size_kb KB</td></tr>";
    } else {
        echo "<tr><td><strong>$file</strong></td><td class='error'>❌ Not found</td><td>-</td></tr>";
    }
}
echo "</table>";
echo "</div>";

// 9. Server Information
echo "<div class='section'>";
echo "<h3>🖥️ Server Information</h3>";
$server_info = [
    'SERVER_SOFTWARE' => $_SERVER['SERVER_SOFTWARE'] ?? 'Not set',
    'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? 'Not set',
    'SERVER_ADDR' => $_SERVER['SERVER_ADDR'] ?? 'Not set',
    'SERVER_PORT' => $_SERVER['SERVER_PORT'] ?? 'Not set',
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'Not set',
    'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? 'Not set',
    'HTTPS' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'Yes' : 'No',
    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? 'Not set',
    'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'Not set',
    'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? 'Not set'
];

echo "<table>";
foreach ($server_info as $key => $value) {
    echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
}
echo "</table>";
echo "</div>";

// 10. Quick Actions
echo "<div class='section'>";
echo "<h3>⚡ Quick Actions</h3>";
echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px;'>";
$actions = [
    'admin/index.php' => 'Admin Panel',
    'admin/test-email.php' => 'Test Email',
    'admin/debug-cpanel.php' => 'Debug cPanel',
    'admin/check-token.php' => 'Check Token',
    'index.html' => 'Main Website',
    'test-myntex-io-vn.php' => 'Test Domain'
];

foreach ($actions as $file => $description) {
    if (file_exists($file)) {
        echo "<a href='$file' target='_blank' style='display: block; padding: 10px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; text-align: center;'>$description</a>";
    } else {
        echo "<div style='padding: 10px; background: #ef4444; color: white; border-radius: 5px; text-align: center;'>$description - Not found</div>";
    }
}
echo "</div>";
echo "</div>";

// Summary
echo "<div class='section' style='background: #e8f5e8;'>";
echo "<h3>✅ TÓM TẮT</h3>";
echo "<p>Script này giúp bạn kiểm tra toàn bộ hệ thống PHP và các file cần thiết.</p>";
echo "<p>Nếu thấy lỗi (❌), hãy fix theo hướng dẫn trong từng phần.</p>";
echo "<p>Đối với hosting myntex.io.vn, hãy upload toàn bộ code và chạy lại script này để kiểm tra.</p>";
echo "</div>";

echo "</div>";
echo "</body></html>";