<?php
require __DIR__ . '/bootstrap.php';

// Xóa session và đăng xuất
session_destroy();
unset($_SESSION);

// Chuyển hướng về trang login
header('Location: login.php');
exit;
