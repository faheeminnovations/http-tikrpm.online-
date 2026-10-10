<?php
/**
 * TikRPM.online – Blog Listing Page (DB-powered)
 */

$page_title       = 'TikTok Creator Blog – RPM Tips, Guides & Earnings Strategies';
$meta_description = 'Expert guides on TikTok RPM, Creator Rewards earnings, qualified views, niche strategy, and how to grow your TikTok income. Updated regularly.';
$meta_keywords    = 'TikTok RPM blog, creator rewards tips, TikTok earnings guide, how to increase TikTok RPM, TikTok monetization strategy';
$schema_type      = 'WebPage';

$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Blog', 'url' => '/blog'],
];

require_once 'includes/config.php';
require_once 'includes/db.php';

// ── Fetch posts from DB ───────────────────────────────────────
$featured = db_row(
    "SELECT id, slug, title, excerpt, tag, icon, author, read_time,
            DATE_FORMAT(created_at, '%M %d, %Y') AS date,
            DATE_FORMAT(created_at, '%Y-%m-%d')  AS date_iso
     FROM blog_posts
     WHERE published = 1 AND featured = 1
     ORDER BY created_at DESC
     LIMIT 1"
);

$articles = db_rows(
    "SELECT id, slug, title, excerpt, tag, icon, author, read_time,
            DATE_FORMAT(created_at, '%M %d, %Y') AS date,
            DATE_FORMAT(created_at, '%Y-%m-%d')  AS date_iso
     FROM blog_posts
     WHERE published = 1 AND featured = 0
     ORDER BY created_at DESC"
);

require_once 'includes/header.php';
?>

<main id="main-content">

  <!-- Page Hero -->
  <section class="page-hero" aria-label="Page introduction">
    <div class="container">
      <h1>Creator Earnings Blog</h1>
      <p>Guides, data, and strategies to help you earn more from TikTok's Creator Rewards Program.</p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= BASE_PATH ?>/">Home</a>
      <span class="breadcrumb-sep" aria-hidden="true">›</span>
      <span aria-current="page">Blog</span>
    </nav>
  </div>

  <section class="section">
    <div class="container">

      <!-- ── Featured Article ───────────────────────────────── -->
      <?php if ($featured): ?>
      <div class="mb-4">
        <span class="section-label">Featured Article</span>
        <article class="card" style="display:grid;grid-template-columns:auto 1fr;gap:1.5rem;align-items:start;" aria-label="Featured: <?= e($featured['title']) ?>">
          <div style="font-size:3rem;line-height:1;" aria-hidden="true"><?= $featured['icon'] ?></div>
          <div>
            <div class="blog-meta">
              <span class="blog-tag"><?= e($featured['tag']) ?></span>
              <time datetime="<?= e($featured['date_iso']) ?>"><?= e($featured['date']) ?></time>
              <span>⏱ <?= e($featured['read_time']) ?></span>
            </div>
            <h2 style="margin-top:.4rem;margin-bottom:.6rem;font-size:1.35rem;">
              <a href="<?= BASE_PATH ?>/blog/<?= e($featured['slug']) ?>"><?= e($featured['title']) ?></a>
            </h2>
            <p style="color:var(--text-muted);margin-bottom:1rem;"><?= e($featured['excerpt']) ?></p>
            <a href="<?= BASE_PATH ?>/blog/<?= e($featured['slug']) ?>" class="btn btn-primary btn-sm">
              Read Article →
            </a>
          </div>
        </article>
      </div>
      <?php endif; ?>

      <!-- ── Article Grid ───────────────────────────────────── -->
      <?php if (!empty($articles)): ?>
      <div class="section-header" style="text-align:left;margin-bottom:1.5rem;">
        <span class="section-label">All Articles</span>
        <h2 style="margin-top:.3rem;">Latest Guides &amp; Analysis</h2>
      </div>

      <div class="blog-grid" role="list">
        <?php foreach ($articles as $post): ?>
        <article class="blog-card" role="listitem" aria-label="<?= e($post['title']) ?>">
          <div class="blog-card-img" aria-hidden="true"><?= $post['icon'] ?></div>
          <div class="blog-card-body">
            <div class="blog-meta">
              <span class="blog-tag"><?= e($post['tag']) ?></span>
              <time datetime="<?= e($post['date_iso']) ?>"><?= e($post['date']) ?></time>
              <span>⏱ <?= e($post['read_time']) ?></span>
            </div>
            <h3><a href="<?= BASE_PATH ?>/blog/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h3>
            <p><?= e($post['excerpt']) ?></p>
            <a href="<?= BASE_PATH ?>/blog/<?= e($post['slug']) ?>" class="btn btn-secondary btn-sm">
              Read More →
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div><!-- /.blog-grid -->
      <?php else: ?>
        <p class="text-muted text-center mt-4">No articles published yet. Check back soon.</p>
      <?php endif; ?>

      <!-- CTA -->
      <div class="info-box teal mt-4" style="text-align:center;">
        <strong>Want to estimate your earnings right now?</strong><br>
        Use our free <a href="<?= BASE_PATH ?>/#calculator"><strong>TikTok RPM Calculator</strong></a>
        to get an instant breakdown based on your actual view counts and niche.
      </div>

    </div><!-- /.container -->
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>
