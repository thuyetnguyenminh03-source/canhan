<?php
require __DIR__ . '/bootstrap.php';
require_login();

$stmt = $pdo->query('SELECT * FROM hero ORDER BY id DESC LIMIT 1');
$hero = $stmt->fetch() ?: [
  'greeting_vi' => '', 'greeting_en' => '', 'headline' => '', 'subhead' => '',
  'typewriter_text' => '', 'cv_link' => '', 'behance_link' => '',
  'instagram_link' => '', 'tiktok_link' => ''
];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $data = [
    'greeting_vi' => trim($_POST['greeting_vi'] ?? ''),
    'greeting_en' => trim($_POST['greeting_en'] ?? ''),
    'headline' => trim($_POST['headline'] ?? ''),
    'subhead' => trim($_POST['subhead'] ?? ''),
    'typewriter_text' => trim($_POST['typewriter_text'] ?? ''),
    'cv_link' => trim($_POST['cv_link'] ?? ''),
    'behance_link' => trim($_POST['behance_link'] ?? ''),
    'instagram_link' => trim($_POST['instagram_link'] ?? ''),
    'tiktok_link' => trim($_POST['tiktok_link'] ?? '')
  ];
  if (!empty($hero['id'])) {
    $sql = 'UPDATE hero SET greeting_vi=:greeting_vi,greeting_en=:greeting_en,headline=:headline,subhead=:subhead,typewriter_text=:typewriter_text,cv_link=:cv_link,behance_link=:behance_link,instagram_link=:instagram_link,tiktok_link=:tiktok_link WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $data['id'] = $hero['id'];
    $stmt->execute($data);
    redirect_with_message('hero-edit.php', 'Đã cập nhật Hero.');
  } else {
    $sql = 'INSERT INTO hero (greeting_vi,greeting_en,headline,subhead,typewriter_text,cv_link,behance_link,instagram_link,tiktok_link) VALUES (:greeting_vi,:greeting_en,:headline,:subhead,:typewriter_text,:cv_link,:behance_link,:instagram_link,:tiktok_link)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    redirect_with_message('hero-edit.php', 'Đã tạo Hero.');
  }
}
$pageTitle = 'Quản lý Hero Section';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent mb-2">Quản lý Hero Section</h1>
            <p class="text-gray-600">Cập nhật nội dung phần giới thiệu chính của website</p>
          </div>
          <div class="flex items-center space-x-3">
            <button onclick="toggleTheme()" class="theme-toggle p-3">
              <i class="fas fa-moon text-gray-600"></i>
            </button>
            <a href="../index.html" target="_blank" class="btn-elegant inline-flex items-center">
              <i class="fas fa-external-link-alt mr-2"></i> Xem website
            </a>
          </div>
        </div>
      </div>

      <?= flash_message(); ?>
      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- Hero Form -->
      <div class="glass-card p-8">
        <h2 class="text-xl font-bold mb-6 flex items-center">
          <i class="fas fa-star text-emerald-400 mr-3"></i>
          Nội dung Hero Section
        </h2>
        <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?= csrf_field(); ?>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Greeting (VI)</label>
            <input type="text" name="greeting_vi" value="<?= htmlspecialchars($hero['greeting_vi']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Xin chào!">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Greeting (EN)</label>
            <input type="text" name="greeting_en" value="<?= htmlspecialchars($hero['greeting_en']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Hello!">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Headline</label>
            <input type="text" name="headline" value="<?= htmlspecialchars($hero['headline']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Tên của bạn">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Subhead</label>
            <input type="text" name="subhead" value="<?= htmlspecialchars($hero['subhead']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Chức danh hoặc mô tả ngắn">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Typewriter Text</label>
            <textarea name="typewriter_text" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Designer, Developer, Photographer... (mỗi dòng một từ)"><?= htmlspecialchars($hero['typewriter_text']) ?></textarea>
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-info-circle mr-1"></i>
              Mỗi dòng là một từ sẽ hiển thị theo hiệu ứng typewriter
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">CV Link</label>
            <input type="url" name="cv_link" value="<?= htmlspecialchars($hero['cv_link']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="https://... hoặc /assets/cv.pdf">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Behance Link</label>
            <input type="url" name="behance_link" value="<?= htmlspecialchars($hero['behance_link']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="https://behance.net/...">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Instagram Link</label>
            <input type="url" name="instagram_link" value="<?= htmlspecialchars($hero['instagram_link']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="https://instagram.com/...">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">TikTok Link</label>
            <input type="url" name="tiktok_link" value="<?= htmlspecialchars($hero['tiktok_link']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="https://tiktok.com/@...">
          </div>

          <div class="md:col-span-2 flex items-center space-x-3 pt-4">
            <button type="submit" class="btn-elegant inline-flex items-center">
              <i class="fas fa-save mr-2"></i>
              Lưu Hero Section
            </button>
          </div>
        </form>
      </div>

      <!-- Preview Section -->
      <div class="glass-card p-6 mt-8">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-eye text-teal-400 mr-3"></i>
            Xem trước nội dung hiện tại
          </h2>
          <span class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-semibold shadow-lg">
            <i class="fas fa-desktop mr-2"></i>
            Preview
          </span>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-200">
          <div class="space-y-4">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center">
                <i class="fas fa-hand-wave text-white"></i>
              </div>
              <div>
                <p class="text-sm text-gray-600">Greeting</p>
                <p class="font-semibold text-gray-800">
                  <?= htmlspecialchars($hero['greeting_vi'] ?: 'Chưa cập nhật') ?> /
                  <?= htmlspecialchars($hero['greeting_en'] ?: 'Not updated') ?>
                </p>
              </div>
            </div>

            <div class="border-t border-emerald-200 pt-4">
              <p class="text-sm text-gray-600 mb-2">Headline</p>
              <h1 class="text-2xl font-bold text-gray-900 mb-2">
                <?= htmlspecialchars($hero['headline'] ?: 'Chưa cập nhật') ?>
              </h1>
              <p class="text-lg text-gray-700">
                <?= htmlspecialchars($hero['subhead'] ?: 'Chưa cập nhật') ?>
              </p>
            </div>

            <?php if ($hero['typewriter_text']): ?>
              <div class="border-t border-emerald-200 pt-4">
                <p class="text-sm text-gray-600 mb-2">Typewriter Text</p>
                <div class="bg-white rounded-lg p-3 border">
                  <code class="text-sm text-gray-800">
                    <?= nl2br(htmlspecialchars($hero['typewriter_text'])) ?>
                  </code>
                </div>
              </div>
            <?php endif; ?>

            <div class="border-t border-emerald-200 pt-4">
              <p class="text-sm text-gray-600 mb-3">Social Links</p>
              <div class="flex flex-wrap gap-3">
                <?php if ($hero['cv_link']): ?>
                  <a href="<?= htmlspecialchars($hero['cv_link']) ?>" target="_blank" class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border border-gray-200 hover:border-emerald-300 transition-colors">
                    <i class="fas fa-file-pdf text-red-500"></i>
                    <span class="text-sm font-medium">CV</span>
                  </a>
                <?php endif; ?>
                <?php if ($hero['behance_link']): ?>
                  <a href="<?= htmlspecialchars($hero['behance_link']) ?>" target="_blank" class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border border-gray-200 hover:border-emerald-300 transition-colors">
                    <i class="fab fa-behance text-blue-600"></i>
                    <span class="text-sm font-medium">Behance</span>
                  </a>
                <?php endif; ?>
                <?php if ($hero['instagram_link']): ?>
                  <a href="<?= htmlspecialchars($hero['instagram_link']) ?>" target="_blank" class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border border-gray-200 hover:border-emerald-300 transition-colors">
                    <i class="fab fa-instagram text-pink-500"></i>
                    <span class="text-sm font-medium">Instagram</span>
                  </a>
                <?php endif; ?>
                <?php if ($hero['tiktok_link']): ?>
                  <a href="<?= htmlspecialchars($hero['tiktok_link']) ?>" target="_blank" class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border border-gray-200 hover:border-emerald-300 transition-colors">
                    <i class="fab fa-tiktok text-black"></i>
                    <span class="text-sm font-medium">TikTok</span>
                  </a>
                <?php endif; ?>
                <?php if (!$hero['cv_link'] && !$hero['behance_link'] && !$hero['instagram_link'] && !$hero['tiktok_link']): ?>
                  <p class="text-gray-500 text-sm italic">Chưa có liên kết nào</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

<?php require __DIR__ . '/_layout-footer.php'; ?>
