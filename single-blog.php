<?php
/**
 * TikRPM.online – Single Blog Post (DB-powered)
 * Route: /blog/{slug} → single-blog.php?slug={slug}
 */

require_once 'includes/config.php';
require_once 'includes/db.php';

// ── Slug routing & sanitise ───────────────────────────────────
$raw_slug = $_GET['slug'] ?? '';
$slug     = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($raw_slug)));

if ($slug === '') {
    header('Location: /blog', true, 302);
    exit;
}

// ── Fetch post from DB ────────────────────────────────────────
$post = db_row(
    "SELECT id, slug, title, excerpt, body, tag, icon, author, read_time, featured,
            DATE_FORMAT(created_at, '%M %d, %Y') AS date,
            DATE_FORMAT(created_at, '%Y-%m-%d')  AS date_iso
     FROM blog_posts
     WHERE slug = ? AND published = 1
     LIMIT 1",
    [$slug]
);

// 404 if not found
if (!$post) {
    header('HTTP/1.1 404 Not Found');
    header('Location: /blog', true, 302);
    exit;
}

// ── Fetch related posts (same tag, excluding current) ─────────
$related = db_rows(
    "SELECT slug, title, icon, tag,
            DATE_FORMAT(created_at, '%M %d, %Y') AS date
     FROM blog_posts
     WHERE published = 1 AND tag = ? AND id != ?
     ORDER BY created_at DESC
     LIMIT 3",
    [$post['tag'], $post['id']]
);

// ── Page Meta ─────────────────────────────────────────────────
$page_title       = $post['title'];
$meta_description = truncate($post['excerpt'], 160);
$meta_keywords    = 'TikTok RPM, ' . strtolower(e($post['tag'])) . ', creator rewards earnings, TikTok creator strategy';
$schema_type      = 'Article';

$article_schema = [
    '@context'         => 'https://schema.org',
    '@type'            => 'Article',
    'headline'         => $post['title'],
    'description'      => $meta_description,
    'author'           => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL],
    'publisher'        => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL],
    'datePublished'    => $post['date_iso'],
    'dateModified'     => $post['date_iso'],
    'url'              => SITE_URL . '/blog/' . $post['slug'],
    'mainEntityOfPage' => SITE_URL . '/blog/' . $post['slug'],
];

$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Blog', 'url' => '/blog'],
    ['name' => $post['title'], 'url' => '/blog/' . $post['slug']],
];

require_once 'includes/header.php';
?>

<main id="main-content">

  <!-- Page Hero -->
  <section class="page-hero" aria-label="Article introduction">
    <div class="container" style="max-width:860px;">
      <div style="margin-bottom:.75rem;">
        <span class="blog-tag" style="background:rgba(238,29,82,.2);color:#ff8fa8;">
          <?= e($post['tag']) ?>
        </span>
      </div>
      <h1 style="font-size:clamp(1.5rem,4vw,2.2rem);"><?= e($post['title']) ?></h1>
      <div class="blog-meta" style="justify-content:center;margin-top:.85rem;color:rgba(255,255,255,.55);">
        <span>By <?= e($post['author']) ?></span>
        <span aria-hidden="true">·</span>
        <time datetime="<?= e($post['date_iso']) ?>"><?= e($post['date']) ?></time>
        <span aria-hidden="true">·</span>
        <span>⏱ <?= e($post['read_time']) ?></span>
      </div>
    </div>
  </section>

  <!-- Breadcrumb -->
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= BASE_PATH ?>/">Home</a>
      <span class="breadcrumb-sep" aria-hidden="true">›</span>
      <a href="<?= BASE_PATH ?>/blog">Blog</a>
      <span class="breadcrumb-sep" aria-hidden="true">›</span>
      <span aria-current="page"><?= e($post['tag']) ?></span>
    </nav>
  </div>

  <section class="section">
    <div class="container" style="max-width:860px;">

      <!-- In-Article Ad (hidden until AdSense approved) -->
      <?php if (ADSENSE_ENABLED): ?>
      <div class="ad-wrapper" style="padding:0 0 1.5rem;">
        <ins class="adsbygoogle ad-slot ad-slot--in-article"
             style="display:block;text-align:center"
             data-ad-layout="in-article"
             data-ad-format="fluid"
             data-ad-client="<?= e(ADSENSE_CLIENT) ?>"
             data-ad-slot="IN_ARTICLE_SLOT_ID"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </div>
      <?php endif; ?>

      <!-- Article Body -->
      <article class="content-area" aria-label="Article body">

        <p style="font-size:1.05rem;font-style:italic;border-left:3px solid var(--accent);padding-left:1rem;margin-bottom:2rem;color:var(--text-muted);">
          <?= e($post['excerpt']) ?>
        </p>

        <!-- Body from DB — stored as safe HTML from admin editor -->
        <?= $post['body'] ?>

        <!-- Calculator CTA -->
        <div class="info-box mt-3 mb-3">
          <strong>Ready to model your earnings?</strong><br>
          Use the <a href="<?= BASE_PATH ?>/#calculator"><strong>TikRPM Calculator</strong></a> to get an
          instant breakdown based on your actual view counts and niche.
        </div>

      </article>

      <!-- Related Posts -->
      <?php if (!empty($related)): ?>
      <div class="mt-4" style="border-top:1px solid var(--border);padding-top:2rem;">
        <h3 class="mb-3">Related Articles</h3>
        <div class="blog-grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
          <?php foreach ($related as $r): ?>
          <a href="<?= BASE_PATH ?>/blog/<?= e($r['slug']) ?>" class="card" style="display:block;text-decoration:none;">
            <div style="font-size:2rem;margin-bottom:.5rem;" aria-hidden="true"><?= $r['icon'] ?></div>
            <span class="blog-tag" style="font-size:.7rem;"><?= e($r['tag']) ?></span>
            <h4 style="margin-top:.5rem;font-size:.95rem;color:var(--text);"><?= e($r['title']) ?></h4>
            <p class="form-hint"><?= e($r['date']) ?></p>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Nav -->
      <div class="mt-4" style="border-top:1px solid var(--border);padding-top:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
        <a href="<?= BASE_PATH ?>/blog" class="btn btn-secondary">← Back to Blog</a>
        <a href="<?= BASE_PATH ?>/#calculator" class="btn btn-primary">Try the Calculator →</a>
      </div>

    </div><!-- /.container -->
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>
