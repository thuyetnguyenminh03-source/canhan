<?php
/**
 * Example configuration file
 * Copy this to config.php and update with your actual credentials
 * Keep config.php outside of web root if possible
 */

return [
  'db' => [
    'host' => 'localhost',
    'name' => 'your_database_name',
    'user' => 'your_database_user', 
    'pass' => 'your_strong_password',
    'charset' => 'utf8mb4'
  ],
  'auth' => [
    'session_key' => 'your_unique_session_key_' . bin2hex(random_bytes(16)),
    'default_user' => 'admin'
  ],
  'security' => [
    'csrf_token_name' => 'csrf_token',
    'session_timeout' => 3600, // 1 hour
    'max_login_attempts' => 5,
    'lockout_time' => 900 // 15 minutes
  ]
];