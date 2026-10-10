<?php
/**
 * TikRPM.online – Homepage
 * Main calculator + in-depth educational guide.
 */

$page_title       = 'TikTok RPM Calculator – Estimate Creator Rewards Earnings';
$meta_description = 'Free TikTok RPM calculator for the Creator Rewards Program. Enter your video views and instantly estimate qualified views, total earnings, and daily & monthly revenue projections.';
$meta_keywords    = 'TikTok RPM calculator, TikTok creator rewards earnings, TikTok qualified views calculator, TikTok RPM rate 2026, creator fund earnings estimator';
$schema_type      = 'SoftwareApplication';

$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
];

// ── Fetch niche RPM rates from DB for calculator ──────────────
require_once 'includes/config.php';
require_once 'includes/db.php';
$rpm_niches = db_rows(
    'SELECT id, niche, icon, min_rpm, avg_rpm, max_rpm
     FROM rpm_rates ORDER BY sort_order, id'
);

require_once 'includes/header.php';
?>

<main id="main-content">

  <!-- ── Hero ─────────────────────────────────────────────────── -->
  <section class="hero" aria-label="Page introduction">
    <div class="container">
      <div class="hero-content fade-in-up">
        <div class="hero-badge">
          <span class="live-dot" aria-hidden="true"></span>
          Free &amp; Updated for 2026
        </div>
        <h1>TikTok <span>RPM Calculator</span><br>for Creator Rewards</h1>
        <p>Estimate your TikTok earnings in seconds. Enter your views, qualified rate, and RPM to get an instant breakdown of projected revenue.</p>

        <div class="hero-stats" role="list" aria-label="Site statistics">
          <div class="hero-stat" role="listitem">
            <span>$0.10–$2.50</span>
            <small>RPM Range</small>
          </div>
          <div class="hero-stat" role="listitem">
            <span>1,000+</span>
            <small>Daily Users</small>
          </div>
          <div class="hero-stat" role="listitem">
            <span>100% Free</span>
            <small>No Sign-up</small>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Calculator Section ────────────────────────────────────── -->
  <section class="section section--alt" id="calculator" aria-labelledby="calc-heading">
    <div class="container">

      <div class="calc-wrapper" role="region" aria-label="TikTok RPM Calculator">

        <!-- Calculator Header -->
        <div class="calc-header">
          <h2 id="calc-heading">⚡ TikTok RPM &amp; Earnings Calculator</h2>
          <p>Real-time estimates based on the Creator Rewards Program formula</p>
        </div>

        <div class="calc-body">

          <!-- Inputs -->
          <div class="calc-grid" role="form" aria-label="Calculator inputs">

            <!-- Niche Selector — NEW -->
            <div class="form-group" style="grid-column:1/-1;">
              <label class="form-label" for="nicheSelect">
                Content Niche
                <span style="font-weight:400;color:var(--text-light);text-transform:none;font-size:.75rem;margin-left:.4rem;">(auto-fills RPM · you can still adjust manually)</span>
              </label>
              <select id="nicheSelect" class="form-control" aria-describedby="nicheHint">
                <option value="">— Select your niche to auto-fill RPM —</option>
                <?php foreach ($rpm_niches as $n): ?>
                <option
                  value="<?= (int)$n['id'] ?>"
                  data-min="<?= e($n['min_rpm']) ?>"
                  data-avg="<?= e($n['avg_rpm']) ?>"
                  data-max="<?= e($n['max_rpm']) ?>"
                  data-niche="<?= e($n['niche']) ?>">
                  <?= e($n['icon']) ?> <?= e($n['niche']) ?> &nbsp;($<?= e($n['min_rpm']) ?>–$<?= e($n['max_rpm']) ?>)
                </option>
                <?php endforeach; ?>
              </select>
              <!-- Niche RPM badge strip — shown after selection -->
              <div id="nicheBadges" style="display:none;margin-top:.5rem;display:none;align-items:center;gap:.5rem;flex-wrap:wrap;">
                <span style="font-size:.75rem;color:var(--text-muted);">Reported range:</span>
                <span id="badge-min"  style="background:#fef3c7;color:#92400e;padding:.2rem .55rem;border-radius:100px;font-size:.75rem;font-weight:700;"></span>
                <span style="color:var(--text-light);font-size:.8rem;">→</span>
                <span id="badge-avg"  style="background:#dcfce7;color:#166534;padding:.2rem .55rem;border-radius:100px;font-size:.75rem;font-weight:700;border:1.5px solid #86efac;"></span>
                <span style="color:var(--text-light);font-size:.8rem;">→</span>
                <span id="badge-max"  style="background:#dbeafe;color:#1e40af;padding:.2rem .55rem;border-radius:100px;font-size:.75rem;font-weight:700;"></span>
                <span id="badge-note" style="font-size:.72rem;color:var(--text-light);margin-left:.25rem;"></span>
              </div>
              <span class="form-hint" id="nicheHint">
                Rates based on 2026 creator community reports — <a href="<?= BASE_PATH ?>/blog" style="color:var(--accent);">see niche guide</a>
              </span>
            </div>

            <!-- Total Views -->
            <div class="form-group">
              <label class="form-label" for="totalViews">Total Video Views</label>
              <input
                type="number"
                id="totalViews"
                class="form-control"
                placeholder="e.g. 500000"
                min="0"
                step="1000"
                inputmode="numeric"
                aria-describedby="totalViewsHint"
                value="500000"
              >
              <span class="form-hint" id="totalViewsHint">
                Enter raw view count from your TikTok analytics
              </span>
            </div>

            <!-- Qualified Views % -->
            <div class="form-group">
              <label class="form-label" for="qualifiedPct">Qualified Views %</label>
              <input
                type="number"
                id="qualifiedPct"
                class="form-control"
                placeholder="e.g. 60"
                min="1"
                max="100"
                step="1"
                value="60"
                inputmode="numeric"
                aria-describedby="qualifiedPctHint"
              >
              <span class="form-hint" id="qualifiedPctHint">
                Typical range: 40%–80% · <a href="#what-are-qualified-views">What counts?</a>
              </span>
            </div>

            <!-- RPM Slider — spans full width -->
            <div class="form-group rpm-row">
              <label class="form-label" for="rpmSlider">
                RPM Rate —
                <strong style="color:var(--accent);" id="rpmDisplay">$0.60</strong>
                per 1,000 qualified views
              </label>
              <div class="rpm-controls">
                <div class="rpm-slider-wrap">
                  <input
                    type="range"
                    id="rpmSlider"
                    min="0.10"
                    max="2.50"
                    step="0.05"
                    value="0.60"
                    aria-valuemin="0.10"
                    aria-valuemax="2.50"
                    aria-valuenow="0.60"
                    aria-label="RPM slider"
                  >
                  <div class="rpm-range-labels" aria-hidden="true">
                    <span>$0.10 (Low)</span>
                    <span>$0.60 (Avg)</span>
                    <span>$2.50 (High)</span>
                  </div>
                </div>
                <input
                  type="number"
                  id="rpmNumber"
                  class="form-control rpm-number"
                  min="0.10"
                  max="2.50"
                  step="0.05"
                  value="0.60"
                  aria-label="RPM exact value"
                >
              </div>
            </div>

          </div><!-- /.calc-grid -->

          <!-- ── Results Panel ──────────────────────────────────── -->
          <div class="calc-results" id="calcResults" aria-live="polite" aria-label="Earnings estimates">
            <p class="result-label" style="margin-bottom:.85rem;font-size:.8rem;">📊 Earnings Estimate</p>
            <div class="results-grid">

              <div class="result-card">
                <div class="result-label">Qualified Views</div>
                <div class="result-value" id="resQualViews">300,000</div>
                <div class="result-sub">of 500,000 total views</div>
              </div>

              <div class="result-card highlight">
                <div class="result-label">Total Estimated Earnings</div>
                <div class="result-value" id="resTotalEarnings">$180.00</div>
                <div class="result-sub" id="resTotalSub">at $0.60 RPM</div>
              </div>

              <div class="result-card">
                <div class="result-label">Daily Revenue (est.)</div>
                <div class="result-value" id="resDailyRev">$6.00</div>
                <div class="result-sub">÷ 30 day projection</div>
              </div>

              <div class="result-card">
                <div class="result-label">Monthly Revenue (est.)</div>
                <div class="result-value" id="resMonthlyRev">$180.00</div>
                <div class="result-sub">based on this video's pace</div>
              </div>

            </div><!-- /.results-grid -->

            <p class="form-hint" style="margin-top:.85rem;text-align:center;">
              ⚠️ Estimates only — actual payouts depend on niche, geography, and TikTok's current rates.
            </p>
          </div><!-- /#calcResults -->

          <!-- Action Buttons -->
          <div class="calc-actions">
            <button type="button" id="btnCopy" class="btn btn-teal">
              📋 Copy Breakdown
            </button>
            <button type="button" id="btnReset" class="btn btn-secondary">
              ↺ Reset
            </button>
          </div>

        </div><!-- /.calc-body -->
      </div><!-- /.calc-wrapper -->

    </div><!-- /.container -->
  </section>

  <!-- In-Article Ad (hidden until AdSense approved) -->
  <?php if (ADSENSE_ENABLED): ?>
  <div class="ad-wrapper section--alt" style="padding-bottom:0;">
    <div class="container">
      <ins class="adsbygoogle ad-slot ad-slot--in-article"
           style="display:block;text-align:center"
           data-ad-layout="in-article"
           data-ad-format="fluid"
           data-ad-client="<?= e(ADSENSE_CLIENT) ?>"
           data-ad-slot="IN_ARTICLE_SLOT_ID"></ins>
      <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── Educational Guide ─────────────────────────────────────── -->
  <article class="section" aria-label="Educational guide">
    <div class="container" style="max-width:860px;">

      <header class="section-header">
        <span class="section-label">Complete Guide</span>
        <h2>Understanding TikTok RPM &amp; the Creator Rewards Program</h2>
        <p>Everything you need to know about how TikTok pays creators and what the numbers really mean.</p>
      </header>

      <div class="content-area">

        <h2>What Is TikTok RPM?</h2>
        <p>
          <strong>RPM (Revenue Per Mille)</strong> is the amount a creator earns for every 1,000
          <em>qualified</em> views on their TikTok content. Unlike CPM — which is the cost advertisers
          pay per 1,000 ad impressions — RPM is the creator's take-home figure after TikTok's platform
          cut. In practice, RPM is always lower than CPM because TikTok retains a portion of the
          advertising revenue before distributing earnings.
        </p>
        <p>
          Under the <strong>Creator Rewards Program</strong> (the evolution of the original Creator Fund),
          TikTok pays eligible creators based on a combination of ad revenue share, engagement metrics,
          and content quality scores. Reported RPM rates in 2025–2026 typically fall between
          <strong>$0.10 and $2.50 per 1,000 qualified views</strong>, with most creators averaging
          around $0.40–$0.80 across niches.
        </p>

        <h2>RPM vs. CPM — What's the Difference?</h2>
        <div class="table-wrap mt-2 mb-3">
          <table>
            <thead>
              <tr>
                <th>Metric</th>
                <th>Stands For</th>
                <th>Who Uses It</th>
                <th>What It Measures</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>RPM</td>
                <td>Revenue Per Mille</td>
                <td>Creators</td>
                <td>Creator earnings per 1,000 qualified views (after platform cut)</td>
              </tr>
              <tr>
                <td>CPM</td>
                <td>Cost Per Mille</td>
                <td>Advertisers</td>
                <td>Advertiser spend per 1,000 ad impressions</td>
              </tr>
              <tr>
                <td>eCPM</td>
                <td>Effective CPM</td>
                <td>Platforms</td>
                <td>Blended ad revenue rate across mixed formats</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="info-box teal mb-3">
          <strong>Key Insight:</strong> TikTok's Creator Rewards RPM is separate from brand deal RPM.
          Sponsored content and brand deals often pay <em>10–50× more</em> than Creator Rewards alone,
          which is why diversifying income is critical for full-time creators.
        </div>

        <h2 id="what-are-qualified-views">What Are "Qualified Views"?</h2>
        <p>
          Not every view on your TikTok video counts toward Creator Rewards earnings. TikTok filters
          views through a <strong>quality and authenticity check</strong> before they qualify for monetization.
          A view is generally considered "qualified" when it meets all of the following criteria:
        </p>
        <ul>
          <li>The viewer watches a meaningful portion of the video (typically more than a few seconds, with longer watch time weighted more heavily).</li>
          <li>The view originates from a real, human account — bot traffic and self-views are excluded.</li>
          <li>The viewer is located in an <strong>eligible country</strong> where TikTok monetization is active (primarily the US, UK, Germany, France, Japan, South Korea, and Brazil).</li>
          <li>The video itself meets TikTok's Community Guidelines and Creator Rewards eligibility requirements (original content, minimum 1 minute, under specific accounts).</li>
          <li>The content does not contain copyrighted music claimed by a third party in a way that blocks monetization.</li>
        </ul>
        <p>
          On average, creators report that <strong>40%–75% of total views qualify</strong> for
          monetization. Our calculator defaults to 60%, which represents a realistic mid-range
          estimate. Creators in high-value niches with global audiences often see higher qualification
          rates, while entertainment or lip-sync content (which attracts more international or
          casual views) tends to qualify at a lower rate.
        </p>

        <h2>How to Use This Calculator</h2>
        <div class="steps mt-2 mb-3">
          <div class="step">
            <div class="step-num" aria-hidden="true">1</div>
            <div class="step-body">
              <h4>Enter Your Total Video Views</h4>
              <p>Open TikTok Studio or your Creator Dashboard and find the total view count for a specific video or your average per-video view count. Enter that number in the "Total Video Views" field.</p>
            </div>
          </div>
          <div class="step">
            <div class="step-num" aria-hidden="true">2</div>
            <div class="step-body">
              <h4>Set Your Qualified Views Percentage</h4>
              <p>If you have access to TikTok's Creator Rewards dashboard, you can see your actual qualified view count. Divide that by total views and enter the percentage. Otherwise, use 55–65% as a starting estimate.</p>
            </div>
          </div>
          <div class="step">
            <div class="step-num" aria-hidden="true">3</div>
            <div class="step-body">
              <h4>Adjust the RPM Slider</h4>
              <p>Select an RPM rate that matches your niche and audience geography. Finance and tech creators may see $1.00–$2.50, while general entertainment is closer to $0.20–$0.60. Check our <a href="/blog">RPM by niche guide</a> for detailed benchmarks.</p>
            </div>
          </div>
          <div class="step">
            <div class="step-num" aria-hidden="true">4</div>
            <div class="step-body">
              <h4>Read Your Earnings Estimate</h4>
              <p>The calculator instantly displays your qualified view count, total estimated earnings, and a daily/monthly revenue projection based on those views. Use "Copy Breakdown" to save the result to your clipboard.</p>
            </div>
          </div>
        </div>

        <h2>What Affects Your TikTok RPM Rate?</h2>
        <p>RPM is not a fixed number — it fluctuates based on multiple variables that TikTok weighs
        when calculating your payout. Understanding these levers helps you optimize your content strategy:</p>

        <div class="cards-grid mt-2 mb-3">
          <div class="card">
            <div class="card-icon" aria-hidden="true">🌍</div>
            <h4>Audience Geography</h4>
            <p>Views from the US, UK, and Western Europe command the highest RPMs ($1.00–$2.50). Views from Southeast Asia or Latin America typically generate much lower rates ($0.10–$0.30).</p>
          </div>
          <div class="card">
            <div class="card-icon" aria-hidden="true">📚</div>
            <h4>Content Niche</h4>
            <p>Finance, business, tech, real estate, and legal niches attract premium advertisers and yield the highest RPMs. Entertainment, dance, and comedy see lower rates due to less-targeted ad inventory.</p>
          </div>
          <div class="card">
            <div class="card-icon" aria-hidden="true">⏱️</div>
            <h4>Video Length</h4>
            <p>Longer videos (1–5 minutes) qualify for more ad placements and earn higher RPMs. Videos under 60 seconds are ineligible for the Creator Rewards Program entirely.</p>
          </div>
          <div class="card">
            <div class="card-icon" aria-hidden="true">💬</div>
            <h4>Engagement Quality</h4>
            <p>High completion rate, comments, shares, and saves signal quality content. TikTok's algorithm rewards high-engagement videos with better distribution and higher RPM scores.</p>
          </div>
          <div class="card">
            <div class="card-icon" aria-hidden="true">📅</div>
            <h4>Seasonal Trends</h4>
            <p>Q4 (October–December) typically has the highest RPMs due to holiday advertiser spending. Q1 often sees the lowest rates as ad budgets reset after the new year.</p>
          </div>
          <div class="card">
            <div class="card-icon" aria-hidden="true">🏆</div>
            <h4>Account Standing</h4>
            <p>Creators in good standing with high-quality content scores and low community guideline violations often receive preferential RPM rates under TikTok's tiered reward system.</p>
          </div>
        </div>

        <h2>TikTok Creator Rewards Program Requirements</h2>
        <p>To be eligible for the Creator Rewards Program (CRP), your account must meet these baseline criteria:</p>
        <ul>
          <li><strong>Age:</strong> 18+ years old</li>
          <li><strong>Followers:</strong> Minimum 10,000 followers</li>
          <li><strong>Views:</strong> At least 100,000 video views in the last 30 days</li>
          <li><strong>Account type:</strong> Personal account (not Business account)</li>
          <li><strong>Location:</strong> Based in an eligible country</li>
          <li><strong>Content:</strong> Original content, at least 1 minute long</li>
          <li><strong>Standing:</strong> Account in good standing with TikTok's Community Guidelines</li>
        </ul>

        <div class="info-box mt-2 mb-3">
          <strong>Note:</strong> The Creator Rewards Program replaced the older Creator Fund in 2023.
          Payout rates are reportedly <em>3–8× higher</em> than the original Creator Fund, making it
          significantly more lucrative for eligible creators.
        </div>

        <h2>Realistic RPM Benchmarks by Niche</h2>
        <div class="table-wrap mt-2 mb-3">
          <table>
            <thead>
              <tr>
                <th>Niche</th>
                <th>Typical RPM Range</th>
                <th>Primary Reason</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>Finance / Investing</td><td>$1.50 – $2.50</td><td>High-value advertiser bids</td></tr>
              <tr><td>Business / Entrepreneurship</td><td>$1.00 – $2.00</td><td>B2B audience value</td></tr>
              <tr><td>Technology / Software</td><td>$0.80 – $1.80</td><td>Tech product advertisers</td></tr>
              <tr><td>Health / Wellness</td><td>$0.60 – $1.20</td><td>Supplement/health brands</td></tr>
              <tr><td>Education / How-To</td><td>$0.50 – $1.00</td><td>Strong completion rates</td></tr>
              <tr><td>Lifestyle / Vlog</td><td>$0.30 – $0.70</td><td>Broad audience, mixed ad fit</td></tr>
              <tr><td>Entertainment / Comedy</td><td>$0.20 – $0.55</td><td>Lower ad targeting precision</td></tr>
              <tr><td>Dance / Music</td><td>$0.10 – $0.40</td><td>Global audience, lower US %</td></tr>
            </tbody>
          </table>
        </div>

        <!-- ── FAQ Accordion ──────────────────────────────────────── -->
        <h2>Frequently Asked Questions</h2>
        <div class="accordion mt-2" role="list">

          <div class="accordion-item" role="listitem">
            <button class="accordion-trigger" aria-expanded="false" aria-controls="faq1" id="faq1-btn">
              How does TikTok calculate Creator Rewards payouts?
              <span class="accordion-icon" aria-hidden="true">+</span>
            </button>
            <div class="accordion-panel" id="faq1" role="region" aria-labelledby="faq1-btn">
              <div class="accordion-panel-inner">
                TikTok calculates Creator Rewards using a formula that multiplies your qualified view count by your RPM rate. The RPM itself is determined dynamically based on ad auction prices in your content niche, your audience's geographic distribution, your content quality score, and overall supply/demand for ad inventory during the period your video gained traction. TikTok does not publicly disclose the exact weighting of each factor.
              </div>
            </div>
          </div>

          <div class="accordion-item" role="listitem">
            <button class="accordion-trigger" aria-expanded="false" aria-controls="faq2" id="faq2-btn">
              Why are my Creator Rewards earnings lower than the calculator estimates?
              <span class="accordion-icon" aria-hidden="true">+</span>
            </button>
            <div class="accordion-panel" id="faq2" role="region" aria-labelledby="faq2-btn">
              <div class="accordion-panel-inner">
                Several factors can push actual earnings below estimates: a lower-than-expected qualified view percentage, a niche with weaker advertiser demand, a predominantly international audience (outside the US/UK), videos under-performing the average completion rate, or temporary RPM drops due to seasonal ad spend patterns. This calculator provides estimates — always cross-check with your actual TikTok Creator Rewards dashboard.
              </div>
            </div>
          </div>

          <div class="accordion-item" role="listitem">
            <button class="accordion-trigger" aria-expanded="false" aria-controls="faq3" id="faq3-btn">
              What is a good RPM on TikTok in 2026?
              <span class="accordion-icon" aria-hidden="true">+</span>
            </button>
            <div class="accordion-panel" id="faq3" role="region" aria-labelledby="faq3-btn">
              <div class="accordion-panel-inner">
                For 2025–2026, an RPM of $0.50–$1.00 is considered average across niches. Anything above $1.00 is good, and above $1.50 is excellent. Finance and investing creators in the US often report RPMs of $1.50–$2.50. If your RPM is consistently below $0.30, consider whether your content qualifies correctly, whether your audience is primarily from high-CPM regions, and whether your videos are long enough for full monetization.
              </div>
            </div>
          </div>

          <div class="accordion-item" role="listitem">
            <button class="accordion-trigger" aria-expanded="false" aria-controls="faq4" id="faq4-btn">
              How many views do I need to make $1,000/month on TikTok?
              <span class="accordion-icon" aria-hidden="true">+</span>
            </button>
            <div class="accordion-panel" id="faq4" role="region" aria-labelledby="faq4-btn">
              <div class="accordion-panel-inner">
                At an average RPM of $0.60 and a 60% qualified view rate, you'd need roughly 2.78 million total monthly views to earn $1,000. At a higher RPM of $1.20, that drops to about 1.39 million views. Use the calculator above to model your specific scenario — plug in your target earnings and work backwards by adjusting views until you hit your goal.
              </div>
            </div>
          </div>

          <div class="accordion-item" role="listitem">
            <button class="accordion-trigger" aria-expanded="false" aria-controls="faq5" id="faq5-btn">
              Does TikTok Live count toward Creator Rewards earnings?
              <span class="accordion-icon" aria-hidden="true">+</span>
            </button>
            <div class="accordion-panel" id="faq5" role="region" aria-labelledby="faq5-btn">
              <div class="accordion-panel-inner">
                No. TikTok Live views are not counted toward Creator Rewards Program earnings. LIVE monetization is a separate program that relies on virtual gifts sent by viewers. The Creator Rewards Program specifically covers standard video content that meets minimum length and eligibility requirements. This calculator only estimates earnings from the standard Creator Rewards Program, not LIVE gifts.
              </div>
            </div>
          </div>

          <div class="accordion-item" role="listitem">
            <button class="accordion-trigger" aria-expanded="false" aria-controls="faq6" id="faq6-btn">
              Can I use this calculator for TikTok Series or Subscription content?
              <span class="accordion-icon" aria-hidden="true">+</span>
            </button>
            <div class="accordion-panel" id="faq6" role="region" aria-labelledby="faq6-btn">
              <div class="accordion-panel-inner">
                TikTok Series (paywalled content) and Subscriptions have different payout structures based on subscription fees rather than RPM. This calculator is specifically designed for the Creator Rewards Program (ad-based revenue). For Series and Subscriptions, multiply your subscriber count by your subscription price and TikTok's platform fee (typically 20–30%) to estimate those earnings separately.
              </div>
            </div>
          </div>

        </div><!-- /.accordion -->

      </div><!-- /.content-area -->
    </div><!-- /.container -->
  </article>

</main><!-- /#main-content -->

<?php
$page_scripts = '<script src="' . ASSETS_URL . '/js/calculator.js" defer></script>';
require_once 'includes/footer.php';
?>
