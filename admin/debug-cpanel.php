<?php
/**
 * Debug Script for cPanel Hosting
 * Upload this to your hosting and run to check configuration
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration check
echo "<!DOCTYPE html>\n<html><head><title>cPanel Debug</title></head><body>";
echo "<h1>🔍 cPanel Hosting Debug Report</h1>";
echo "<p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// Check required extensions
echo "<h2>📦 PHP Extensions</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json'];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? "✅" : "❌";
    echo "<p>$status $ext</p>";
}

// Check database connection
echo "<h2>🗄️ Database Connection</h2>";
try {
    // Try to load config if exists
    if (file_exists('admin/config.php')) {
        $config = require 'admin/config.php';
        
        $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}";
        $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass']);
        
        echo "<p style='color: green;'>✅ Database connection successful!</p>";
        echo "<p><strong>Database:</strong> {$config['db']['name']}</p>";
        echo "<p><strong>Host:</strong> {$config['db']['host']}</p>";
        
        // Test query
        $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
        $admin_count = $stmt->fetchColumn();
        echo "<p><strong>Admin accounts:</strong> $admin_count</p>";
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM projects");
        $project_count = $stmt->fetchColumn();
        echo "<p><strong>Projects:</strong> $project_count</p>";
        
    } else {
        echo "<p style='color: orange;'>⚠️ Config file not found at admin/config.php</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Check email configuration
echo "<h2>📧 Email Configuration</h2>";
if (file_exists('admin/config.php')) {
    $config = require 'admin/config.php';
    echo "<p><strong>Email enabled:</strong> " . ($config['email']['enabled'] ? 'Yes' : 'No') . "</p>";
    echo "<p><strong>SMTP Host:</strong> {$config['email']['smtp_host']}</p>";
    echo "<p><strong>SMTP Port:</strong> {$config['email']['smtp_port']}</p>";
    echo "<p><strong>From Email:</strong> {$config['email']['from_email']}</p>";
    
    // Test email connection
    if ($config['email']['enabled']) {
        echo "<h3>Testing Email Connection...</h3>";
        
        // Test SMTP connection
        $smtp_host = $config['email']['smtp_host'];
        $smtp_port = $config['email']['smtp_port'];
        
        $timeout = 10;
        $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, $timeout);
        
        if ($socket) {
            echo "<p style='color: green;'>✅ SMTP connection successful to $smtp_host:$smtp_port</p>";
            fclose($socket);
        } else {
            echo "<p style='color: red;'>❌ SMTP connection failed: $errstr ($errno)</p>";
        }
    }
}

// Check file permissions
echo "<h2>📁 File Permissions</h2>";
$important_files = [
    'admin/config.php' => '600',
    'admin/bootstrap.php' => '644',
    'admin/index.php' => '644',
    'admin/uploads/' => '755'
];

foreach ($important_files as $file => $recommended_perm) {
    if (file_exists($file)) {
        $actual_perm = substr(sprintf('%o', fileperms($file)), -3);
        $status = ($actual_perm == $recommended_perm) ? "✅" : "⚠️";
        echo "<p>$status $file: $actual_perm (recommended: $recommended_perm)</p>";
    } else {
        echo "<p>❌ $file: Not found</p>";
    }
}

// Check PHP settings
echo "<h2>⚙️ PHP Settings</h2>";
echo "<p><strong>Display Errors:</strong> " . ini_get('display_errors') . "</p>";
echo "<p><strong>Error Reporting:</strong> " . ini_get('error_reporting') . "</p>";
echo "<p><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . "s</p>";
echo "<p><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</p>";
echo "<p><strong>Upload Max Filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";

// Check SSL
echo "<h2>🔒 SSL/HTTPS</h2>";
echo "<p><strong>HTTPS:</strong> " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? '✅ Enabled' : '❌ Not enabled') . "</p>";
echo "<p><strong>Server Port:</strong> " . $_SERVER['SERVER_PORT'] . "</p>";

// Recommendations
echo "<h2>💡 Recommendations</h2>";
echo "<ul>";
if (!extension_loaded('pdo_mysql')) {
    echo "<li>Install PDO MySQL extension</li>";
}
if (ini_get('display_errors') == '1') {
    echo "<li>Disable display_errors in production</li>";
}
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    echo "<li>Consider enabling SSL/HTTPS</li>";
}
echo "<li>Regular backups recommended</li>";
echo "<li>Keep software updated</li>";
echo "</ul>";

echo "</body></html>";