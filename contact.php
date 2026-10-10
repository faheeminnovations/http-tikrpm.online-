<?php
/**
 * TikRPM.online – Contact Page
 * Handles form submission with sanitisation and PHP mail().
 */

$page_title       = 'Contact TikRPM – Get in Touch';
$meta_description = 'Contact the TikRPM team with questions about our TikTok RPM calculator, methodology, or general enquiries. We aim to reply within 48 hours.';
$meta_keywords    = 'contact TikRPM, TikTok RPM calculator support, creator tools contact';
$schema_type      = 'WebPage';

$breadcrumbs = [
    ['name' => 'Home',    'url' => '/'],
    ['name' => 'Contact', 'url' => '/contact'],
];

// ── Form Processing ───────────────────────────────────────────
$success_msg = '';
$error_msg   = '';
$form_data   = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$field_errors= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF token check (simple token stored in session)
    session_start();
    $token = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        $error_msg = 'Security check failed. Please refresh the page and try again.';
    } else {

        // Sanitise & validate inputs
        $name    = htmlspecialchars(strip_tags(trim($_POST['name']    ?? '')), ENT_QUOTES, 'UTF-8');
        $email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(strip_tags(trim($_POST['subject'] ?? '')), ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')), ENT_QUOTES, 'UTF-8');

        // Store for re-fill on error
        $form_data = compact('name', 'email', 'subject', 'message');

        // Validate
        if (strlen($name) < 2 || strlen($name) > 100) {
            $field_errors['name'] = 'Please enter your full name (2–100 characters).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $field_errors['email'] = 'Please enter a valid email address.';
        }
        if (strlen($subject) < 3 || strlen($subject) > 150) {
            $field_errors['subject'] = 'Subject must be between 3 and 150 characters.';
        }
        if (strlen($message) < 10 || strlen($message) > 3000) {
            $field_errors['message'] = 'Message must be between 10 and 3,000 characters.';
        }

        // Honeypot anti-spam check
        if (!empty($_POST['website'])) {
            // Silent fail — bot submission
            $success_msg = 'Thank you! Your message has been sent. We\'ll be in touch within 48 hours.';
        } elseif (empty($field_errors)) {

            // Build email
            $to      = SITE_EMAIL;
            $subject_line = '[TikRPM Contact] ' . $subject;
            $body    = "Name:    {$name}\n"
                     . "Email:   {$email}\n"
                     . "Subject: {$subject}\n\n"
                     . "Message:\n{$message}\n\n"
                     . "---\nSent via tikrpm.online contact form\n"
                     . "IP: " . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'unknown', ENT_QUOTES, 'UTF-8');

            $headers  = "From: noreply@" . SITE_DOMAIN . "\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            // Prevent header injection
            if (preg_match('/[\r\n]/', $name . $email . $subject)) {
                $error_msg = 'Invalid characters detected. Please try again.';
            } else {
                if (@mail($to, $subject_line, $body, $headers)) {
                    $success_msg = 'Thank you! Your message has been sent. We\'ll be in touch within 48 hours.';
                    $form_data   = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
                    // Regenerate CSRF token after success
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                } else {
                    $error_msg = 'Sorry, we couldn\'t send your message right now. Please email us directly at '
                               . e(SITE_EMAIL) . '.';
                }
            }
        }
    }
} else {
    // Generate CSRF token on GET
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

require_once 'includes/header.php';
?>

<main id="main-content">

  <!-- Page Hero -->
  <section class="page-hero" aria-label="Page introduction">
    <div class="container">
      <h1>Contact Us</h1>
      <p>Have a question, suggestion, or feedback? We'd love to hear from you.</p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= BASE_PATH ?>/">Home</a>
      <span class="breadcrumb-sep" aria-hidden="true">›</span>
      <span aria-current="page">Contact</span>
    </nav>
  </div>

  <section class="section">
    <div class="container">
      <div class="form-page">

        <!-- Intro -->
        <div class="info-box teal mb-3">
          <strong>Response time:</strong> We aim to reply to all enquiries within
          <strong>48 business hours</strong>. For urgent matters, email us directly at
          <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>.
        </div>

        <!-- Success / Error Alerts -->
        <?php if ($success_msg): ?>
          <div class="alert alert-success" role="alert" aria-live="assertive">
            <span aria-hidden="true">✅</span>
            <?= e($success_msg) ?>
          </div>
        <?php endif; ?>

        <?php if ($error_msg): ?>
          <div class="alert alert-error" role="alert" aria-live="assertive">
            <span aria-hidden="true">⚠️</span>
            <?= e($error_msg) ?>
          </div>
        <?php endif; ?>

        <!-- Contact Form -->
        <?php if (!$success_msg): ?>
        <form
          method="POST"
          action="/contact"
          novalidate
          aria-label="Contact form"
        >
          <!-- CSRF Token -->
          <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">

          <!-- Honeypot (hidden from real users) -->
          <div style="display:none;" aria-hidden="true">
            <label for="website">Website (leave blank)</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
          </div>

          <!-- Name & Email row -->
          <div class="form-row mb-3">
            <div class="form-group">
              <label class="form-label" for="name">
                Full Name <span style="color:var(--accent);" aria-label="required">*</span>
              </label>
              <input
                type="text"
                id="name"
                name="name"
                class="form-control <?= isset($field_errors['name']) ? 'error' : '' ?>"
                value="<?= e($form_data['name']) ?>"
                placeholder="Jane Creator"
                maxlength="100"
                required
                autocomplete="name"
                aria-required="true"
                <?= isset($field_errors['name']) ? 'aria-describedby="name-error"' : '' ?>
              >
              <?php if (isset($field_errors['name'])): ?>
                <span class="form-hint" id="name-error" style="color:var(--error);" role="alert">
                  <?= e($field_errors['name']) ?>
                </span>
              <?php endif; ?>
            </div>

            <div class="form-group">
              <label class="form-label" for="email">
                Email Address <span style="color:var(--accent);" aria-label="required">*</span>
              </label>
              <input
                type="email"
                id="email"
                name="email"
                class="form-control <?= isset($field_errors['email']) ? 'error' : '' ?>"
                value="<?= e($form_data['email']) ?>"
                placeholder="jane@example.com"
                maxlength="254"
                required
                autocomplete="email"
                inputmode="email"
                aria-required="true"
                <?= isset($field_errors['email']) ? 'aria-describedby="email-error"' : '' ?>
              >
              <?php if (isset($field_errors['email'])): ?>
                <span class="form-hint" id="email-error" style="color:var(--error);" role="alert">
                  <?= e($field_errors['email']) ?>
                </span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Subject -->
          <div class="form-group mb-3">
            <label class="form-label" for="subject">
              Subject <span style="color:var(--accent);" aria-label="required">*</span>
            </label>
            <input
              type="text"
              id="subject"
              name="subject"
              class="form-control <?= isset($field_errors['subject']) ? 'error' : '' ?>"
              value="<?= e($form_data['subject']) ?>"
              placeholder="Question about my RPM estimate"
              maxlength="150"
              required
              aria-required="true"
              <?= isset($field_errors['subject']) ? 'aria-describedby="subject-error"' : '' ?>
            >
            <?php if (isset($field_errors['subject'])): ?>
              <span class="form-hint" id="subject-error" style="color:var(--error);" role="alert">
                <?= e($field_errors['subject']) ?>
              </span>
            <?php endif; ?>
          </div>

          <!-- Message -->
          <div class="form-group mb-3">
            <label class="form-label" for="message">
              Message <span style="color:var(--accent);" aria-label="required">*</span>
            </label>
            <textarea
              id="message"
              name="message"
              class="form-control <?= isset($field_errors['message']) ? 'error' : '' ?>"
              placeholder="Tell us how we can help..."
              maxlength="3000"
              required
              aria-required="true"
              aria-describedby="msg-hint <?= isset($field_errors['message']) ? 'message-error' : '' ?>"
            ><?= e($form_data['message']) ?></textarea>
            <?php if (isset($field_errors['message'])): ?>
              <span class="form-hint" id="message-error" style="color:var(--error);" role="alert">
                <?= e($field_errors['message']) ?>
              </span>
            <?php endif; ?>
            <span class="form-hint" id="msg-hint">Max 3,000 characters.</span>
          </div>

          <!-- Submit -->
          <div class="form-submit">
            <button type="submit" class="btn btn-primary btn-lg">
              Send Message →
            </button>
          </div>

        </form>
        <?php endif; ?>

      </div><!-- /.form-page -->
    </div><!-- /.container -->
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>
