<?php
/**
 * TikRPM.online – Site Configuration
 * Central settings: base URL, site identity, defaults, and meta helpers.
 */
// ── Database ──────────────────────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_PORT',    '3306');
define('DB_NAME',    'tikrpm');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ── Base Path (subfolder support for local dev) ───────────────────────────────
// On localhost XAMPP: /tikrpm    On production root: (empty string '')
define('BASE_PATH',  '/tikrpm');
define('ASSETS_URL', BASE_PATH . '/assets');

// ── Environment ──────────────────────────────────────────────────────────────
define('ENVIRONMENT', 'production'); // 'development' | 'production'

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ── Site Identity ─────────────────────────────────────────────────────────────
define('SITE_NAME',        'TikRPM');
define('SITE_TAGLINE',     'TikTok RPM & Creator Rewards Earnings Calculator');
define('SITE_URL',         'https://tikrpm.online');
define('SITE_DOMAIN',      'tikrpm.online');
define('SITE_EMAIL',       'hello@tikrpm.online');
define('SITE_AUTHOR',      'TikRPM Team');

// ── Google AdSense ─────────────────────────────────────────────────────────────
// Replace with your publisher ID once approved: ca-pub-XXXXXXXXXXXXXXXX
define('ADSENSE_CLIENT',   'ca-pub-XXXXXXXXXXXXXXXX');
define('ADSENSE_ENABLED',  false); // set true after approval

// ── Google Analytics ───────────────────────────────────────────────────────────
define('GA_MEASUREMENT_ID', 'G-XXXXXXXXXX'); // Replace with your GA4 ID
define('GA_ENABLED',        false);

// ── Default Meta ───────────────────────────────────────────────────────────────
define('DEFAULT_TITLE',       'TikTok RPM Calculator – Estimate Creator Rewards Earnings | TikRPM');
define('DEFAULT_DESCRIPTION', 'Free TikTok RPM calculator for the Creator Rewards Program. Estimate your qualified views and earnings based on real RPM rates from $0.10 to $2.50 per 1,000 views.');
define('DEFAULT_KEYWORDS',    'TikTok RPM calculator, TikTok creator rewards, TikTok earnings calculator, qualified views, TikTok RPM rate, creator fund earnings');
define('DEFAULT_OG_IMAGE',    SITE_URL . '/assets/images/og-tikrpm.jpg');

// ── Navigation Links ───────────────────────────────────────────────────────────
$nav_links = [
    'Home'    => BASE_PATH . '/',
    'Blog'    => BASE_PATH . '/blog',
    'About'   => BASE_PATH . '/about',
    'Contact' => BASE_PATH . '/contact',
];

// ── Footer Links ───────────────────────────────────────────────────────────────
$footer_links = [
    'Privacy Policy'   => BASE_PATH . '/privacy-policy',
    'Terms of Service' => BASE_PATH . '/terms',
    'About'            => BASE_PATH . '/about',
    'Contact'          => BASE_PATH . '/contact',
    'Blog'             => BASE_PATH . '/blog',
];

// ── Helper: Active Nav Class ───────────────────────────────────────────────────
function active_class(string $path): string {
    // Strip BASE_PATH prefix for comparison
    $clean_path = str_replace(BASE_PATH, '', $path);
    $clean_path = $clean_path === '' ? '/' : $clean_path;

    $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Strip BASE_PATH from request URI too
    $request = str_replace(BASE_PATH, '', $request);
    $request = ($request === '' || $request === '/') ? '/' : '/' . trim($request, '/');

    return ($request === $clean_path) ? 'active' : '';
}

// ── Helper: Safe Output ────────────────────────────────────────────────────────
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// ── Helper: Canonical URL ──────────────────────────────────────────────────────
function canonical_url(): string {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = '/' . trim($path, '/');
    if ($path === '/') {
        return SITE_URL . '/';
    }
    return SITE_URL . rtrim($path, '/');
}

// ── Calculator Defaults ────────────────────────────────────────────────────────
define('RPM_MIN',     0.10);
define('RPM_MAX',     2.50);
define('RPM_DEFAULT', 0.60);
define('QUALIFIED_VIEWS_DEFAULT_PCT', 60);

// ── Admin Settings ─────────────────────────────────────────────────────────────
define('ADMIN_SESSION_NAME', 'tikrpm_admin');
define('ADMIN_SESSION_LIFETIME', 3600); // 1 hour

// ── Helper: Generate slug from title ──────────────────────────────────────────
function make_slug(string $title): string {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
    $slug = preg_replace('/[\s\-]+/', '-', $slug);
    return trim($slug, '-');
}

// ── Helper: Truncate text ──────────────────────────────────────────────────────
function truncate(string $text, int $length = 160): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) return $text;
    return rtrim(mb_substr($text, 0, $length)) . '…';
}
