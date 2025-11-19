<?php
$config = include __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // cho phép truy cập từ mọi domain

try {
  $db = $config['db'];
  $pdo = new PDO(
    "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",
    $db['user'],
    $db['pass'],
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
  );
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error'=>'DB connection failed']); exit;
}

$hero = $pdo->query('SELECT * FROM hero ORDER BY id DESC LIMIT 1')->fetch();
$timeline = $pdo->query('SELECT * FROM timeline ORDER BY sort_order, id')->fetchAll();
$services = $pdo->query('SELECT * FROM services ORDER BY sort_order, id')->fetchAll();
$skills = $pdo->query('SELECT * FROM skills ORDER BY sort_order, id')->fetchAll();
$contact = $pdo->query('SELECT * FROM contact_info ORDER BY id DESC LIMIT 1')->fetch();
$footer = $pdo->query('SELECT * FROM footer_links ORDER BY section, sort_order, id')->fetchAll();
$projects = $pdo->query('SELECT p.id, p.slug, p.title_vi, p.title_en,
  COALESCE((SELECT url FROM project_media WHERE project_id=p.id AND section="cover" ORDER BY sort_order, id LIMIT 1), "") AS cover_url
  FROM projects p ORDER BY sort_order, p.id')->fetchAll();
$testimonials = $pdo->query('SELECT * FROM testimonials WHERE project_id IS NULL ORDER BY sort_order, id')->fetchAll();

echo json_encode([
  'hero'=>$hero,'timeline'=>$timeline,'services'=>$services,'skills'=>$skills,
  'contact'=>$contact,'footer'=>$footer,'projects'=>$projects,'testimonials'=>$testimonials
], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);