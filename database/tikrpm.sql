-- ============================================================
--  TikRPM.com — Database Schema
--  Run this in phpMyAdmin → tikrpm database → SQL tab
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- ── Blog Posts ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `slug`        VARCHAR(200)     NOT NULL,
  `title`       VARCHAR(300)     NOT NULL,
  `excerpt`     TEXT             NOT NULL,
  `body`        LONGTEXT         NOT NULL,
  `tag`         VARCHAR(80)      NOT NULL DEFAULT 'General',
  `icon`        VARCHAR(10)      NOT NULL DEFAULT '📝',
  `author`      VARCHAR(100)     NOT NULL DEFAULT 'TikRPM Team',
  `read_time`   VARCHAR(30)      NOT NULL DEFAULT '5 min read',
  `featured`    TINYINT(1)       NOT NULL DEFAULT 0,
  `published`   TINYINT(1)       NOT NULL DEFAULT 1,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug`      (`slug`),
  INDEX  `idx_published`    (`published`),
  INDEX  `idx_featured`     (`featured`),
  INDEX  `idx_created_at`   (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Admin Users ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `username`     VARCHAR(60)   NOT NULL,
  `password`     VARCHAR(255)  NOT NULL,  -- bcrypt hash
  `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Default Admin Account ─────────────────────────────────────
-- Username: admin   Password: Admin@1234  (CHANGE IMMEDIATELY after login)
INSERT INTO `admin_users` (`username`, `password`) VALUES
('admin', '$2y$12$eImiTXuWVxfM37uY4JANjQ9kbzK5bMCaIIRkNXDC1OjyT8HnKY.cO');

-- ── Seed Blog Posts ───────────────────────────────────────────
INSERT INTO `blog_posts`
  (`slug`, `title`, `excerpt`, `body`, `tag`, `icon`, `author`, `read_time`, `featured`, `published`, `created_at`)
VALUES

-- Post 1 (Featured, full body)
(
  'how-to-increase-tiktok-rpm',
  'How to Increase Your TikTok RPM: 10 Proven Strategies for 2025',
  'Your RPM is not fixed. Discover the content, niche, and audience tactics that consistently push TikTok creator RPMs above $1.00 per 1,000 qualified views.',
  '<p>Most new TikTok creators obsess over view counts. But experienced creators know that the metric that actually determines your income is <strong>RPM: Revenue Per Mille</strong> — what you earn for every 1,000 qualified views.</p>
<p>Two creators can have the exact same number of monthly views and earn vastly different amounts purely because their RPM rates differ. A finance creator earning $1.80 RPM on 2 million views earns $3,600. An entertainment creator at $0.25 RPM on the same 2 million views earns just $500. That is a <strong>7× difference in income</strong> from identical traffic.</p>
<h2>1. Niche Into High-Value Advertiser Categories</h2>
<p>The single biggest lever for RPM improvement is your content niche. TikTok RPM is driven by ad auction prices, and advertisers pay far more to reach audiences interested in finance, business, technology, and health than audiences watching comedy or dance content.</p>
<h2>2. Build a US-Heavy Audience</h2>
<p>Audience geography is the second-biggest RPM driver. TikTok advertisers bid significantly more to reach US viewers. A video with 70% US viewers will earn dramatically more than the same view count skewed toward Southeast Asia or Latin America.</p>
<h2>3. Make Videos Longer Than 60 Seconds</h2>
<p>TikTok Creator Rewards requires videos to be at least <strong>60 seconds long</strong> to qualify. Videos in the 1–3 minute range earn higher RPMs because they support mid-roll ad placements and signal higher content quality to TikTok''s algorithm.</p>
<h2>4. Maximise Watch Time and Completion Rate</h2>
<p>TikTok''s content quality score is heavily influenced by average watch time and completion rate. Videos that hold viewer attention all the way through receive better algorithmic distribution and earn premium RPMs.</p>
<h2>5. Post During Peak Ad Spend Periods</h2>
<p>Q4 (October–December) typically has the highest RPMs due to holiday advertising spend. Some creators report Q4 RPMs 2–3× higher than Q1.</p>
<h2>6. Improve Your Content Quality Score</h2>
<p>Use original audio, high-resolution video (1080p+), stable footage, good lighting, clear audio, captions, and avoid recycled or watermarked content from other platforms.</p>
<h2>7. Avoid Copyright Music Conflicts</h2>
<p>Using popular copyrighted music is one of the most common reasons creators see Creator Rewards earnings drop to zero. Use TikTok''s commercially licensed music library or royalty-free music.</p>
<h2>8. Stay Consistent and Build Algorithmic Trust</h2>
<p>Creators who post 4–7 times per week in a consistent niche report more stable RPMs than those posting irregularly across mixed topics.</p>
<h2>9. Engage Your Audience to Drive Comment Velocity</h2>
<p>Ask questions at the end of your videos, respond to early comments, and create content that invites debate or personal stories. High comment velocity tells TikTok''s algorithm your content is resonating.</p>
<h2>10. Diversify Revenue Beyond Creator Rewards</h2>
<p>Treat Creator Rewards as a baseline and stack brand deals, affiliate commissions, digital products, and community memberships on top. A single brand deal with a finance company can pay $500–$5,000 for the same video that earns $80 from Creator Rewards.</p>',
  'Strategy', '📈', 'TikRPM Team', '9 min read', 1, 1, '2024-12-10 08:00:00'
),

