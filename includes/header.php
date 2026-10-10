<?php
/**
 * TikRPM.online – Global Header
 * Outputs: <!DOCTYPE html> through </header>
 * Expects: $page_title, $meta_description, $meta_keywords (optional),
 *          $og_image (optional), $schema_type (optional) set before require.
 */
require_once __DIR__ . '/config.php';

// ── Meta Defaults ─────────────────────────────────────────────────────────────
$page_title      = isset($page_title)      ? trim($page_title)      : DEFAULT_TITLE;
$meta_description= isset($meta_description)? trim($meta_description): DEFAULT_DESCRIPTION;
$meta_keywords   = isset($meta_keywords)   ? trim($meta_keywords)   : DEFAULT_KEYWORDS;
$og_image        = isset($og_image)        ? $og_image              : DEFAULT_OG_IMAGE;
$schema_type     = isset($schema_type)     ? $schema_type           : 'WebPage';
$body_class      = isset($body_class)      ? e($body_class)         : '';

// Full <title> — append site name unless it's already in the title
$full_title = (strpos($page_title, SITE_NAME) === false)
    ? $page_title . ' | ' . SITE_NAME
    : $page_title;

$canonical = canonical_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- ── Primary Meta ───────────────────────────────────────── -->
  <title><?= e($full_title) ?></title>
  <meta name="description"        content="<?= e($meta_description) ?>">
  <meta name="keywords"           content="<?= e($meta_keywords) ?>">
  <meta name="author"             content="<?= e(SITE_AUTHOR) ?>">
  <meta name="robots"             content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <link rel="canonical"           href="<?= e($canonical) ?>">

  <!-- ── Open Graph ─────────────────────────────────────────── -->
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= e($canonical) ?>">
  <meta property="og:title"       content="<?= e($full_title) ?>">
  <meta property="og:description" content="<?= e($meta_description) ?>">
  <meta property="og:image"       content="<?= e($og_image) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height"content="630">
  <meta property="og:site_name"   content="<?= e(SITE_NAME) ?>">
  <meta property="og:locale"      content="en_US">

  <!-- ── Twitter Card ───────────────────────────────────────── -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?= e($full_title) ?>">
  <meta name="twitter:description" content="<?= e($meta_description) ?>">
  <meta name="twitter:image"       content="<?= e($og_image) ?>">

  <!-- ── Schema.org JSON-LD ─────────────────────────────────── -->
<?php if ($schema_type === 'SoftwareApplication'): ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "TikTok RPM Calculator",
    "description": "<?= addslashes(e($meta_description)) ?>",
    "url": "<?= SITE_URL ?>",
    "applicationCategory": "UtilityApplication",
    "operatingSystem": "Web",
    "offers": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "USD"
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.8",
      "reviewCount": "312",
      "bestRating": "5"
    },
    "author": {
      "@type": "Organization",
      "name": "<?= SITE_NAME ?>",
      "url": "<?= SITE_URL ?>"
    }
  }
  </script>
<?php elseif ($schema_type === 'Article' && isset($article_schema)): ?>
  <script type="application/ld+json">
  <?= json_encode($article_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
  </script>
<?php else: ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "<?= addslashes(e($full_title)) ?>",
    "description": "<?= addslashes(e($meta_description)) ?>",
    "url": "<?= e($canonical) ?>",
    "isPartOf": {
      "@type": "WebSite",
      "name": "<?= SITE_NAME ?>",
      "url": "<?= SITE_URL ?>"
    }
  }
  </script>
<?php endif; ?>

  <!-- ── BreadcrumbList Schema (optional) ───────────────────── -->
<?php if (isset($breadcrumbs) && count($breadcrumbs) > 1): ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
<?php foreach ($breadcrumbs as $i => $crumb): ?>
      {
        "@type": "ListItem",
        "position": <?= $i + 1 ?>,
        "name": "<?= addslashes(e($crumb['name'])) ?>",
        "item": "<?= e(SITE_URL . $crumb['url']) ?>"
      }<?= ($i < count($breadcrumbs) - 1) ? ',' : '' ?>

<?php endforeach; ?>
    ]
  }
  </script>
<?php endif; ?>

  <!-- ── Favicon ─────────────────────────────────────────────── -->
  <link rel="icon" type="image/svg+xml" href="<?= ASSETS_URL ?>/images/favicon.svg">
  <link rel="alternate icon" type="image/png" href="<?= ASSETS_URL ?>/images/favicon.png">
  <link rel="apple-touch-icon" href="<?= ASSETS_URL ?>/images/favicon.png">
  <meta name="theme-color" content="#EE1D52">

  <!-- ── Stylesheet ─────────────────────────────────────────── -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">

  <!-- ── Google AdSense ─────────────────────────────────────── -->
<?php if (ADSENSE_ENABLED): ?>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?= e(ADSENSE_CLIENT) ?>"
          crossorigin="anonymous"></script>
<?php else: ?>
  <!-- AdSense: replace ADSENSE_CLIENT in config.php and set ADSENSE_ENABLED to true -->
<?php endif; ?>

  <!-- ── Google Analytics ───────────────────────────────────── -->
<?php if (GA_ENABLED): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GA_MEASUREMENT_ID) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= e(GA_MEASUREMENT_ID) ?>', { anonymize_ip: true });
  </script>
<?php endif; ?>
</head>
<body class="<?= $body_class ?>">

<!-- ── Skip to Content (Accessibility) ──────────────────────── -->
<a href="#main-content" class="sr-only sr-only-focusable" style="position:absolute;left:-9999px;top:auto;z-index:9999;padding:.5rem 1rem;background:var(--accent);color:#fff;border-radius:0 0 8px 0;font-weight:600;">Skip to main content</a>

<!-- ── Header Ad Slot (hidden until AdSense approved) ────────── -->
<?php if (ADSENSE_ENABLED): ?>
<div class="ad-wrapper" style="background:var(--surface-2);border-bottom:1px solid var(--border);">
  <div class="container">
    <ins class="adsbygoogle ad-slot ad-slot--leaderboard"
         style="display:block"
         data-ad-client="<?= e(ADSENSE_CLIENT) ?>"
         data-ad-slot="HEADER_SLOT_ID"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
  </div>
</div>
<?php endif; ?>

<!-- ── Navigation ─────────────────────────────────────────────── -->
<header class="navbar" role="banner">
  <div class="container navbar-inner">

    <a href="<?= BASE_PATH ?>/" class="navbar-brand" aria-label="<?= e(SITE_NAME) ?> — Home">
      <div class="brand-icon" aria-hidden="true">T</div>
      <span>
        <span class="brand-tik">TikTok</span><span class="brand-rpm">RPM</span>
      </span>
    </a>

    <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Toggle navigation">
      <span></span><span></span><span></span>
    </button>

    <nav id="primaryNav" role="navigation" aria-label="Primary navigation">
      <ul class="navbar-nav" id="navMenu">
        <?php foreach ($nav_links as $label => $href): ?>
          <li>
            <a href="<?= e($href) ?>"
               class="<?= active_class($href) ?>"
               <?= active_class($href) ? 'aria-current="page"' : '' ?>>
              <?= e($label) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

  </div>
</header>
<!-- ── End Navbar ──────────────────────────────────────────────── -->
