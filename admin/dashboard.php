<?php
/**
 * TikRPM Admin – Dashboard
 */
require_once __DIR__ . '/auth.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$posts = db_rows(
    "SELECT id, slug, title, tag, featured, published,
            DATE_FORMAT(created_at, '%d %b %Y') AS date
     FROM blog_posts ORDER BY created_at DESC"
);

$total      = count($posts);
$published  = count(array_filter($posts, fn($p) => $p['published']));
$draft      = $total - $published;
$feat_count = count(array_filter($posts, fn($p) => $p['featured']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard – TikRPM Admin</title>
<meta name="robots" content="noindex,nofollow">
  <link rel="icon" type="image/svg+xml" href="/tikrpm/assets/images/favicon.svg">
  <link rel="alternate icon" type="image/png" href="/tikrpm/assets/images/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:#f1f3f6;min-height:100vh;display:flex;flex-direction:column}
a{text-decoration:none;color:inherit}

/* ── Top bar ── */
.topbar{background:#0f0f1a;color:#fff;padding:.85rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.topbar-brand{font-size:1.2rem;font-weight:800}
.topbar-brand span{color:#EE1D52}
.topbar-nav{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
.topbar-nav a{font-size:.82rem;color:rgba(255,255,255,.6);background:rgba(255,255,255,.08);padding:.35rem .75rem;border-radius:6px;transition:all .15s}
.topbar-nav a:hover{background:rgba(255,255,255,.15);color:#fff}
.topbar-nav a.active{background:#EE1D52;color:#fff}

/* ── Layout ── */
.wrap{padding:1.5rem;max-width:1100px;margin:0 auto;width:100%}

/* ── Stats ── */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
@media(max-width:640px){.stats{grid-template-columns:repeat(2,1fr)}}
.stat{background:#fff;border:1px solid #e2e6ea;border-radius:12px;padding:1.25rem 1.5rem;text-align:center}
.stat-num{font-size:2rem;font-weight:800;line-height:1}
.stat-lbl{font-size:.72rem;color:#6b7280;text-transform:uppercase;letter-spacing:.07em;margin-top:.3rem}

/* ── Flash ── */
.flash{padding:.85rem 1.1rem;border-radius:8px;margin-bottom:1.25rem;font-size:.9rem;font-weight:500}
.flash-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
.flash-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}

/* ── Card ── */
.card{background:#fff;border:1px solid #e2e6ea;border-radius:12px;overflow:hidden}
.card-head{padding:1rem 1.25rem;border-bottom:1px solid #e2e6ea;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.card-head h2{font-size:1rem;font-weight:700}

/* ── Table ── */
.tbl-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:.88rem}
thead th{background:#0f0f1a;color:#fff;padding:.7rem 1rem;text-align:left;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
tbody tr{border-bottom:1px solid #f1f3f6;transition:background .1s}
tbody tr:hover{background:#f8f9fb}
tbody tr:last-child{border-bottom:none}
td{padding:.75rem 1rem;vertical-align:middle}
.slug-hint{font-size:.72rem;color:#9ca3af;display:block;margin-top:.15rem}

/* ── Badges ── */
.badge{display:inline-block;padding:.2rem .55rem;border-radius:100px;font-size:.7rem;font-weight:700}
.badge-pub{background:#ecfdf5;color:#065f46}
.badge-draft{background:#fffbeb;color:#92400e}
.badge-feat{background:#fff0f3;color:#EE1D52}

/* ── Buttons ── */
.btn{display:inline-flex;align-items:center;gap:.3rem;padding:.4rem .9rem;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all .15s;white-space:nowrap}
.btn-primary{background:#EE1D52;color:#fff}
.btn-primary:hover{background:#c4143f;color:#fff}
.btn-teal{background:#69C9D0;color:#fff}
.btn-teal:hover{background:#3eaeb6;color:#fff}
.btn-ghost{background:#f1f3f6;color:#374151;border:1px solid #e2e6ea}
.btn-ghost:hover{background:#e2e6ea}
.btn-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
.btn-danger:hover{background:#fee2e2}
.btn-sm{padding:.3rem .7rem;font-size:.75rem}
</style>
</head>
<body>

<!-- Top bar -->
<div class="topbar">
  <div class="topbar-brand">Tik<span>RPM</span> <span style="color:rgba(255,255,255,.3);font-weight:400;font-size:.9rem;">Admin</span></div>
  <div class="topbar-nav">
    <a href="dashboard.php" class="active">📊 Posts</a>
    <a href="post-form.php">✏️ New Post</a>
    <a href="rpm-rates.php">📈 RPM Rates</a>
    <a href="<?= BASE_PATH ?>/" target="_blank">🌐 View Site</a>
    <a href="logout.php" style="background:rgba(238,29,82,.25);color:#ff8fa8;">Sign Out</a>
  </div>
</div>

<div class="wrap">

  <!-- Stats -->
  <div class="stats">
    <div class="stat"><div class="stat-num"><?= $total ?></div><div class="stat-lbl">Total Posts</div></div>
    <div class="stat"><div class="stat-num" style="color:#10b981"><?= $published ?></div><div class="stat-lbl">Published</div></div>
    <div class="stat"><div class="stat-num" style="color:#f59e0b"><?= $draft ?></div><div class="stat-lbl">Drafts</div></div>
    <div class="stat"><div class="stat-num" style="color:#EE1D52"><?= $feat_count ?></div><div class="stat-lbl">Featured</div></div>
  </div>

  <!-- Flash -->
  <?php if ($flash): ?>
    <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
  <?php endif; ?>

  <!-- Posts table -->
  <div class="card">
    <div class="card-head">
      <h2>All Blog Posts</h2>
      <a href="post-form.php" class="btn btn-primary">+ New Post</a>
    </div>

    <?php if (empty($posts)): ?>
      <p style="padding:2rem;text-align:center;color:#6b7280;">No posts yet. <a href="post-form.php" style="color:#EE1D52;font-weight:600;">Create your first post →</a></p>
    <?php else: ?>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Title & Slug</th>
            <th>Tag</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $i => $p): ?>
          <tr>
            <td style="color:#9ca3af;font-size:.78rem;"><?= $p['id'] ?></td>
            <td>
              <strong style="font-size:.9rem;"><?= e($p['title']) ?></strong>
              <span class="slug-hint">/blog/<?= e($p['slug']) ?></span>
            </td>
            <td><span class="badge" style="background:#f1f3f6;color:#374151;"><?= e($p['tag']) ?></span></td>
            <td style="white-space:nowrap;color:#6b7280;font-size:.82rem;"><?= e($p['date']) ?></td>
            <td style="white-space:nowrap;">
              <?php if ($p['published']): ?>
                <span class="badge badge-pub">Published</span>
              <?php else: ?>
                <span class="badge badge-draft">Draft</span>
              <?php endif; ?>
              <?php if ($p['featured']): ?>
                <span class="badge badge-feat" style="margin-left:.3rem;">⭐ Featured</span>
              <?php endif; ?>
            </td>
            <td>
              <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                <a href="<?= BASE_PATH ?>/blog/<?= e($p['slug']) ?>" target="_blank" class="btn btn-ghost btn-sm">View</a>
                <a href="post-form.php?id=<?= (int)$p['id'] ?>" class="btn btn-teal btn-sm">Edit</a>
                <form method="POST" action="post-delete.php" style="display:inline;" onsubmit="return confirm('Delete &quot;<?= e(addslashes($p['title'])) ?>&quot;? This cannot be undone.')">
                  <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                  <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

</div><!-- /wrap -->
</body>
</html>
