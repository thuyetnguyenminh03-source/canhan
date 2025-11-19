<?php
// Debug API response
ob_start();
include 'api/site.php';
$output = ob_get_clean();

$data = json_decode($output, true);

echo "=== DEBUG API RESPONSE ===\n\n";
echo "Keys in response: " . implode(', ', array_keys($data)) . "\n";
echo "Projects key exists: " . (isset($data['projects']) ? 'YES' : 'NO') . "\n";

if(isset($data['projects'])) {
  echo "Projects count: " . count($data['projects']) . "\n";
  echo "First project: " . json_encode($data['projects'][0], JSON_UNESCAPED_UNICODE) . "\n";
} else {
  echo "Available keys: " . json_encode(array_keys($data), JSON_UNESCAPED_UNICODE) . "\n";
}