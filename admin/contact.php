<?php
require __DIR__ . '/bootstrap.php';
require_login();

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Lấy bản ghi liên hệ mới nhất (hoặc tạo mặc định)
$stmt = $pdo->query('SELECT * FROM contact_info ORDER BY id DESC LIMIT 1');
$contact = $stmt->fetch() ?: [
  'id' => null,
  'phone' => '',
  'email' => '',
  'address_vi' => '',
  'address_en' => '',
  'facebook_url' => '',
  'instagram_url' => '',
  'tiktok_url' => '',
  'zalo_url' => '',
  'whatsapp_url' => '',
  'map_embed' => ''
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    verify_csrf_or_die();

    $data = [
      'phone'         => trim($_POST['phone'] ?? ''),
      'email'         => trim($_POST['email'] ?? ''),
      'address_vi'    => trim($_POST['address_vi'] ?? ''),
      'address_en'    => trim($_POST['address_en'] ?? ''),
      'facebook_url'  => trim($_POST['facebook_url'] ?? ''),
      'instagram_url' => trim($_POST['instagram_url'] ?? ''),
      'tiktok_url'    => trim($_POST['tiktok_url'] ?? ''),
      'zalo_url'      => trim($_POST['zalo_url'] ?? ''),
      'whatsapp_url'  => trim($_POST['whatsapp_url'] ?? ''),
      'map_embed'     => trim($_POST['map_embed'] ?? ''),
    ];

    if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $error = 'Email không hợp lệ.';
    }

    if (!$error) {
      // Luôn UPDATE bản ghi mới nhất, nếu không có thì INSERT
      if (!empty($contact['id'])) {
        $sql = 'UPDATE contact_info SET phone=:phone,email=:email,address_vi=:address_vi,address_en=:address_en,facebook_url=:facebook_url,instagram_url=:instagram_url,tiktok_url=:tiktok_url,zalo_url=:zalo_url,whatsapp_url=:whatsapp_url,map_embed=:map_embed WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $data['id'] = $contact['id'];
        $stmt->execute($data);
        redirect_with_message('contact.php', 'Đã cập nhật thông tin liên hệ.');
      } else {
        $sql = 'INSERT INTO contact_info (phone,email,address_vi,address_en,facebook_url,instagram_url,tiktok_url,zalo_url,whatsapp_url,map_embed) VALUES (:phone,:email,:address_vi,:address_en,:facebook_url,:facebook_url,:instagram_url,:tiktok_url,:zalo_url,:whatsapp_url,:map_embed)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        redirect_with_message('contact.php', 'Đã tạo thông tin liên hệ.');
      }
    }
  } catch (Throwable $e) {
    $error = 'Lỗi: ' . $e->getMessage();
  }
}
$pageTitle = 'Quản lý Liên hệ';
require __DIR__ . '/_layout-header.php';
?>

      <!-- Header -->
      <div class="glass-card p-6 mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent mb-2">Quản lý Liên hệ</h1>
            <p class="text-gray-600">Cập nhật thông tin liên hệ và mạng xã hội</p>
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

      <!-- Form liên hệ -->
      <form method="post" class="glass-card p-8 mb-8">
        <h2 class="text-xl font-bold mb-6 flex items-center">
          <i class="fas fa-address-book text-teal-400 mr-3"></i>
          Thông tin liên hệ
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?= csrf_field(); ?>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($contact['phone']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="+84 9xx xxx xxx">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($contact['email']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="you@example.com">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ (VI)</label>
            <textarea name="address_vi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="Số nhà, phường/xã, quận/huyện, tỉnh/thành"><?= htmlspecialchars($contact['address_vi']) ?></textarea>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ (EN)</label>
            <textarea name="address_en" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="Street, ward, district, city"><?= htmlspecialchars($contact['address_en']) ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Facebook URL</label>
            <input type="url" name="facebook_url" value="<?= htmlspecialchars($contact['facebook_url']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="https://facebook.com/...">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Instagram URL</label>
            <input type="url" name="instagram_url" value="<?= htmlspecialchars($contact['instagram_url']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="https://instagram.com/...">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">TikTok URL</label>
            <input type="url" name="tiktok_url" value="<?= htmlspecialchars($contact['tiktok_url']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="https://tiktok.com/@...">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Zalo URL</label>
            <input type="url" name="zalo_url" value="<?= htmlspecialchars($contact['zalo_url']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="https://zalo.me/...">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">WhatsApp URL</label>
            <input type="url" name="whatsapp_url" value="<?= htmlspecialchars($contact['whatsapp_url']) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="https://wa.me/849...">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Google Map Embed</label>
            <textarea name="map_embed" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all" placeholder="<iframe ...></iframe>"><?= htmlspecialchars($contact['map_embed']) ?></textarea>
            <p class="text-xs text-gray-500 mt-2 flex items-center">
              <i class="fas fa-info-circle mr-1"></i>
              Dán mã nhúng iframe từ Google Maps (tùy chọn)
            </p>
          </div>

          <div class="md:col-span-2 flex items-center space-x-3 pt-4">
            <button type="submit" class="btn-elegant inline-flex items-center">
              <i class="fas fa-save mr-2"></i>
              Lưu thông tin liên hệ
            </button>
          </div>
        </div>
      </form>

      <!-- Xem nhanh thông tin hiện tại -->
      <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-eye text-cyan-400 mr-3"></i>
            Thông tin hiện tại
          </h2>
          <span class="px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl font-semibold shadow-lg">
            <i class="fas fa-info-circle mr-2"></i>
            Xem nhanh
          </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
              <i class="fas fa-phone text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Phone</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['phone']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
              <i class="fas fa-envelope text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Email</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['email']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
              <i class="fas fa-map-marker-alt text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Địa chỉ (VI)</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['address_vi']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
              <i class="fas fa-map-marker-alt text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Địa chỉ (EN)</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['address_en']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
              <i class="fab fa-facebook text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Facebook</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['facebook_url']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center">
              <i class="fab fa-instagram text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Instagram</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['instagram_url']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-black to-gray-600 rounded-lg flex items-center justify-center">
              <i class="fab fa-tiktok text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">TikTok</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['tiktok_url']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
              <i class="fas fa-comment text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Zalo</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['zalo_url']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
              <i class="fab fa-whatsapp text-white text-sm"></i>
            </div>
            <div>
              <p class="font-semibold text-gray-800">WhatsApp</p>
              <p class="text-gray-600"><?= htmlspecialchars($contact['whatsapp_url']) ?: 'Chưa cập nhật' ?></p>
            </div>
          </div>
        </div>
        <?php if (!empty($contact['map_embed'])): ?>
          <div class="mt-8">
            <div class="text-sm font-medium text-gray-700 mb-4 flex items-center">
              <i class="fas fa-map-marked-alt mr-2 text-teal-400"></i>
              Bản đồ
            </div>
            <div class="aspect-video w-full overflow-hidden rounded-xl border-2 border-gray-200 shadow-lg">
              <?= $contact['map_embed'] ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

<?php require __DIR__ . '/_layout-footer.php'; ?>
