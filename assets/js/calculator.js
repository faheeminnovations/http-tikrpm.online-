/**
 * TikRPM.online — Calculator Logic
 * Real-time RPM & earnings calculations with niche auto-fill.
 */

(function () {
  'use strict';

  // ── DOM References ────────────────────────────────────────────
  const nicheSelect     = document.getElementById('nicheSelect');
  const nicheBadges     = document.getElementById('nicheBadges');
  const badgeMin        = document.getElementById('badge-min');
  const badgeAvg        = document.getElementById('badge-avg');
  const badgeMax        = document.getElementById('badge-max');
  const badgeNote       = document.getElementById('badge-note');

  const totalViewsInput = document.getElementById('totalViews');
  const qualifiedPctInput = document.getElementById('qualifiedPct');
  const rpmSlider       = document.getElementById('rpmSlider');
  const rpmNumber       = document.getElementById('rpmNumber');
  const rpmDisplay      = document.getElementById('rpmDisplay');

  const resQualViews    = document.getElementById('resQualViews');
  const resTotalEarnings= document.getElementById('resTotalEarnings');
  const resTotalSub     = document.getElementById('resTotalSub');
  const resDailyRev     = document.getElementById('resDailyRev');
  const resMonthlyRev   = document.getElementById('resMonthlyRev');

  const btnCopy  = document.getElementById('btnCopy');
  const btnReset = document.getElementById('btnReset');

  // ── Defaults ─────────────────────────────────────────────────
  const DEFAULTS = {
    totalViews:   500000,
    qualifiedPct: 60,
    rpm:          0.60,
  };

  const RPM_MIN  = 0.10;
  const RPM_MAX  = 2.50;
  const RPM_STEP = 0.05;

  // Track current niche selection for copy breakdown
  let currentNiche = '';

  // ── Helpers ───────────────────────────────────────────────────
  function fmtNumber(n) {
    return Math.round(n).toLocaleString('en-US');
  }

  function fmtDollars(n) {
    return '$' + n.toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  }

  function clamp(val, min, max) {
    return Math.min(Math.max(val, min), max);
  }

  function getInputs() {
    const totalViews   = clamp(parseFloat(totalViewsInput.value)   || 0, 0, 1e12);
    const qualifiedPct = clamp(parseFloat(qualifiedPctInput.value) || 0, 0, 100);
    const rpm          = clamp(parseFloat(rpmNumber.value)         || RPM_MIN, RPM_MIN, RPM_MAX);
    return { totalViews, qualifiedPct, rpm };
  }

  // ── Core Calculation ──────────────────────────────────────────
  function calculate(totalViews, qualifiedPct, rpm) {
    const qualifiedViews = totalViews * (qualifiedPct / 100);
    const totalEarnings  = (qualifiedViews / 1000) * rpm;
    const dailyRevenue   = totalEarnings / 30;
    const monthlyRevenue = totalEarnings;
    return { qualifiedViews, totalEarnings, dailyRevenue, monthlyRevenue };
  }

  // ── Render Results ────────────────────────────────────────────
  function updateResults() {
    const { totalViews, qualifiedPct, rpm } = getInputs();
    const { qualifiedViews, totalEarnings, dailyRevenue, monthlyRevenue } =
      calculate(totalViews, qualifiedPct, rpm);

    resQualViews.textContent = fmtNumber(qualifiedViews);
    resQualViews.closest('.result-card').querySelector('.result-sub').textContent =
      'of ' + fmtNumber(totalViews) + ' total views';

    resTotalEarnings.textContent = fmtDollars(totalEarnings);
    resTotalSub.textContent      = 'at ' + fmtDollars(rpm) + ' RPM'
      + (currentNiche ? ' · ' + currentNiche : '');

    resDailyRev.textContent   = fmtDollars(dailyRevenue);
    resMonthlyRev.textContent = fmtDollars(monthlyRevenue);

    // Animate highlight card
    const highlight = resTotalEarnings.closest('.result-card');
    highlight.classList.remove('fade-in-up');
    void highlight.offsetWidth;
    highlight.classList.add('fade-in-up');
  }

  // ── Niche Selector ────────────────────────────────────────────
  function onNicheChange() {
    const opt = nicheSelect.options[nicheSelect.selectedIndex];

    if (!opt || !opt.value) {
      // Cleared — hide badges, keep current RPM
      nicheBadges.style.display = 'none';
      currentNiche = '';
      updateResults();
      return;
    }

    const minRpm  = parseFloat(opt.dataset.min);
    const avgRpm  = parseFloat(opt.dataset.avg);
    const maxRpm  = parseFloat(opt.dataset.max);
    const niche   = opt.dataset.niche || opt.text;

    currentNiche = niche;

    // Auto-fill RPM slider with avg
    const rpm = clamp(avgRpm, RPM_MIN, RPM_MAX);
    rpmNumber.value = rpm.toFixed(2);
    syncRpm(rpmNumber);

    // Show badge strip
    badgeMin.textContent  = 'Low: ' + fmtDollars(minRpm);
    badgeAvg.textContent  = '✓ Avg: ' + fmtDollars(avgRpm);
    badgeMax.textContent  = 'High: ' + fmtDollars(maxRpm);
    badgeNote.textContent = '← auto-filled';
    nicheBadges.style.display = 'flex';

    // Highlight that RPM was auto-filled
    rpmNumber.style.borderColor = '#10b981';
    rpmSlider.style.accentColor  = '#10b981';
    setTimeout(function() {
      rpmNumber.style.borderColor = '';
      rpmSlider.style.accentColor  = '';
      badgeNote.textContent = '';
    }, 2500);
  }

  if (nicheSelect) {
    nicheSelect.addEventListener('change', onNicheChange);
  }

  // Reset niche badge when user manually changes RPM
  function onManualRpmChange() {
    if (currentNiche) {
      badgeNote.textContent = '(manually adjusted)';
    }
  }

  // ── RPM Sync (slider ↔ number ↔ label) ───────────────────────
  function syncRpm(source) {
    let val = parseFloat(source.value) || RPM_MIN;
    val = clamp(Math.round(val / RPM_STEP) * RPM_STEP, RPM_MIN, RPM_MAX);
    val = parseFloat(val.toFixed(2));

    rpmSlider.value = val;
    rpmNumber.value = val;
    rpmSlider.setAttribute('aria-valuenow', val);
    rpmDisplay.textContent = fmtDollars(val);

    updateResults();
  }

  rpmSlider.addEventListener('input',  function() { onManualRpmChange(); syncRpm(rpmSlider); });
  rpmSlider.addEventListener('change', function() { syncRpm(rpmSlider); });
  rpmNumber.addEventListener('input',  function() { onManualRpmChange(); syncRpm(rpmNumber); });
  rpmNumber.addEventListener('change', function() { syncRpm(rpmNumber); });

  // ── View / Pct Inputs ─────────────────────────────────────────
  function onViewsInput() {
    totalViewsInput.value = totalViewsInput.value.replace(/[^0-9]/g, '');
    updateResults();
  }

  function onPctInput() {
    const v = parseFloat(qualifiedPctInput.value);
    if (!isNaN(v)) qualifiedPctInput.value = clamp(v, 1, 100);
    updateResults();
  }

  totalViewsInput.addEventListener('input',  onViewsInput);
  totalViewsInput.addEventListener('change', onViewsInput);
  qualifiedPctInput.addEventListener('input',  onPctInput);
  qualifiedPctInput.addEventListener('change', onPctInput);

  // ── Copy Breakdown ────────────────────────────────────────────
  btnCopy.addEventListener('click', function () {
    const { totalViews, qualifiedPct, rpm } = getInputs();
    const { qualifiedViews, totalEarnings, dailyRevenue, monthlyRevenue } =
      calculate(totalViews, qualifiedPct, rpm);

    const nicheLine = currentNiche ? 'Niche:                ' + currentNiche + '\n' : '';

    const text = [
      '──────────────────────────────',
      '   TikRPM.online — Earnings Breakdown',
      '──────────────────────────────',
      nicheLine + 'Total Views:          ' + fmtNumber(totalViews),
      'Qualified Views (%):  ' + qualifiedPct + '%',
      'Qualified Views:      ' + fmtNumber(qualifiedViews),
      'RPM Rate:             ' + fmtDollars(rpm) + ' / 1,000 views',
      '──────────────────────────────',
      'Total Est. Earnings:  ' + fmtDollars(totalEarnings),
      'Daily Revenue (÷30):  ' + fmtDollars(dailyRevenue),
      'Monthly Revenue:      ' + fmtDollars(monthlyRevenue),
      '──────────────────────────────',
      'Estimated at tikrpm.online',
      'Actual earnings may vary.',
    ].join('\n');

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(flashCopied).catch(function() {
        fallbackCopy(text);
      });
    } else {
      fallbackCopy(text);
    }
  });

  function fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;left:-9999px;top:-9999px;';
    document.body.appendChild(ta);
    ta.select();
    try { document.execCommand('copy'); flashCopied(); }
    catch (e) { alert('Please copy manually:\n\n' + text); }
    document.body.removeChild(ta);
  }

  function flashCopied() {
    var orig = btnCopy.textContent;
    btnCopy.textContent = '✅ Copied!';
    btnCopy.classList.add('copied');
    setTimeout(function() {
      btnCopy.textContent = orig;
      btnCopy.classList.remove('copied');
    }, 2200);
  }

  // ── Reset ─────────────────────────────────────────────────────
  btnReset.addEventListener('click', function () {
    totalViewsInput.value   = DEFAULTS.totalViews;
    qualifiedPctInput.value = DEFAULTS.qualifiedPct;
    rpmNumber.value         = DEFAULTS.rpm;

    // Reset niche dropdown
    if (nicheSelect) {
      nicheSelect.selectedIndex = 0;
      nicheBadges.style.display = 'none';
      currentNiche = '';
    }

    syncRpm(rpmNumber);
  });

  // ── Keyboard guard for RPM number input ──────────────────────
  rpmNumber.addEventListener('keydown', function (e) {
    const allowed = ['Backspace','Delete','Tab','Escape','Enter','.','ArrowLeft','ArrowRight','ArrowUp','ArrowDown'];
    if (allowed.includes(e.key)) return;
    if ((e.ctrlKey || e.metaKey) && ['a','c','v','x'].includes(e.key.toLowerCase())) return;
    if (e.key >= '0' && e.key <= '9') return;
    e.preventDefault();
  });

  // ── Debounce for typing inputs (aria-live) ────────────────────
  var debounceTimer;
  function debouncedUpdate() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(updateResults, 180);
  }
  totalViewsInput.addEventListener('input',   debouncedUpdate);
  qualifiedPctInput.addEventListener('input', debouncedUpdate);

  // ── Init ──────────────────────────────────────────────────────
  function init() {
    syncRpm(rpmNumber);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
