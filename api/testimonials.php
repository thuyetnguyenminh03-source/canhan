<?php
$config = include __DIR__ . '/../admin/config.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
  $db = $config['db'];
  $pdo = new PDO(
    "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",
    $db['user'],
    $db['pass'],
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
  );
} catch (PDOException $e) {
  http_response_code(500); echo json_encode(['error'=>'DB connection failed']); exit;
}

$items = $pdo->query('SELECT content_vi AS quote_vi, content_en AS quote_en, author_name AS author, author_title AS role_title FROM testimonials WHERE project_id IS NULL ORDER BY sort_order, id')->fetchAll();
echo json_encode(['testimonials'=>$items], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);