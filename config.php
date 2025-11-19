<?php
return [
  'db' => [
    'host' => 'localhost',
    'name' => 'acegiove_portfolio',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
  ],
  'auth' => [
    'session_key' => 'myntex_admin_session',
    'default_user' => 'admin'
  ],
  'security' => [
    'csrf_token_name' => 'csrf_token',
    'session_timeout' => 3600,
    'max_login_attempts' => 5,
    'lockout_time' => 900
  ],
  'email' => [
    'enabled' => true,
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_username' => 'thuyet.nguyenminh03@gmail.com',
    'smtp_password' => 'cksi zmxe jgav dixr',
    'smtp_encryption' => 'tls',
    'from_email' => 'thuyet.nguyenminh03@gmail.com',
    'from_name' => 'Portfolio Admin',
    'admin_email' => 'thuyet.nguyenminh03@gmail.com'
  ]
];