<?php
/**
 * TikRPM Admin – Add / Edit Post
 * GET  post-form.php        → new post
 * GET  post-form.php?id=N   → edit post N
 */
require_once __DIR__ . '/auth.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_edit = $post_id > 0;
$error   = '';

$post = [
    'id'        => 0,
    'slug'      => '',
    'title'     => '',
    'excerpt'   => '',
    'body'      => '',
    'tag'       => 'General',
    'icon'      => '📝',
    'author'    => 'TikRPM Team',
    'read_time' => '5 min read',
    'featured'  => 0,
    'published' => 1,
];

if ($is_edit) {
    $row = db_row('SELECT * FROM blog_posts WHERE id = ? LIMIT 1', [$post_id]);
    if (!$row) { header('Location: dashboard.php'); exit; }
    $post = $row;
}

if (!empty($_SESSION['form_data'])) {
    $post  = array_merge($post, $_SESSION['form_data']);
    $error = $_SESSION['form_error'] ?? '';
    unset($_SESSION['form_data'], $_SESSION['form_error']);
}

$tags  = ['Strategy','Data','Education','News','Tutorial','Comparison','General'];
$icons = ['📈','💰','🎯','🔄','🧮','⚖️','📝','🚀','💡','🎓','📊','🌍'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $is_edit ? 'Edit Post' : 'New Post' ?> – TikRPM Admin</title>
<meta name="robots" content="noindex,nofollow">
  <link rel="icon" type="image/svg+xml" href="/tikrpm/assets/images/favicon.svg">
  <link rel="alternate icon" type="image/png" href="/tikrpm/assets/images/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:#f1f3f6;min-height:100vh;display:flex;flex-direction:column}
a{text-decoration:none;color:inherit}

/* ── Topbar ── */
.topbar{background:#0f0f1a;color:#fff;padding:.85rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.topbar-brand{font-size:1.2rem;font-weight:800}
.topbar-brand span{color:#EE1D52}
.topbar-nav{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
.topbar-nav a{font-size:.82rem;color:rgba(255,255,255,.6);background:rgba(255,255,255,.08);padding:.35rem .75rem;border-radius:6px;transition:all .15s}
.topbar-nav a:hover{background:rgba(255,255,255,.15);color:#fff}
.topbar-nav a.active{background:#EE1D52;color:#fff}

/* ── Layout ── */
.wrap{padding:1.5rem;max-width:1100px;margin:0 auto;width:100%}
.page-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap}
.page-head h1{font-size:1.3rem;font-weight:700}
.grid{display:grid;grid-template-columns:1fr 310px;gap:1.5rem;align-items:start}
@media(max-width:780px){.grid{grid-template-columns:1fr}}

/* ── Cards ── */
.card{background:#fff;border:1px solid #e2e6ea;border-radius:12px;padding:1.25rem;margin-bottom:1rem}
.card h3{font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;margin-bottom:.9rem}

/* ── Form ── */
.fg{display:flex;flex-direction:column;gap:.35rem;margin-bottom:.85rem}
.fg:last-child{margin-bottom:0}
label{font-size:.78rem;font-weight:600;color:#374151;text-transform:uppercase;letter-spacing:.05em}
input[type=text],input[type=number],select,textarea{width:100%;padding:.6rem .85rem;border:1.5px solid #e2e6ea;border-radius:8px;font-size:.9rem;color:#1a1a2e;background:#f8f9fb;font-family:inherit;transition:border-color .2s,box-shadow .2s;outline:none}
input:focus,select:focus,textarea:focus{border-color:#EE1D52;box-shadow:0 0 0 3px rgba(238,29,82,.1);background:#fff}
textarea{resize:vertical}
.hint{font-size:.72rem;color:#9ca3af}
.hint span{color:#EE1D52;font-weight:600}

/* ── Checkbox row ── */
.chk-row{display:flex;align-items:center;gap:.6rem;cursor:pointer;font-size:.88rem;font-weight:500;color:#374151;margin-bottom:.6rem}
.chk-row input{width:16px;height:16px;accent-color:#EE1D52;cursor:pointer;flex-shrink:0}

/* ── Buttons ── */
.btn{display:inline-flex;align-items:center;gap:.35rem;padding:.55rem 1.1rem;border-radius:8px;font-size:.88rem;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all .15s;white-space:nowrap}
.btn-primary{background:#EE1D52;color:#fff;width:100%;justify-content:center;padding:.75rem}
.btn-primary:hover{background:#c4143f}
.btn-ghost{background:#f1f3f6;color:#374151;border:1px solid #e2e6ea}
.btn-ghost:hover{background:#e2e6ea}
.btn-gen{background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;font-size:.75rem;padding:.3rem .7rem}

/* ── Icon picker ── */
.icon-grid{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.4rem}
.icon-btn{font-size:1.3rem;background:none;border:2px solid #e2e6ea;border-radius:8px;padding:.2rem .45rem;cursor:pointer;transition:all .15s;line-height:1}
.icon-btn:hover{border-color:#EE1D52}
.icon-btn.sel{border-color:#EE1D52;background:#fff0f3}

/* ── Preview ── */
.preview-box{border:1.5px solid #e2e6ea;border-radius:8px;padding:1rem;min-height:100px;background:#f8f9fb;font-size:.88rem;line-height:1.7;color:#374151}
.preview-box h2{font-size:1.1rem;margin:1rem 0 .4rem;color:#1a1a2e}
.preview-box h3{font-size:.95rem;margin:.85rem 0 .35rem;color:#1a1a2e}
.preview-box p{margin-bottom:.6rem}
.preview-box ul,.preview-box ol{padding-left:1.3rem;margin-bottom:.6rem}
.preview-box li{margin-bottom:.2rem}
.preview-box strong{font-weight:700}
.preview-box em{font-style:italic}
.preview-box table{width:100%;border-collapse:collapse;font-size:.82rem;margin:.5rem 0}
.preview-box th{background:#1a1a2e;color:#fff;padding:.4rem .6rem;text-align:left}
.preview-box td{padding:.4rem .6rem;border-bottom:1px solid #e2e6ea}

/* ── Alert ── */
.alert-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:.8rem 1rem;border-radius:8px;font-size:.88rem;margin-bottom:1rem;font-weight:500}

/* ── Slug row ── */
.slug-row{display:flex;gap:.5rem;align-items:flex-start}
.slug-row input{flex:1}
</style>
</head>
<body>

<div class="topbar">
  <div class="topbar-brand">Tik<span>RPM</span> <span style="color:rgba(255,255,255,.3);font-weight:400;font-size:.9rem;">Admin</span></div>
  <div class="topbar-nav">
    <a href="dashboard.php">📊 Posts</a>
    <a href="post-form.php" class="active">✏️ <?= $is_edit ? 'Edit Post' : 'New Post' ?></a>
    <a href="rpm-rates.php">📈 RPM Rates</a>
    <a href="<?= BASE_PATH ?>/" target="_blank">🌐 View Site</a>
    <a href="logout.php" style="background:rgba(238,29,82,.25);color:#ff8fa8;">Sign Out</a>
  </div>
</div>

<div class="wrap">
  <div class="page-head">
    <h1><?= $is_edit ? '✏️ Edit Post' : '✨ New Post' ?></h1>
    <a href="dashboard.php" class="btn btn-ghost">← Back to Posts</a>
  </div>

  <?php if ($error): ?>
    <div class="alert-error">⚠️ <?= e($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="post-save.php" id="postForm">
    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
    <input type="hidden" name="id"         value="<?= (int)$post['id'] ?>">

    <div class="grid">

      <!-- ── LEFT: Content ── -->
      <div>

        <!-- Title + Slug -->
        <div class="card">
          <h3>Post Content</h3>
          <div class="fg">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title"
                   value="<?= e($post['title']) ?>"
                   placeholder="How to Increase Your TikTok RPM..."
                   maxlength="300" required>
          </div>
          <div class="fg">
            <label for="slug">URL Slug *</label>
            <div class="slug-row">
              <input type="text" id="slug" name="slug"
                     value="<?= e($post['slug']) ?>"
                     placeholder="how-to-increase-tiktok-rpm"
                     pattern="[a-z0-9\-]+" maxlength="200" required>
              <button type="button" id="btnGenSlug" class="btn btn-gen">Auto ↺</button>
            </div>
            <span class="hint">tikrpm.online/blog/<span id="slugPreview"><?= e($post['slug']) ?></span></span>
          </div>
          <div class="fg" style="margin-bottom:0">
            <label for="excerpt">Excerpt / Meta Description * <span class="hint">(max 300 chars)</span></label>
            <textarea id="excerpt" name="excerpt"
                      style="min-height:80px" maxlength="300" required><?= e($post['excerpt']) ?></textarea>
            <span class="hint"><span id="excerptCount"><?= strlen($post['excerpt']) ?></span>/300</span>
          </div>
        </div>

        <!-- Body -->
        <div class="card">
          <h3>Article Body <span class="hint">(HTML supported)</span></h3>
          <div class="fg" style="margin-bottom:0">
            <textarea id="body" name="body"
                      style="min-height:380px;font-family:monospace;font-size:.82rem;"
                      required><?= e($post['body']) ?></textarea>
            <span class="hint">Use: &lt;h2&gt; &lt;h3&gt; &lt;p&gt; &lt;ul&gt; &lt;li&gt; &lt;strong&gt; &lt;em&gt; &lt;table&gt; &lt;tr&gt; &lt;th&gt; &lt;td&gt;</span>
          </div>
        </div>

        <!-- Live Preview -->
        <div class="card">
          <h3>Live Preview</h3>
          <div class="preview-box" id="bodyPreview">
            <?php if ($post['body']): ?>
              <?= $post['body'] ?>
            <?php else: ?>
              <span style="color:#9ca3af;font-style:italic;">Start typing body to see preview…</span>
            <?php endif; ?>
          </div>
        </div>

      </div><!-- /left -->

      <!-- ── RIGHT: Settings ── -->
      <div>

        <!-- Publish -->
        <div class="card">
          <h3>Publish</h3>
          <label class="chk-row">
            <input type="checkbox" name="published" value="1" <?= $post['published'] ? 'checked' : '' ?>>
            Published (visible on site)
          </label>
          <label class="chk-row" style="margin-bottom:1rem">
            <input type="checkbox" name="featured" value="1" <?= $post['featured'] ? 'checked' : '' ?>>
            Featured (shown at top of blog)
          </label>
          <button type="submit" class="btn btn-primary">
            <?= $is_edit ? '💾 Update Post' : '🚀 Publish Post' ?>
          </button>
          <?php if ($is_edit): ?>
            <p style="margin-top:.75rem;text-align:center;">
              <a href="<?= BASE_PATH ?>/blog/<?= e($post['slug']) ?>" target="_blank"
                 style="font-size:.78rem;color:#EE1D52;">View live post ↗</a>
            </p>
          <?php endif; ?>
        </div>

        <!-- Category & Icon -->
        <div class="card">
          <h3>Category</h3>
          <div class="fg">
            <label for="tag">Tag</label>
            <select id="tag" name="tag">
              <?php foreach ($tags as $t): ?>
                <option value="<?= e($t) ?>" <?= $post['tag'] === $t ? 'selected' : '' ?>><?= e($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fg" style="margin-bottom:0">
            <label>Post Icon</label>
            <div class="icon-grid">
              <?php foreach ($icons as $ic): ?>
                <button type="button"
                        class="icon-btn <?= $post['icon'] === $ic ? 'sel' : '' ?>"
                        onclick="pickIcon('<?= $ic ?>')"><?= $ic ?></button>
              <?php endforeach; ?>
            </div>
            <input type="hidden" id="icon" name="icon" value="<?= e($post['icon']) ?>">
          </div>
        </div>

        <!-- Author & Read time -->
        <div class="card">
          <h3>Details</h3>
          <div class="fg">
            <label for="author">Author</label>
            <input type="text" id="author" name="author"
                   value="<?= e($post['author']) ?>" maxlength="100">
          </div>
          <div class="fg" style="margin-bottom:0">
            <label for="read_time">Read Time</label>
            <input type="text" id="read_time" name="read_time"
                   value="<?= e($post['read_time']) ?>" placeholder="5 min read" maxlength="30">
          </div>
        </div>

      </div><!-- /right -->
    </div><!-- /grid -->
  </form>

</div><!-- /wrap -->

<script>
// Auto-generate slug from title
document.getElementById('btnGenSlug').addEventListener('click', function() {
  var t = document.getElementById('title').value;
  var s = t.toLowerCase().replace(/[^a-z0-9\s]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'');
  document.getElementById('slug').value = s;
  document.getElementById('slugPreview').textContent = s;
});

// Slug live preview
document.getElementById('slug').addEventListener('input', function() {
  document.getElementById('slugPreview').textContent = this.value;
});

// Excerpt counter
var exc = document.getElementById('excerpt');
var cnt = document.getElementById('excerptCount');
exc.addEventListener('input', function(){ cnt.textContent = this.value.length; });

// Body live preview (debounced)
var body = document.getElementById('body');
var prev = document.getElementById('bodyPreview');
var t;
body.addEventListener('input', function() {
  clearTimeout(t);
  t = setTimeout(function() {
    prev.innerHTML = body.value || '<span style="color:#9ca3af;font-style:italic;">Start typing body to see preview…</span>';
  }, 350);
});

// Icon picker
function pickIcon(ic) {
  document.getElementById('icon').value = ic;
  document.querySelectorAll('.icon-btn').forEach(function(b) {
    b.classList.toggle('sel', b.textContent.trim() === ic);
  });
}
</script>
</body>
</html>
