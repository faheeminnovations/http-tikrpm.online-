<?php
/**
 * TikRPM Admin – Auth Guard
 * Include at the top of every protected admin page.
 * Usage: require_once __DIR__ . '/auth.php';
 */
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';

session_name(ADMIN_SESSION_NAME);
session_start();

// Check login
if (empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_PATH . '/admin/index.php');
    exit;
}

// Session timeout check
if (isset($_SESSION['admin_login_at'])) {
    if (time() - $_SESSION['admin_login_at'] > ADMIN_SESSION_LIFETIME) {
        session_destroy();
        header('Location: ' . BASE_PATH . '/admin/index.php?timeout=1');
        exit;
    }
    // Rolling expiry — refresh on activity
    $_SESSION['admin_login_at'] = time();
}

// Ensure CSRF token exists for all admin forms
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