-- Post 2
(
  'tiktok-rpm-by-niche-2025',
  'TikTok RPM by Niche in 2025: Finance to Entertainment Breakdown',
  'A comprehensive look at real RPM rates reported by creators across 12 content niches — from $0.12 in pure dance content to $2.40 in personal finance.',
  '<p>One of the most common questions from TikTok creators is: <em>what RPM should I expect in my niche?</em> The answer varies enormously depending on the type of content you create and the audience you attract.</p>
<p>Based on community-reported data from thousands of creators, here is a breakdown of typical RPM ranges by niche in 2025.</p>
<h2>Finance & Investing</h2>
<p>RPM Range: <strong>$1.50 – $2.50</strong>. Finance content attracts premium advertisers — banks, investment apps, insurance companies — who bid aggressively to reach financially engaged audiences. This is consistently the highest-earning niche on TikTok.</p>
<h2>Business & Entrepreneurship</h2>
<p>RPM Range: <strong>$1.00 – $2.00</strong>. B2B software, SaaS tools, and business services advertisers push RPMs to strong levels. Creators who teach business skills, side hustles, or entrepreneurship strategies perform very well.</p>
<h2>Technology & Software</h2>
<p>RPM Range: <strong>$0.80 – $1.80</strong>. Tech reviewers, software tutorials, and AI content creators benefit from high advertiser bids from tech companies targeting early adopters.</p>
<h2>Health & Wellness</h2>
<p>RPM Range: <strong>$0.60 – $1.20</strong>. Supplement brands, fitness apps, and health services drive solid RPMs in this category. Creators focusing on mental health, nutrition, and fitness see particularly strong results.</p>
<h2>Education & How-To</h2>
<p>RPM Range: <strong>$0.50 – $1.00</strong>. Educational content tends to have strong completion rates, which TikTok rewards with better algorithmic distribution. Language learning, skill-building, and academic content all perform well.</p>
<h2>Entertainment & Comedy</h2>
<p>RPM Range: <strong>$0.20 – $0.55</strong>. The broad, mixed audience of entertainment content reduces ad targeting precision, resulting in lower advertiser bids and therefore lower creator RPMs.</p>
<h2>Dance & Music</h2>
<p>RPM Range: <strong>$0.10 – $0.40</strong>. Dance and music content often attracts a global, younger audience — demographics that advertisers typically bid less to reach. Copyright music conflicts also frequently suppress monetization.</p>',
  'Data', '💰', 'TikRPM Team', '7 min read', 0, 1, '2024-11-28 09:00:00'
),

