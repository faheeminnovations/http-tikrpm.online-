<?php
/**
 * TikRPM.online – Global Footer
 * Outputs: closing </main>, <footer>, scripts, and </body></html>
 */
// $footer_links is defined in config.php (already required via header.php)
?>

<!-- ── Footer Ad Slot (hidden until AdSense approved) ────────── -->
<?php if (ADSENSE_ENABLED): ?>
<div class="ad-wrapper" style="background:var(--surface-2);border-top:1px solid var(--border);">
  <div class="container">
    <ins class="adsbygoogle ad-slot ad-slot--banner"
         style="display:block"
         data-ad-client="<?= e(ADSENSE_CLIENT) ?>"
         data-ad-slot="FOOTER_SLOT_ID"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
  </div>
</div>
<?php endif; ?>

<!-- ── Site Footer ─────────────────────────────────────────────── -->
<footer class="site-footer" role="contentinfo">
  <div class="container">

    <div class="footer-grid">

      <!-- Brand Column -->
      <div class="footer-brand-col">
        <div class="footer-brand">
          <div class="brand-icon" aria-hidden="true">T</div>
          <div class="footer-brand-name">
            Tik<span>RPM</span>
          </div>
        </div>
        <p class="footer-desc">
          A free, independent calculator to help TikTok creators estimate earnings
          through the Creator Rewards Program. Built for transparency and accuracy.
        </p>
      </div>

      <!-- Quick Links -->
      <div>
        <p class="footer-heading">Quick Links</p>
        <ul class="footer-links">
          <li><a href="<?= BASE_PATH ?>/">RPM Calculator</a></li>
          <li><a href="<?= BASE_PATH ?>/blog">Blog &amp; Guides</a></li>
          <li><a href="<?= BASE_PATH ?>/about">About Us</a></li>
          <li><a href="<?= BASE_PATH ?>/contact">Contact</a></li>
        </ul>
      </div>

      <!-- Legal -->
      <div>
        <p class="footer-heading">Legal</p>
        <ul class="footer-links">
          <li><a href="<?= BASE_PATH ?>/privacy-policy">Privacy Policy</a></li>
          <li><a href="<?= BASE_PATH ?>/terms">Terms of Service</a></li>
        </ul>
        <p class="footer-heading" style="margin-top:1.25rem;">Resources</p>
        <ul class="footer-links">
          <li><a href="https://www.tiktok.com/creator-academy/" target="_blank" rel="noopener noreferrer">TikTok Creator Academy ↗</a></li>
          <li><a href="https://support.tiktok.com/en/using-tiktok/creator-tools/creator-rewards-program" target="_blank" rel="noopener noreferrer">Creator Rewards Program ↗</a></li>
        </ul>
      </div>

    </div><!-- /.footer-grid -->

    <!-- Bottom bar -->
    <div class="footer-bottom">
      <span>© <?= date('Y') ?> <?= e(SITE_NAME) ?>.com — All rights reserved.</span>
      <span style="color:rgba(255,255,255,.3);">
        Made for creators, by creators.
      </span>
    </div>

    <!-- Disclaimer -->
    <p class="footer-disclaimer">
      <strong style="color:rgba(255,255,255,.5);">Disclaimer:</strong>
      TikRPM.online is an independent utility tool and is <em>not affiliated, endorsed, sponsored,
      or associated with TikTok, ByteDance Ltd., or any of their subsidiaries or affiliates</em>.
      All earnings estimates are approximations based on publicly reported RPM ranges and are
      provided for informational purposes only. Actual earnings may vary significantly based on
      content niche, audience geography, engagement, and TikTok's current payout policies.
      "TikTok" and related marks are trademarks of ByteDance Ltd.
    </p>

  </div><!-- /.container -->
</footer>
<!-- ── End Footer ──────────────────────────────────────────────── -->

<!-- ── Scripts ─────────────────────────────────────────────────── -->
<script>
// Hamburger menu toggle
(function() {
  var btn  = document.getElementById('navToggle');
  var menu = document.getElementById('navMenu');
  if (!btn || !menu) return;

  btn.addEventListener('click', function() {
    var open = menu.classList.toggle('open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  // Close on outside click
  document.addEventListener('click', function(e) {
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });

  // Close on Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      menu.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();

// Accordion (FAQ)
(function() {
  document.querySelectorAll('.accordion-trigger').forEach(function(trigger) {
    trigger.addEventListener('click', function() {
      var panel   = document.getElementById(trigger.getAttribute('aria-controls'));
      var isOpen  = trigger.getAttribute('aria-expanded') === 'true';

      // Close all siblings in the same accordion
      var accordion = trigger.closest('.accordion');
      if (accordion) {
        accordion.querySelectorAll('.accordion-trigger').forEach(function(t) {
          t.setAttribute('aria-expanded', 'false');
          var p = document.getElementById(t.getAttribute('aria-controls'));
          if (p) p.classList.remove('open');
        });
      }

      // Toggle clicked one
      if (!isOpen) {
        trigger.setAttribute('aria-expanded', 'true');
        if (panel) panel.classList.add('open');
      }
    });
  });
})();
</script>

<?php if (isset($page_scripts)): ?>
  <!-- Page-specific scripts -->
  <?= $page_scripts ?>
<?php endif; ?>

</body>
</html>
