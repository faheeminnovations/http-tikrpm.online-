<?php
$page_title       = 'About TikRPM – Our Methodology & Creator Earnings Formula';
$meta_description = 'Learn how TikRPM.online calculates TikTok Creator Rewards earnings, the formulas we use, our data sources, and the team behind the free RPM calculator.';
$meta_keywords    = 'about TikRPM, TikTok RPM methodology, how TikTok earnings calculated, creator rewards formula';
$schema_type      = 'WebPage';

$breadcrumbs = [
    ['name' => 'Home',  'url' => '/'],
    ['name' => 'About', 'url' => '/about'],
];

require_once 'includes/header.php';
?>

<main id="main-content">

  <!-- Page Hero -->
  <section class="page-hero" aria-label="Page introduction">
    <div class="container">
      <h1>About TikRPM.online</h1>
      <p>A transparent look at who we are, how our calculator works, and the data behind every estimate.</p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
      <a href="<?= BASE_PATH ?>/">Home</a>
      <span class="breadcrumb-sep" aria-hidden="true">›</span>
      <span aria-current="page">About</span>
    </nav>
  </div>

  <!-- About Content -->
  <section class="section">
    <div class="container" style="max-width:860px;">
      <div class="content-area">

        <h2>What Is TikRPM?</h2>
        <p>
          TikRPM.online is a free, independent online tool that helps TikTok creators estimate their
          potential earnings through the <strong>Creator Rewards Program</strong>. We built it because
          the landscape of creator monetization data is fragmented, confusing, and often buried
          behind vague platform dashboards. Our goal is simple: give every creator a clear,
          transparent, and instant way to model their revenue.
        </p>
        <p>
          We are not affiliated with TikTok, ByteDance, or any of their subsidiaries. We are an
          independent utility — built by creators, for creators.
        </p>

        <h2>Our Calculation Methodology</h2>
        <p>
          The TikRPM calculator uses a straightforward, publicly derived formula based on how
          TikTok's Creator Rewards Program distributes earnings. Here is how each value is computed:
        </p>

        <div class="table-wrap mt-2 mb-3">
          <table>
            <thead>
              <tr>
                <th>Step</th>
                <th>Formula</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1. Qualified Views</td>
                <td>Total Views × (Qualified %÷100)</td>
                <td>Views that pass TikTok's authenticity and eligibility filters</td>
              </tr>
              <tr>
                <td>2. Total Earnings</td>
                <td>(Qualified Views ÷ 1,000) × RPM</td>
                <td>RPM is applied per 1,000 qualified views, not total views</td>
              </tr>
              <tr>
                <td>3. Daily Revenue</td>
                <td>Total Earnings ÷ 30</td>
                <td>Amortised daily estimate over a standard 30-day month</td>
              </tr>
              <tr>
                <td>4. Monthly Revenue</td>
                <td>Total Earnings</td>
                <td>The per-video earnings expressed as a monthly projection</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="info-box teal mb-3">
          <strong>Why RPM, not CPM?</strong> CPM is what advertisers pay. RPM is what creators
          actually receive after TikTok's platform revenue share. RPM is always lower than CPM.
          Our calculator uses RPM to reflect real creator take-home figures.
        </div>

        <h2>Our Data Sources</h2>
        <p>
          The RPM ranges shown in our calculator and guides ($0.10–$2.50) are derived from:
        </p>
        <ul>
          <li>Publicly disclosed earnings reports from verified TikTok creators on YouTube, Reddit (r/Tiktokhelp, r/NewTubers), and Twitter/X.</li>
          <li>Creator-shared screenshots and breakdowns from TikTok Studio dashboards.</li>
          <li>Industry analyses published by social media marketing researchers and creator economy newsletters.</li>
          <li>TikTok's own Creator Rewards Program documentation and publicly stated policy changes.</li>
        </ul>
        <p>
          We do not have API access to TikTok's internal payout systems and cannot verify any
          individual creator's exact RPM. All figures are <strong>estimates based on community-reported data</strong>
          and should be treated as approximations, not guarantees.
        </p>

        <h2>Why We Built This Tool</h2>
        <p>
          When the Creator Rewards Program launched, creators were frustrated by the opacity of the
          payout system. Unlike YouTube's analytics, TikTok's earnings dashboard provides limited
          context for why your RPM is what it is, or how to improve it.
        </p>
        <p>
          TikRPM.online was created to fill that gap — giving creators a starting point to model
          different scenarios, compare niches, and set realistic income expectations before
          committing to a full-time content strategy.
        </p>

        <h2>Limitations &amp; Disclaimer</h2>
        <p>
          Our calculator is a <strong>projection tool</strong>, not an accounting system. Actual
          TikTok Creator Rewards earnings depend on dozens of dynamic factors that we cannot
          model in real time, including:
        </p>
        <ul>
          <li>Real-time ad auction prices in your specific niche</li>
          <li>Viewer geographic distribution (country-level RPM variance)</li>
          <li>TikTok's proprietary content quality score for each video</li>
          <li>Seasonal fluctuations in ad spend (Q4 is highest, Q1 is lowest)</li>
          <li>Platform policy changes that alter payout structures</li>
        </ul>
        <p>
          Always verify your actual earnings in your TikTok Creator Center dashboard. Never make
          financial decisions based solely on our estimates.
        </p>

        <h2>Contact Us</h2>
        <p>
          Have a question about our methodology, found an inaccuracy, or want to suggest a feature?
          We'd love to hear from you. Reach out via our <a href="<?= BASE_PATH ?>/contact">Contact page</a>.
        </p>

      </div><!-- /.content-area -->
    </div><!-- /.container -->
  </section>

</main>

<?php require_once 'includes/footer.php'; ?>