-- Post 3
(
  'qualified-views-explained',
  'TikTok Qualified Views Explained: What Counts and What Doesn''t',
  'Many creators are confused about why their earnings seem low relative to their view counts. The answer almost always comes down to qualified view rates.',
  '<p>If you have ever checked your TikTok Creator Rewards dashboard and wondered why your earnings seem disproportionately low compared to your view count, you have encountered the concept of <strong>qualified views</strong>.</p>
<p>TikTok does not pay you for every single view your video receives. Instead, it filters views through a quality and authenticity check before counting them toward your earnings. Only views that pass this check are considered "qualified."</p>
<h2>What Makes a View Qualified?</h2>
<p>A view is generally considered qualified when it meets all of the following criteria:</p>
<ul>
<li>The viewer watches a meaningful portion of the video</li>
<li>The view comes from a real, human account (not a bot)</li>
<li>The viewer is located in an eligible monetization country (primarily US, UK, Germany, France, Japan)</li>
<li>The video meets TikTok Community Guidelines and Creator Rewards eligibility</li>
<li>The video does not have third-party copyright claims blocking monetization</li>
</ul>
<h2>What Is a Typical Qualified View Rate?</h2>
<p>Most creators report that between <strong>40% and 75%</strong> of their total views qualify for monetization. The TikRPM calculator defaults to 60%, which is a realistic mid-range estimate.</p>
<h2>How to Improve Your Qualified View Rate</h2>
<p>Focus on creating content that attracts viewers from eligible countries (especially the US and UK), use original or commercially licensed audio, and ensure your content genuinely complies with all Community Guidelines. Avoid any tactics that artificially inflate view counts.</p>',
  'Education', '🎯', 'TikRPM Team', '6 min read', 0, 1, '2024-11-15 10:00:00'
),

-- Post 4
(
  'tiktok-creator-rewards-vs-creator-fund',
  'Creator Rewards Program vs. Creator Fund: What Changed and Why It Matters',
  'TikTok replaced the Creator Fund with the Creator Rewards Program in 2023. Here''s a detailed comparison of rates, eligibility, and what it means for your income.',
  '<p>In 2023, TikTok made a significant change to how it pays creators. The original <strong>Creator Fund</strong> — which launched in 2020 and was widely criticized for its extremely low payout rates — was replaced by the <strong>Creator Rewards Program</strong>.</p>
<p>If you were on the old Creator Fund, this change is significant. Here is what you need to know.</p>
<h2>The Original Creator Fund: What Was Wrong With It?</h2>
<p>The Creator Fund paid creators based on a shared pool of money that TikTok allocated. The problem was that as more creators joined, the fixed pool was divided among more people — so individual payouts actually <em>decreased</em> as TikTok grew. Creators reported RPMs as low as $0.02–$0.04 per 1,000 views, far below what was needed to sustain full-time content creation.</p>
<h2>The Creator Rewards Program: What Changed</h2>
<p>The Creator Rewards Program shifts to an <strong>ad revenue share model</strong>, where payouts are tied to actual advertising revenue generated by your content rather than a fixed pool. This means:</p>
<ul>
<li>Payouts are reportedly 3–8× higher than the old Creator Fund</li>
<li>RPMs now range from $0.10 to $2.50 depending on niche and audience</li>
<li>Videos must be at least 60 seconds long to qualify</li>
<li>Minimum eligibility requirements are higher (10,000 followers, 100,000 views in 30 days)</li>
</ul>
<h2>Should You Switch?</h2>
<p>If you meet the eligibility requirements, absolutely yes. The Creator Rewards Program pays significantly more and aligns creator incentives with content quality rather than raw output volume.</p>',
  'News', '🔄', 'TikRPM Team', '8 min read', 0, 1, '2024-10-30 11:00:00'
),

