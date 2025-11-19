<?php
// Test API site.php độc lập
ob_start();
include 'api/site.php';
$output = ob_get_clean();

echo "=== 🔍 TEST API site.php ===\n\n";

echo "HTTP Status: " . http_response_code() . "\n";
echo "Content Length: " . strlen($output) . " bytes\n\n";

$data = json_decode($output, true);
if ($data) {
  echo "✅ JSON hợp lệ\n";
  echo "📊 Số dự án: " . count($data['projects'] ?? []) . "\n";
  echo "📁 Các section:\n";
  foreach(['hero', 'timeline', 'services', 'skills', 'contact', 'testimonials'] as $section) {
    if(isset($data[$section])) {
      $count = is_array($data[$section]) ? count($data[$section]) : 1;
      echo "   ✓ $section: $count item(s)\n";
    }
  }
} else {
  echo "❌ JSON không hợp lệ\n";
  echo "Output preview: " . substr($output, 0, 200) . "...\n";
}