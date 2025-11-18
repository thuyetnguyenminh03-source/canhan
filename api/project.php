<?php
$config = include __DIR__ . '/../admin/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://your-frontend-domain.com');

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

$slug = preg_replace('/[^a-z0-9_\\-]/i', '', $_GET['slug'] ?? '');
if (!$slug) { http_response_code(400); echo json_encode(['error'=>'Missing slug']); exit; }

$stmt = $pdo->prepare('SELECT id, title_vi, title_en, slug, description_vi, description_en, sort_order,
           objective_vi, objective_en, challenge_vi, challenge_en,
           strategy_vi, strategy_en, workflow_vi, workflow_en,
           meta_role, meta_time, meta_tools,
           kpi1, kpi2, kpi3, kpi4 FROM projects WHERE slug=? LIMIT 1'); $stmt->execute([$slug]); $project = $stmt->fetch();
if (!$project) { http_response_code(404); echo json_encode(['error'=>'Project not found']); exit; }

$mediaStmt = $pdo->prepare('SELECT section,url,title,sort_order FROM project_media WHERE project_id=? ORDER BY section,sort_order,id');
$mediaStmt->execute([$project['id']]);
$media = ['cover'=>[],'gallery'=>[],'policy'=>[],'floor'=>[],'recruitment'=>[]];
foreach ($mediaStmt as $item) { $media[$item['section']][] = $item; }

$testimonials = $pdo->prepare('SELECT content_vi as quote_vi, content_en as quote_en, author_name as author, author_title as role_title FROM testimonials WHERE project_id=? ORDER BY sort_order, id');
$testimonials->execute([$project['id']]); $testi = $testimonials->fetchAll();

echo json_encode(['project'=>$project,'media'=>$media,'testimonials'=>$testi], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);