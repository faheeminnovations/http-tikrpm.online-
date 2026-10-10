<?php
/**
 * Admin Sidebar — included in every admin page.
 * Requires $admin_username to be set by auth.php.
 */
$current = basename($_SERVER['PHP_SELF'], '.php');
function admin_active(string $page): string {
    global $current;
    return ($current === $page) ? 'active' : '';
}
?>
<aside class="admin-sidebar">
  <div class="admin-logo">
    <a href="<?= BASE_PATH ?>/admin/dashboard.php" style="text-decoration:none;">
      <span style="font-size:1.3rem;font-weight:800;color:#fff;">
        Tik<span style="color:#EE1D52;">RPM</span>
      </span>
      <small style="display:block;color:rgba(255,255,255,.4);font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;">Admin</small>
    </a>
  </div>

  <nav class="admin-nav" aria-label="Admin navigation">
    <a href="<?= BASE_PATH ?>/admin/dashboard.php" class="admin-nav-link <?= admin_active('dashboard') ?>">
      <span aria-hidden="true">📊</span> Posts
    </a>
    <a href="<?= BASE_PATH ?>/admin/post-form.php" class="admin-nav-link <?= admin_active('post-form') ?>">
      <span aria-hidden="true">✏️</span> New Post
    </a>
    <a href="<?= BASE_PATH ?>/admin/rpm-rates.php" class="admin-nav-link <?= admin_active('rpm-rates') ?>">
      <span aria-hidden="true">📈</span> RPM Rates
    </a>
    <a href="<?= BASE_PATH ?>/" target="_blank" class="admin-nav-link">
      <span aria-hidden="true">🌐</span> View Site
    </a>
  </nav>

  <div class="admin-sidebar-footer">
    <div style="font-size:.8rem;color:rgba(255,255,255,.45);margin-bottom:.6rem;">
      Logged in as <strong style="color:rgba(255,255,255,.7);"><?= e($admin_username) ?></strong>
    </div>
    <a href="<?= BASE_PATH ?>/admin/logout.php" class="btn btn-sm" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6);width:100%;justify-content:center;">
      Sign Out
    </a>
  </div>
</aside>
