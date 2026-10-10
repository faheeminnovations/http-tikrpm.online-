<?php
$page_title       = 'Privacy Policy – TikRPM.online';
$meta_description = 'TikRPM.online privacy policy covering data collection, Google AdSense, cookies, Google Analytics, log files, and your rights as a user.';
$meta_keywords    = 'TikRPM privacy policy, data collection, cookies, Google AdSense privacy';
$schema_type      = 'WebPage';

$breadcrumbs = [
    ['name' => 'Home',           'url' => '/'],
    ['name' => 'Privacy Policy', 'url' => '/privacy-policy'],
];

$last_updated = 'January 1, 2026';

require_once 'includes/header.php';
?>

<main id="main-content">

  <section class="page-hero" aria-label="Page introduction">
    <div class="container">
      <h1>Privacy Policy</h1>
      <p>Last updated: <?= e($last_updated) ?></p>
    </div>
  </section>

  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= BASE_PATH ?>/">Home</a>
      <span class="breadcrumb-sep" aria-hidden="true">›</span>
      <span aria-current="page">Privacy Policy</span>
    </nav>
  </div>

  <section class="section">
    <div class="container" style="max-width:860px;">
      <div class="content-area">

        <div class="info-box mb-3">
          This Privacy Policy describes how <strong>TikRPM.online</strong> ("we", "us", or "our")
          collects, uses, and shares information when you visit our website. By using TikRPM.online,
          you agree to the practices described in this policy.
        </div>

        <h2>1. Information We Collect</h2>

        <h3>a) Information You Provide</h3>
        <p>
          When you use the <strong>Contact form</strong>, we collect your name, email address,
          subject, and message. This information is used solely to respond to your enquiry.
          We do not store contact form submissions in a database; messages are forwarded
          directly to our email inbox.
        </p>

        <h3>b) Automatically Collected Information (Log Files)</h3>
        <p>
          Like most websites, TikRPM.online automatically collects certain information when you
          visit, including:
        </p>
        <ul>
          <li>IP address (anonymised where possible)</li>
          <li>Browser type and version</li>
          <li>Operating system</li>
          <li>Referring URL (the page you visited before ours)</li>
          <li>Pages visited and time spent on each page</li>
          <li>Date and time of your visit</li>
        </ul>
        <p>
          This data is collected via server access logs and third-party analytics tools.
          It is used in aggregate form to understand site usage, diagnose technical problems,
          and improve our content. Log files are retained for a maximum of <strong>90 days</strong>.
        </p>

        <h3>c) Calculator Inputs</h3>
        <p>
          All calculator inputs (total views, qualified view percentage, and RPM rate) are
          processed <strong>entirely in your browser</strong> using JavaScript. No calculator
          data is transmitted to our servers, stored, or associated with your identity.
        </p>

        <h2>2. Cookies</h2>
        <p>
          TikRPM.online uses cookies to improve your browsing experience and serve relevant
          advertising. Below is a description of each cookie type we use:
        </p>

        <div class="table-wrap mt-2 mb-3">
          <table>
            <thead>
              <tr>
                <th>Cookie Type</th>
                <th>Purpose</th>
                <th>Provider</th>
                <th>Duration</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Session (PHPSESSID)</td>
                <td>Maintains CSRF security token for the contact form</td>
                <td>TikRPM.online</td>
                <td>Session (deleted on close)</td>
              </tr>
              <tr>
                <td>Analytics (_ga, _gid)</td>
                <td>Tracks aggregate page views and user behaviour</td>
                <td>Google Analytics</td>
                <td>Up to 2 years</td>
              </tr>
              <tr>
                <td>Advertising (IDE, DSID)</td>
                <td>Personalises and measures ad performance</td>
                <td>Google AdSense / DoubleClick</td>
                <td>Up to 13 months</td>
              </tr>
            </tbody>
          </table>
        </div>

        <p>
          You can control or disable cookies through your browser settings. Note that
          disabling cookies may affect the functionality of certain features on this site.
          For instructions on managing cookies, visit your browser's help documentation.
        </p>

        <h2>3. Google AdSense</h2>
        <p>
          TikRPM.online uses <strong>Google AdSense</strong> to display advertisements. Google
          AdSense uses cookies and web beacons to serve ads based on your prior visits to our
          site and other sites on the internet. Google's use of advertising cookies enables
          it and its partners to serve ads to you based on your visit to our site and/or
          other sites on the internet.
        </p>
        <p>
          You may opt out of personalised advertising by visiting
          <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer">Google Ads Settings</a>
          or by visiting <a href="https://www.aboutads.info" target="_blank" rel="noopener noreferrer">aboutads.info</a>.
        </p>
        <p>
          Google's use of the DoubleClick cookie enables Google and its partners to serve
          ads based on your visits to this site. For more information, see
          <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener noreferrer">How Google Uses Advertising Data</a>.
        </p>

        <h2>4. Google Analytics</h2>
        <p>
          We use <strong>Google Analytics 4</strong> to understand how visitors use our site.
          Google Analytics collects anonymised usage data including pages visited, session
          duration, and traffic sources. IP addresses are anonymised before storage.
          Data is processed in accordance with
          <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Google's Privacy Policy</a>.
        </p>
        <p>
          You can opt out of Google Analytics by installing the
          <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">Google Analytics Opt-out Browser Add-on</a>.
        </p>

        <h2>5. Third-Party Links</h2>
        <p>
          Our website may contain links to external websites (e.g., TikTok Creator Academy,
          support documentation). We are not responsible for the privacy practices or content
          of those third-party sites. We encourage you to review the privacy policies of any
          external site you visit.
        </p>

        <h2>6. Children's Privacy</h2>
        <p>
          TikRPM.online is not directed at children under the age of 13. We do not knowingly
          collect personal information from children. If you believe we have inadvertently
          collected data from a child, please contact us immediately so we can delete it.
        </p>

        <h2>7. Data Retention</h2>
        <p>
          Contact form data forwarded to our email is retained for a maximum of
          <strong>12 months</strong> before being deleted. Server log files are retained for
          up to <strong>90 days</strong>. We do not maintain a database of user information.
        </p>

        <h2>8. Your Rights</h2>
        <p>
          Depending on your jurisdiction, you may have the following rights regarding your
          personal data:
        </p>
        <ul>
          <li><strong>Access:</strong> Request a copy of personal data we hold about you.</li>
          <li><strong>Correction:</strong> Request correction of inaccurate data.</li>
          <li><strong>Deletion:</strong> Request deletion of your personal data.</li>
          <li><strong>Objection:</strong> Object to processing based on legitimate interests.</li>
          <li><strong>Portability:</strong> Request your data in a portable format.</li>
        </ul>
        <p>
          To exercise any of these rights, contact us at
          <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>.
          We will respond within 30 days.
        </p>

        <h2>9. Changes to This Policy</h2>
        <p>
          We may update this Privacy Policy from time to time. Changes will be posted on
          this page with an updated "Last updated" date. Continued use of TikRPM.online after
          changes constitutes acceptance of the updated policy.
        </p>

        <h2>10. Contact</h2>
        <p>
          If you have questions about this Privacy Policy, please contact us at:<br>
          <strong><?= e(SITE_NAME) ?>.com</strong><br>
          Email: <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>
        </p>

      </div><!-- /.content-area -->
    </div>
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>
