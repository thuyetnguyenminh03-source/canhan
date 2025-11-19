<?php
/**
 * Health Check Script for myntex.io.vn
 * Kiểm tra nhanh tình trạng website
 */

header('Content-Type: application/json; charset=utf-8');

$status = [
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => []
];

// 1. Check config file
$status['checks']['config_file'] = file_exists(__DIR__ . '/config.php') 
    ? ['status' => 'OK', 'message' => 'config.php exists'] 
    : ['status' => 'ERROR', 'message' => 'config.php missing'];

// 2. Check database connection
try {
    $config = include __DIR__ . '/config.php';
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}",
        $config['db']['user'],
        $config['db']['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $status['checks']['database'] = ['status' => 'OK', 'message' => 'Connected to ' . $config['db']['name']];
} catch (Exception $e) {
    $status['checks']['database'] = ['status' => 'ERROR', 'message' => $e->getMessage()];
}

// 3. Check database tables
if (isset($pdo)) {
    $tables_needed = ['hero', 'timeline', 'services', 'skills', 'contact_info', 'projects', 'testimonials', 'admins'];
    $tables_found = [];
    try {
        $result = $pdo->query("SHOW TABLES");
        $tables_found = $result->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {
        // Silently fail
    }
    
    $missing = array_diff($tables_needed, $tables_found);
    $status['checks']['tables'] = empty($missing) 
        ? ['status' => 'OK', 'message' => 'All tables exist: ' . implode(', ', $tables_found)]
        : ['status' => 'WARNING', 'message' => 'Missing tables: ' . implode(', ', $missing)];
}

// 4. Check admin user
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
        $count = $stmt->fetchColumn();
        $status['checks']['admin_user'] = $count > 0 
            ? ['status' => 'OK', 'message' => $count . ' admin user(s) exist']
            : ['status' => 'ERROR', 'message' => 'No admin users in database'];
    } catch (Exception $e) {
        $status['checks']['admin_user'] = ['status' => 'ERROR', 'message' => $e->getMessage()];
    }
}

// 5. Check API endpoints
$status['checks']['api_site'] = [
    'status' => 'INFO',
    'message' => 'API endpoint: /api/site.php',
    'url' => $_SERVER['HTTP_HOST'] . '/api/site.php'
];

$status['checks']['api_project'] = [
    'status' => 'INFO', 
    'message' => 'API endpoint: /api/project.php?slug=project-slug',
    'url' => $_SERVER['HTTP_HOST'] . '/api/project.php'
];

// 6. Check admin panel
$status['checks']['admin_login'] = [
    'status' => 'INFO',
    'message' => 'Admin login: /admin/login.php',
    'url' => $_SERVER['HTTP_HOST'] . '/admin/login.php'
];

// 7. File permissions
$status['checks']['upload_dir'] = is_writable(__DIR__ . '/uploads')
    ? ['status' => 'OK', 'message' => '/uploads directory is writable']
    : ['status' => 'WARNING', 'message' => '/uploads directory not writable'];

// Summary
$errors = array_filter($status['checks'], fn($c) => ($c['status'] ?? '') === 'ERROR');
$warnings = array_filter($status['checks'], fn($c) => ($c['status'] ?? '') === 'WARNING');

$status['summary'] = [
    'total_checks' => count($status['checks']),
    'passed' => count(array_filter($status['checks'], fn($c) => ($c['status'] ?? '') === 'OK')),
    'warnings' => count($warnings),
    'errors' => count($errors),
    'overall_status' => empty($errors) ? 'HEALTHY' : 'UNHEALTHY'
];

echo json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
