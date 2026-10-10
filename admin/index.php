<?php
/**
 * TikRPM Admin – Login Page
 */
require_once '../includes/config.php';
require_once '../includes/db.php';

// Start session
session_name(ADMIN_SESSION_NAME);
session_start();

// Already logged in → dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_PATH . '/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    $token = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        $error = 'Security check failed. Please refresh and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $error = 'Please enter your username and password.';
        } else {
            $admin = db_row(
                'SELECT id, username, password FROM admin_users WHERE username = ? LIMIT 1',
                [$username]
            );

            if ($admin && password_verify($password, $admin['password'])) {
                // Regenerate session ID on login (session fixation protection)
                session_regenerate_id(true);
                $_SESSION['admin_id']       = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_login_at'] = time();
                // New CSRF token for admin session
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                header('Location: ' . BASE_PATH . '/admin/dashboard.php');
                exit;
            } else {
                // Intentional small delay to slow brute-force
                usleep(400000);
                $error = 'Invalid username or password.';
            }
        }
    }
}

// Generate CSRF token for login form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login – <?= e(SITE_NAME) ?></title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="/tikrpm/assets/css/style.css">
  <style>
    body { background: #0f0f1a; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .login-card {
      background: #fff;
      border-radius: 16px;
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 20px 60px rgba(0,0,0,.4);
    }
    .login-logo { text-align: center; margin-bottom: 1.75rem; }
    .login-logo .brand-name { font-size: 1.6rem; font-weight: 800; color: #1a1a2e; }
    .login-logo .brand-name span { color: #EE1D52; }
    .login-logo small { display: block; color: #6b7280; font-size: .78rem; margin-top: .2rem; }
  </style>
</head>
<body>

<div class="login-card">
  <div class="login-logo">
    <div class="login-logo">
      <div class="brand-name">Tik<span>RPM</span></div>
      <small>Admin Panel</small>
    </div>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-error" role="alert"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="<?= BASE_PATH ?>/admin/index.php" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

    <div class="form-group mb-3">
      <label class="form-label" for="username">Username</label>
      <input type="text" id="username" name="username"
             class="form-control" placeholder="admin"
             autocomplete="username" required autofocus
             value="<?= e($_POST['username'] ?? '') ?>">
    </div>

    <div class="form-group mb-3">
      <label class="form-label" for="password">Password</label>
      <input type="password" id="password" name="password"
             class="form-control" placeholder="••••••••"
             autocomplete="current-password" required>
    </div>

    <button type="submit" class="btn btn-primary w-full btn-lg" style="width:100%;">
      Sign In →
    </button>
  </form>

  <p style="text-align:center;margin-top:1.25rem;">
    <a href="<?= BASE_PATH ?>/" style="font-size:.82rem;color:var(--text-muted);">← Back to site</a>
  </p>
</div>

</body>
</html>
