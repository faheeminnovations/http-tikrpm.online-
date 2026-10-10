<?php
/**
 * TikRPM Admin – Save Post (Insert or Update)
 * POST only — redirects back on success/failure.
 */
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php'); exit;
}
$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $token)) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Security check failed. Please try again.'];
    header('Location: dashboard.php'); exit;
}

$post_id = (int)($_POST['id'] ?? 0);
$is_edit = $post_id > 0;

// ── Collect & sanitise inputs ─────────────────────────────────
$title     = trim($_POST['title']     ?? '');
$slug      = trim($_POST['slug']      ?? '');
$excerpt   = trim($_POST['excerpt']   ?? '');
$body      = trim($_POST['body']      ?? '');  // stored as HTML
$tag       = trim($_POST['tag']       ?? 'General');
$icon      = trim($_POST['icon']      ?? '📝');
$author    = trim($_POST['author']    ?? 'TikRPM Team');
$read_time = trim($_POST['read_time'] ?? '5 min read');
$featured  = isset($_POST['featured'])  ? 1 : 0;
$published = isset($_POST['published']) ? 1 : 0;

// Clean slug — lowercase letters, numbers, hyphens only
$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));
$slug = preg_replace('/\-+/', '-', $slug);
$slug = trim($slug, '-');

// ── Validate ──────────────────────────────────────────────────
$errors = [];

if (strlen($title) < 3 || strlen($title) > 300)    $errors[] = 'Title must be 3–300 characters.';
if (strlen($slug)  < 3 || strlen($slug)  > 200)    $errors[] = 'Slug must be 3–200 characters.';
if (strlen($excerpt) < 10 || strlen($excerpt) > 300) $errors[] = 'Excerpt must be 10–300 characters.';
if (strlen($body)  < 20)                            $errors[] = 'Body is too short (minimum 20 characters).';

if (!empty($errors)) {
    $_SESSION['form_data']  = compact('title','slug','excerpt','body','tag','icon','author','read_time','featured','published');
    $_SESSION['form_data']['id'] = $post_id;
    $_SESSION['form_error'] = implode(' ', $errors);
    $redirect = $is_edit
        ? "post-form.php?id={$post_id}"
        : 'post-form.php';
    header("Location: {$redirect}");
    exit;
}

// ── Check slug uniqueness (exclude self on edit) ──────────────
$existing_slug = db_row(
    'SELECT id FROM blog_posts WHERE slug = ? AND id != ? LIMIT 1',
    [$slug, $post_id]
);

if ($existing_slug) {
    $_SESSION['form_data']  = compact('title','slug','excerpt','body','tag','icon','author','read_time','featured','published');
    $_SESSION['form_data']['id'] = $post_id;
    $_SESSION['form_error'] = "Slug \"{$slug}\" is already in use. Please choose a different slug.";
    $redirect = $is_edit
        ? "post-form.php?id={$post_id}"
        : 'post-form.php';
    header("Location: {$redirect}");
    exit;
}

// ── If featured, unfeatured others first ──────────────────────
if ($featured) {
    db_exec(
        'UPDATE blog_posts SET featured = 0 WHERE id != ?',
        [$post_id]
    );
}

// ── Insert or Update ──────────────────────────────────────────
try {
    if ($is_edit) {
        db_exec(
            'UPDATE blog_posts
             SET slug=?, title=?, excerpt=?, body=?, tag=?, icon=?, author=?,
                 read_time=?, featured=?, published=?
             WHERE id=?',
            [$slug, $title, $excerpt, $body, $tag, $icon, $author,
             $read_time, $featured, $published, $post_id]
        );
        $_SESSION['flash'] = ['type' => 'success', 'msg' => '✅ Post updated successfully.'];
    } else {
        db_exec(
            'INSERT INTO blog_posts
             (slug, title, excerpt, body, tag, icon, author, read_time, featured, published)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$slug, $title, $excerpt, $body, $tag, $icon, $author,
             $read_time, $featured, $published]
        );
        $_SESSION['flash'] = ['type' => 'success', 'msg' => '🚀 Post published successfully.'];
    }
} catch (PDOException $e) {
    $_SESSION['form_data']  = compact('title','slug','excerpt','body','tag','icon','author','read_time','featured','published');
    $_SESSION['form_data']['id'] = $post_id;
    $_SESSION['form_error'] = 'Database error. Please try again.';
    $redirect = $is_edit
        ? "post-form.php?id={$post_id}"
        : 'post-form.php';
    header("Location: {$redirect}");
    exit;
}

header('Location: ' . 'dashboard.php');
exit;
