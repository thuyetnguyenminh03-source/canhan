<?php
/**
 * Configuration for myntex.io.vn hosting
 * Upload this config to replace admin/config.php on hosting
 */

return array (
  'db' => 
  array (
    // Thay đổi theo thông tin database từ cPanel
    'host' => 'localhost', // hoặc IP nếu database ở server khác
    'name' => 'myntexio_portfolio', // tên database bạn tạo trong cPanel
    'user' => 'myntexio_admin', // username database trong cPanel  
    'pass' => 'your-strong-password-here', // password bạn đặt
    'charset' => 'utf8mb4',
  ),
  'auth' => 
  array (
    'session_key' => 'myntex_admin_session',
    'default_user' => 'admin',
  ),
  'email' => 
  array (
    'enabled' => true,
    // Dùng email hosting thay vì Gmail để tránh bị chặn
    'smtp_host' => 'mail.myntex.io.vn', // SMTP của hosting
    'smtp_port' => 587,
    'smtp_username' => 'noreply@myntex.io.vn', // email tạo trong cPanel
    'smtp_password' => 'email-password-here', // password email
    'smtp_encryption' => 'tls',
    'from_email' => 'noreply@myntex.io.vn',
    'from_name' => 'Myntex Portfolio Admin',
    'admin_email' => 'thuyet.nguyenminh03@gmail.com', // email nhận thông báo
  ),
);