<?php
/**
 * Secure Configuration File
 * 
 * IMPORTANT: 
 * 1. Move this file outside of web root if possible
 * 2. Set proper file permissions (600 or 644)
 * 3. Use environment variables for sensitive data
 * 4. Never commit credentials to version control
 */

// Load environment variables if available
$env = function($key, $default = null) {
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
};

return [
  'db' => [
    'host' => $env('DB_HOST', 'localhost'),
    'name' => $env('DB_NAME', 'your_database_name'),
    'user' => $env('DB_USER', 'your_database_user'),
    'pass' => $env('DB_PASS', 'your_strong_password'),
    'charset' => 'utf8mb4'
  ],
  'auth' => [
    'session_key' => $env('SESSION_KEY', 'your_unique_session_key_' . bin2hex(random_bytes(16))),
    'default_user' => $env('DEFAULT_USER', 'admin'),
    'session_timeout' => 3600, // 1 hour
    'max_attempts' => 5,
    'lockout_duration' => 900 // 15 minutes
  ],
  'security' => [
    'csrf_token_name' => 'csrf_token',
    'hash_algorithm' => PASSWORD_DEFAULT,
    'hash_cost' => 12,
    'encryption_key' => $env('ENCRYPTION_KEY', bin2hex(random_bytes(32)))
  ],
  'paths' => [
    'upload_dir' => __DIR__ . '/uploads/',
    'log_dir' => __DIR__ . '/logs/',
    'temp_dir' => sys_get_temp_dir()
  ]
];