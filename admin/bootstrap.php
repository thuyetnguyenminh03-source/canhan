<?php
$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    exit('Missing admin/config.php');
}
$config = include $configPath;

if (session_status() === PHP_SESSION_NONE) {
    session_name($config['auth']['session_key'] ?? 'cms_admin_session');
    session_start();
}

try {
    $db = $config['db'];
    $pdo = new PDO(
        "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",
        $db['user'],
        $db['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    exit('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

function current_admin() { return $_SESSION['admin_user'] ?? null; }

function require_login() {
    if (!current_admin()) {
        header('Location: login.php');
        exit;
    }
}

function redirect_with_message($location, $message, $type = 'success') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
    header("Location: {$location}");
    exit;
}

function flash_message() {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $class = $flash['type'] === 'error' ? 'alert-danger' : 'alert-success';
        return "<div class=\"alert {$class}\">" . htmlspecialchars($flash['message']) . "</div>";
    }
    return '';
}

/* CSRF */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}
function verify_csrf_or_die() {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }
}