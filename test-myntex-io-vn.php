<?php
/**
 * Test Script cho myntex.io.vn
 * Upload lên hosting và chạy để kiểm tra
 */

// Domain configuration
define('DOMAIN', 'myntex.io.vn');
define('ADMIN_URL', 'https://myntex.io.vn/admin/');
define('MAIN_URL', 'https://myntex.io.vn/');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if running on correct domain
if ($_SERVER['HTTP_HOST'] !== DOMAIN) {
    die("⚠️ Script này chỉ chạy trên domain: " . DOMAIN);
}

echo "<!DOCTYPE html>\n<html><head>";
echo "<meta charset='UTF-8'>";
echo "<title>🔍 Test myntex.io.vn</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}";
echo ".ok{color:green;}.error{color:red;}.warning{color:orange;}";
echo ".box{background:white;padding:20px;margin:10px 0;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1);}";
echo "</style></head><body>";

echo "<h1>🔍 KIỂM TRA myntex.io.vn</h1>";
echo "<div class='box'>";
echo "<h2>📍 Domain Info</h2>";
echo "<p><strong>Domain:</strong> " . DOMAIN . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

// Test database
echo "<div class='box'>";
echo "<h2>🗄️ Database Test</h2>";

if (file_exists('admin/config.php')) {
    $config = require 'admin/config.php';
    
    try {
        $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}";
        $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass']);
        
        echo "<p class='ok'>✅ Kết nối database thành công!</p>";
        echo "<p><strong>Database:</strong> {$config['db']['name']}</p>";
        
        // Test tables
        $tables = ['admins', 'projects', 'password_resets'];
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
                $count = $stmt->fetchColumn();
                echo "<p class='ok'>✅ Bảng $table: $count records</p>";
            } catch (Exception $e) {
                echo "<p class='error'>❌ Bảng $table: " . $e->getMessage() . "</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ Lỗi database: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>❌ Không tìm thấy file admin/config.php</p>";
}
echo "</div>";

// Test email
echo "<div class='box'>";
echo "<h2>📧 Email Configuration</h2>";
if (file_exists('admin/config.php')) {
    $config = require 'admin/config.php';
    echo "<p><strong>SMTP Host:</strong> {$config['email']['smtp_host']}</p>";
    echo "<p><strong>SMTP Port:</strong> {$config['email']['smtp_port']}</p>";
    echo "<p><strong>From Email:</strong> {$config['email']['from_email']}</p>";
    echo "<p><strong>Email Enabled:</strong> " . ($config['email']['enabled'] ? 'Yes' : 'No') . "</p>";
    
    if ($config['email']['enabled']) {
        // Test SMTP connection
        $smtp_host = $config['email']['smtp_host'];
        $smtp_port = $config['email']['smtp_port'];
        
        $timeout = 10;
        $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, $timeout);
        
        if ($socket) {
            echo "<p class='ok'>✅ Kết nối SMTP thành công đến $smtp_host:$smtp_port</p>";
            fclose($socket);
        } else {
            echo "<p class='error'>❌ Kết nối SMTP thất bại: $errstr ($errno)</p>";
        }
    }
}
echo "</div>";

// Test admin login
echo "<div class='box'>";
echo "<h2>🔐 Admin Panel Test</h2>";
echo "<p><strong>Admin URL:</strong> <a href='" . ADMIN_URL . "' target='_blank'>" . ADMIN_URL . "</a></p>";

// Check if bootstrap exists
if (file_exists('admin/bootstrap.php')) {
    echo "<p class='ok'>✅ File admin/bootstrap.php tồn tại</p>";
} else {
    echo "<p class='error'>❌ Thiếu file admin/bootstrap.php</p>";
}

// Check if email smtp exists
if (file_exists('admin/email-smtp.php')) {
    echo "<p class='ok'>✅ File admin/email-smtp.php tồn tại</p>";
} else {
    echo "<p class='error'>❌ Thiếu file admin/email-smtp.php</p>";
}
echo "</div>";

// Test file permissions
echo "<div class='box'>";
echo "<h2>📁 File Permissions</h2>";
$important_files = [
    'admin/config.php' => '600',
    'admin/bootstrap.php' => '644',
    'admin/index.php' => '644',
    'admin/email-smtp.php' => '644',
    'admin/uploads/' => '755',
    'api/site.php' => '644'
];

foreach ($important_files as $file => $recommended_perm) {
    if (file_exists($file)) {
        $actual_perm = substr(sprintf('%o', fileperms($file)), -3);
        $status_class = ($actual_perm == $recommended_perm) ? 'ok' : 'warning';
        $status_icon = ($actual_perm == $recommended_perm) ? '✅' : '⚠️';
        echo "<p class='$status_class'>$status_icon $file: $actual_perm (recommended: $recommended_perm)</p>";
    } else {
        echo "<p class='error'>❌ $file: Không tìm thấy</p>";
    }
}
echo "</div>";

// Test main website
echo "<div class='box'>";
echo "<h2>🌐 Main Website Test</h2>";
echo "<p><strong>Main URL:</strong> <a href='" . MAIN_URL . "' target='_blank'>" . MAIN_URL . "</a></p>";

// Check if main files exist
$main_files = ['index.html', 'assets/css/style.css', 'assets/js/main.js', 'assets/js/load-projects.js'];
foreach ($main_files as $file) {
    if (file_exists($file)) {
        echo "<p class='ok'>✅ $file tồn tại</p>";
    } else {
        echo "<p class='error'>❌ Thiếu $file</p>";
    }
}

// Check if image modal exists
if (file_exists('assets/js/image-modal.js')) {
    echo "<p class='ok'>✅ File image-modal.js tồn tại</p>";
} else {
    echo "<p class='error'>❌ Thiếu file image-modal.js</p>";
}
echo "</div>";

// Quick actions
echo "<div class='box'>";
echo "<h2>⚡ Quick Actions</h2>";
echo "<p>🔗 <a href='admin/debug-cpanel.php' target='_blank'>Chạy debug script chi tiết</a></p>";
echo "<p>🔗 <a href='admin/test-email.php' target='_blank'>Test gửi email</a></p>";
echo "<p>🔗 <a href='admin/check-token.php' target='_blank'>Kiểm tra token</a></p>";
echo "</div>";

echo "<div class='box' style='background: #e8f5e8;'>";
echo "<h2>✅ HOÀN THÀNH</h2>";
echo "<p>Script này giúp bạn kiểm tra toàn bộ hệ thống trên myntex.io.vn</p>";
echo "<p>Nếu thấy lỗi, hãy fix theo hướng dẫn trong từng phần.</p>";
echo "</div>";

echo "</body></html>";