-- Post 5
(
  'tiktok-earnings-calculator-guide',
  'How to Use a TikTok Earnings Calculator to Plan Your Content Strategy',
  'A step-by-step walkthrough of using the TikRPM calculator to set realistic income targets, reverse-engineer view goals, and compare revenue across different niches.',
  '<p>A TikTok earnings calculator is more than just a curiosity tool — when used strategically, it becomes a powerful planning instrument that helps you set realistic income targets and build a data-driven content strategy.</p>
<h2>Step 1: Find Your Baseline</h2>
<p>Start by entering your actual recent view counts. If you have multiple videos, use your average per-video view count from the last 30 days. This gives you a realistic baseline rather than an outlier high or low performance.</p>
<h2>Step 2: Set Your Qualified View Rate</h2>
<p>If you have access to TikTok Creator Rewards data, use your actual qualified view percentage. If not, start with 55–60% as a conservative estimate. Creators with mostly US/UK audiences can use 65–70%.</p>
<h2>Step 3: Select the Right RPM</h2>
<p>Choose an RPM that matches your niche realistically. Do not just pick the highest number — model both a conservative scenario ($0.40 RPM) and an optimistic scenario ($1.00+ RPM) to understand your income range.</p>
<h2>Step 4: Reverse-Engineer Your View Goal</h2>
<p>Set a monthly income target (e.g., $1,000/month) and work backwards. At $0.60 RPM with 60% qualified views, you need roughly 2.78 million monthly views to hit that target. Is that realistic for your current growth trajectory?</p>
<h2>Step 5: Compare Niches</h2>
<p>Run the calculator twice — once with your current niche RPM and once with a higher-RPM niche RPM. The difference shows you the income upside of a strategic niche shift without needing more views.</p>',
  'Tutorial', '🧮', 'TikRPM Team', '5 min read', 0, 1, '2024-10-12 12:00:00'
),

-- Post 6
(
  'tiktok-rpm-vs-youtube-rpm',
  'TikTok RPM vs. YouTube RPM: Which Platform Pays Creators More?',
  'A head-to-head analysis of TikTok Creator Rewards and YouTube AdSense RPMs across comparable niches — with real creator data and actionable takeaways.',
  '<p>The eternal question for multi-platform creators: <strong>does TikTok or YouTube pay more per view?</strong> The answer is nuanced, and it depends heavily on your niche, audience geography, and content format.</p>
<h2>YouTube RPM in 2025</h2>
<p>YouTube AdSense RPMs typically range from <strong>$1.00 to $10.00+</strong> per 1,000 views depending on niche. Finance content on YouTube can exceed $20 RPM. The platform is well-established, has mature ad inventory, and pays creators a 55% revenue share on AdSense.</p>
<h2>TikTok RPM in 2025</h2>
<p>TikTok Creator Rewards RPMs range from <strong>$0.10 to $2.50</strong> per 1,000 qualified views. While lower than YouTube overall, TikTok compensates with significantly higher organic reach — a new creator can get millions of views with zero subscribers, which is extremely rare on YouTube.</p>
<h2>The Reach Advantage</h2>
<p>A mid-tier YouTube creator with 50,000 subscribers might average 30,000–80,000 views per video. A mid-tier TikTok creator with the same subscriber count can regularly see 200,000–2,000,000 views. This volume advantage can offset TikTok''s lower RPM.</p>
<h2>Which Should You Focus On?</h2>
<p>For pure RPM, YouTube wins in almost every niche. But for total earnings potential with a smaller audience, TikTok''s reach advantage makes it competitive. The optimal strategy for most creators is to publish long-form on YouTube (high RPM) and use TikTok short-form to drive YouTube subscribers (volume + conversion).</p>',
  'Comparison', '⚖️', 'TikRPM Team', '10 min read', 0, 1, '2024-09-20 13:00:00'
);
