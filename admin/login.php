<?php
require __DIR__ . '/bootstrap.php';

if (current_admin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_die();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_user'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Sai tài khoản hoặc mật khẩu.';
    } else {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    }
}
$pageTitle = 'Đăng nhập Admin';
require __DIR__ . '/_layout-header.php';
?>

      <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
          <div class="glass-card p-8">
            <div class="text-center">
              <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                <i class="fas fa-crown text-white text-2xl"></i>
              </div>
              <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Đăng nhập Admin
              </h2>
              <p class="text-gray-600">Vui lòng nhập thông tin đăng nhập</p>
            </div>

            <?php if ($error): ?>
              <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-triangle mr-2"></i>
                  <?= htmlspecialchars($error) ?>
                </div>
              </div>
            <?php endif; ?>

            <form method="post" class="mt-8 space-y-6">
              <?= csrf_field(); ?>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tài khoản</label>
                <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Nhập tài khoản" autofocus required>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu</label>
                <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Nhập mật khẩu" required>
              </div>

              <div class="pt-4">
                <button type="submit" class="w-full btn-elegant flex items-center justify-center">
                  <i class="fas fa-sign-in-alt mr-2"></i>
                  Đăng nhập
                </button>
              </div>
            </form>

            <div class="mt-6 text-center">
              <p class="text-sm text-gray-500">
                Tài khoản mặc định: <strong>admin</strong> / Mật khẩu: <strong>admin123</strong>
              </p>
            </div>
          </div>
        </div>
      </div>

<?php require __DIR__ . '/_layout-footer.php'; ?>
