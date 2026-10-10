<?php
/**
 * TikRPM Admin – Delete Post
 * POST only — CSRF protected.
 */
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

// CSRF
$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $token)) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Security check failed.'];
    header('Location: dashboard.php');
    exit;
}

$post_id = (int)($_POST['id'] ?? 0);

if ($post_id > 0) {
    // Verify post exists before deleting
    $exists = db_row('SELECT id, title FROM blog_posts WHERE id = ? LIMIT 1', [$post_id]);

    if ($exists) {
        db_exec('DELETE FROM blog_posts WHERE id = ?', [$post_id]);
        $_SESSION['flash'] = [
            'type' => 'success',
            'msg'  => '🗑️ Post "' . $exists['title'] . '" deleted successfully.',
        ];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Post not found.'];
    }
} else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Invalid post ID.'];
}

header('Location: dashboard.php');
exit;
