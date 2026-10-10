<?php
$page_title       = 'Terms of Service – TikRPM.online';
$meta_description = 'Terms of Service for TikRPM.online. Independent TikTok RPM calculator — not affiliated with TikTok or ByteDance. Usage terms, disclaimers, and legal notices.';
$meta_keywords    = 'TikRPM terms of service, TikTok calculator disclaimer, not affiliated TikTok';
$schema_type      = 'WebPage';

$breadcrumbs = [
    ['name' => 'Home',             'url' => '/'],
    ['name' => 'Terms of Service', 'url' => '/terms'],
];

$last_updated = 'January 1, 2026';

require_once 'includes/header.php';
?>

<main id="main-content">

  <section class="page-hero" aria-label="Page introduction">
    <div class="container">
      <h1>Terms of Service</h1>
      <p>Last updated: <?= e($last_updated) ?></p>
    </div>
  </section>

  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= BASE_PATH ?>/">Home</a>
      <span class="breadcrumb-sep" aria-hidden="true">›</span>
      <span aria-current="page">Terms of Service</span>
    </nav>
  </div>

  <section class="section">
    <div class="container" style="max-width:860px;">
      <div class="content-area">

        <!-- Affiliation Disclaimer — prominent -->
        <div class="info-box" style="background:#fff8f0;border-left-color:#f59e0b;margin-bottom:2rem;">
          <strong>⚠️ Independent Tool — Not Affiliated with TikTok</strong><br>
          TikRPM.online is an <strong>independent, third-party utility tool</strong> and is
          <strong>not affiliated, endorsed, sponsored, licensed, or associated with
          TikTok, ByteDance Ltd., or any of their subsidiaries, affiliates, officers,
          employees, agents, partners, or licensors</strong> in any way whatsoever.
          All product names, logos, and brands are the property of their respective owners.
          "TikTok" is a registered trademark of ByteDance Ltd.
        </div>

        <h2>1. Acceptance of Terms</h2>
        <p>
          By accessing or using TikRPM.online (the "Site"), you agree to be bound by these
          Terms of Service ("Terms"). If you do not agree with any part of these Terms,
          you must not use the Site.
        </p>

        <h2>2. Description of Service</h2>
        <p>
          TikRPM.online provides a free online calculator tool designed to help TikTok content
          creators estimate potential earnings through TikTok's Creator Rewards Program.
          The Site also provides educational articles, guides, and informational content
          related to TikTok monetisation.
        </p>
        <p>
          The Service is provided "as is" and "as available", without any warranties of
          any kind, either express or implied.
        </p>

        <h2>3. No Affiliation with TikTok or ByteDance</h2>
        <p>
          TikRPM.online is an independent utility website. We have <strong>no relationship</strong>
          with TikTok Inc., TikTok Ltd., ByteDance Ltd., or any related entity. We do not
          receive data from TikTok's internal systems, we have no access to TikTok's API
          for creator earnings data, and our estimates are not endorsed or verified by TikTok.
        </p>
        <p>
          References to "TikTok", "Creator Rewards Program", "TikTok Studio", or other
          TikTok product names are made purely for descriptive, informational purposes
          under nominative fair use.
        </p>

        <h2>4. Accuracy of Information &amp; Earnings Disclaimer</h2>
        <p>
          All earnings estimates produced by the TikRPM calculator are <strong>approximations
          for informational purposes only</strong>. They are based on publicly reported RPM
          ranges from creator communities and do not constitute financial advice, guaranteed
          income projections, or binding representations of what you will earn.
        </p>
        <p>
          Actual TikTok Creator Rewards earnings depend on many factors including, but not
          limited to: your audience geography, content niche, TikTok's current payout policies,
          video quality scores, seasonal advertising demand, and platform algorithm changes.
          <strong>TikRPM.online is not responsible for any financial decisions made based on
          estimates provided by this tool.</strong>
        </p>

        <h2>5. Intellectual Property</h2>
        <p>
          All original content on TikRPM.online — including text, graphics, logos, user interface
          design, and calculator code — is the intellectual property of TikRPM.online and is
          protected by applicable copyright and intellectual property laws.
        </p>
        <p>
          You may not reproduce, distribute, modify, create derivative works of, publicly
          display, or commercially exploit any content from this Site without our express
          written permission.
        </p>

        <h2>6. Permitted Use</h2>
        <p>You may use TikRPM.online for personal, non-commercial purposes including:</p>
        <ul>
          <li>Using the earnings calculator to estimate your own TikTok revenue.</li>
          <li>Reading educational articles and guides on TikTok monetisation.</li>
          <li>Sharing links to our calculator or articles on social media.</li>
        </ul>

        <h2>7. Prohibited Use</h2>
        <p>You agree not to:</p>
        <ul>
          <li>Scrape, copy, or republish our content without permission.</li>
          <li>Use automated bots or scripts to access the Site.</li>
          <li>Attempt to reverse-engineer, decompile, or extract our source code.</li>
          <li>Use the Site in any way that violates applicable laws or regulations.</li>
          <li>Submit false or misleading information through the contact form.</li>
          <li>Impersonate TikRPM.online or claim affiliation where none exists.</li>
        </ul>

        <h2>8. Third-Party Links</h2>
        <p>
          The Site may contain links to third-party websites (including TikTok's official
          documentation). These links are provided for convenience only. TikRPM.online does
          not endorse, control, or assume responsibility for the content, privacy policies,
          or practices of any third-party websites.
        </p>

        <h2>9. Limitation of Liability</h2>
        <p>
          To the fullest extent permitted by applicable law, TikRPM.online, its owners,
          operators, and contributors shall not be liable for any indirect, incidental,
          special, consequential, or punitive damages arising from your use of — or
          inability to use — the Site or the calculator tool, even if we have been
          advised of the possibility of such damages.
        </p>
        <p>
          Our total liability for any claim arising out of your use of the Site shall
          not exceed USD $0, as the Site is provided entirely free of charge.
        </p>

        <h2>10. Indemnification</h2>
        <p>
          You agree to defend, indemnify, and hold harmless TikRPM.online and its operators
          from any claims, liabilities, damages, costs, and expenses (including reasonable
          legal fees) arising from your use of the Site or violation of these Terms.
        </p>

        <h2>11. Changes to Terms</h2>
        <p>
          We reserve the right to modify these Terms at any time. Updated Terms will be
          posted on this page with a revised "Last updated" date. Your continued use of
          the Site after any changes constitutes your acceptance of the new Terms.
        </p>

        <h2>12. Governing Law</h2>
        <p>
          These Terms shall be governed by and construed in accordance with the laws
          of the applicable jurisdiction, without regard to its conflict of law provisions.
        </p>

        <h2>13. Contact</h2>
        <p>
          If you have questions about these Terms, please contact us:<br>
          <strong><?= e(SITE_NAME) ?>.com</strong><br>
          Email: <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>
        </p>

      </div><!-- /.content-area -->
    </div>
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>
