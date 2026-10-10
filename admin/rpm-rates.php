<?php
/**
 * TikRPM Admin – RPM Rates Manager
 * Update community-reported niche RPM rates
 */
require_once __DIR__ . '/auth.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// ── Handle Save ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $_SESSION['flash'] = ['type'=>'error','msg'=>'Security check failed.'];
        header('Location: rpm-rates.php'); exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'update_all') {
        $ids     = $_POST['id']      ?? [];
        $mins    = $_POST['min_rpm'] ?? [];
        $avgs    = $_POST['avg_rpm'] ?? [];
        $maxs    = $_POST['max_rpm'] ?? [];
        $icons   = $_POST['icon']    ?? [];

        $stmt = db()->prepare(
            'UPDATE rpm_rates SET icon=?, min_rpm=?, avg_rpm=?, max_rpm=? WHERE id=?'
        );
        $count = 0;
        foreach ($ids as $i => $id) {
            $id  = (int)$id;
            $min = round((float)($mins[$i]  ?? 0.10), 2);
            $avg = round((float)($avgs[$i]  ?? 0.50), 2);
            $max = round((float)($maxs[$i]  ?? 2.50), 2);
            $ico = trim($icons[$i] ?? '📊');

            // Sanity: min ≤ avg ≤ max
            $min = min($min, $avg);
            $max = max($max, $avg);

            $stmt->execute([$ico, $min, $avg, $max, $id]);
            $count++;
        }
        $_SESSION['flash'] = ['type'=>'success','msg'=>"✅ Updated {$count} niche rates successfully."];
        header('Location: rpm-rates.php'); exit;
    }

    if ($action === 'add_niche') {
        $niche = trim($_POST['new_niche']    ?? '');
        $icon  = trim($_POST['new_icon']     ?? '📊');
        $min   = round((float)($_POST['new_min'] ?? 0.10), 2);
        $avg   = round((float)($_POST['new_avg'] ?? 0.50), 2);
        $max   = round((float)($_POST['new_max'] ?? 1.00), 2);

        if (strlen($niche) < 2) {
            $_SESSION['flash'] = ['type'=>'error','msg'=>'Niche name is required.'];
        } else {
            try {
                db_exec(
                    'INSERT INTO rpm_rates (niche,icon,min_rpm,avg_rpm,max_rpm,sort_order)
                     SELECT ?,?,?,?,?, COALESCE(MAX(sort_order),0)+1 FROM rpm_rates',
                    [$niche,$icon,$min,$avg,$max]
                );
                $_SESSION['flash'] = ['type'=>'success','msg'=>"✅ Niche \"{$niche}\" added."];
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type'=>'error','msg'=>'Niche already exists or DB error.'];
            }
        }
        header('Location: rpm-rates.php'); exit;
    }

    if ($action === 'delete_niche') {
        $id = (int)($_POST['del_id'] ?? 0);
        if ($id > 0) {
            $row = db_row('SELECT niche FROM rpm_rates WHERE id=?', [$id]);
            db_exec('DELETE FROM rpm_rates WHERE id=?', [$id]);
            $_SESSION['flash'] = ['type'=>'success','msg'=>'🗑️ Niche "'.($row['niche']??'').'" deleted.'];
        }
        header('Location: rpm-rates.php'); exit;
    }
}

// ── Fetch rates ───────────────────────────────────────────────
$rates = db_rows('SELECT * FROM rpm_rates ORDER BY sort_order, id');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>RPM Rates – TikRPM Admin</title>
<meta name="robots" content="noindex,nofollow">
  <link rel="icon" type="image/svg+xml" href="/tikrpm/assets/images/favicon.svg">
  <link rel="alternate icon" type="image/png" href="/tikrpm/assets/images/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:#f1f3f6;min-height:100vh}
a{text-decoration:none;color:inherit}
.topbar{background:#0f0f1a;color:#fff;padding:.85rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.topbar-brand{font-size:1.2rem;font-weight:800}.topbar-brand span{color:#EE1D52}
.topbar-nav{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
.topbar-nav a{font-size:.82rem;color:rgba(255,255,255,.6);background:rgba(255,255,255,.08);padding:.35rem .75rem;border-radius:6px;transition:all .15s}
.topbar-nav a:hover{background:rgba(255,255,255,.15);color:#fff}
.topbar-nav a.active{background:#EE1D52;color:#fff}
.wrap{padding:1.5rem;max-width:1000px;margin:0 auto}
.page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap}
.page-head h1{font-size:1.3rem;font-weight:700}
.card{background:#fff;border:1px solid #e2e6ea;border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.card-head{padding:1rem 1.25rem;border-bottom:1px solid #e2e6ea;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap}
.card-head h2{font-size:.95rem;font-weight:700}
.notice{font-size:.78rem;color:#6b7280;background:#f8f9fb;border:1px solid #e2e6ea;border-radius:8px;padding:.65rem .9rem;margin:1rem 1.25rem 0}
.flash{padding:.85rem 1.1rem;border-radius:8px;margin-bottom:1.25rem;font-size:.9rem;font-weight:500}
.flash-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
.flash-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
table{width:100%;border-collapse:collapse;font-size:.86rem}
thead th{background:#0f0f1a;color:#fff;padding:.65rem .9rem;text-align:left;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
tbody tr{border-bottom:1px solid #f1f3f6}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:#f8f9fb}
td{padding:.6rem .9rem;vertical-align:middle}
input[type=text],input[type=number]{padding:.45rem .7rem;border:1.5px solid #e2e6ea;border-radius:7px;font-size:.85rem;font-family:inherit;width:100%;background:#f8f9fb;transition:border-color .2s}
input:focus{border-color:#EE1D52;outline:none;background:#fff}
.rpm-inputs{display:grid;grid-template-columns:1fr 1fr 1fr;gap:.4rem}
.btn{display:inline-flex;align-items:center;gap:.3rem;padding:.4rem .9rem;border-radius:7px;font-size:.8rem;font-weight:600;cursor:pointer;border:none;font-family:inherit;white-space:nowrap;transition:all .15s}
.btn-primary{background:#EE1D52;color:#fff}.btn-primary:hover{background:#c4143f}
.btn-ghost{background:#f1f3f6;color:#374151;border:1px solid #e2e6ea}.btn-ghost:hover{background:#e2e6ea}
.btn-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}.btn-danger:hover{background:#fee2e2}
.btn-save{background:#10b981;color:#fff}.btn-save:hover{background:#059669}
.rpm-badge{display:inline-block;padding:.15rem .45rem;border-radius:5px;font-size:.72rem;font-weight:700}
.rpm-min{background:#fef3c7;color:#92400e}
.rpm-avg{background:#dcfce7;color:#166534}
.rpm-max{background:#dbeafe;color:#1e40af}
.add-row td{background:#f0fdf4;border-top:2px solid #10b981}
</style>
</head>
<body>

<div class="topbar">
  <div class="topbar-brand">Tik<span>RPM</span> <span style="color:rgba(255,255,255,.3);font-weight:400;font-size:.9rem;">Admin</span></div>
  <div class="topbar-nav">
    <a href="dashboard.php">📊 Posts</a>
    <a href="post-form.php">✏️ New Post</a>
    <a href="rpm-rates.php" class="active">📈 RPM Rates</a>
    <a href="<?= BASE_PATH ?>/" target="_blank">🌐 View Site</a>
    <a href="logout.php" style="background:rgba(238,29,82,.25);color:#ff8fa8;">Sign Out</a>
  </div>
</div>

<div class="wrap">
  <div class="page-head">
    <h1>📈 Niche RPM Rates</h1>
    <a href="dashboard.php" class="btn btn-ghost">← Back</a>
  </div>

  <?php if ($flash): ?>
    <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-head">
      <h2>Community-Reported Rates (2026)</h2>
      <button form="ratesForm" type="submit" class="btn btn-save">💾 Save All Changes</button>
    </div>

    <div class="notice">
      ℹ️ These rates are shown in the calculator's niche dropdown. Update them when new community data is available.
      <strong>Min</strong> = low end · <strong>Avg</strong> = default auto-fill · <strong>Max</strong> = high end.
    </div>

    <form id="ratesForm" method="POST" action="rpm-rates.php">
      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
      <input type="hidden" name="action"     value="update_all">

      <table>
        <thead>
          <tr>
            <th style="width:36px">#</th>
            <th>Icon</th>
            <th>Niche</th>
            <th>Min RPM ($)</th>
            <th>Avg RPM ($) <span style="color:#10b981;">← auto-fills</span></th>
            <th>Max RPM ($)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rates as $r): ?>
          <tr>
            <td style="color:#9ca3af;font-size:.75rem;"><?= $r['id'] ?></td>
            <td>
              <input type="hidden" name="id[]" value="<?= (int)$r['id'] ?>">
              <input type="text" name="icon[]" value="<?= e($r['icon']) ?>"
                     style="width:52px;text-align:center;font-size:1.1rem;">
            </td>
            <td style="font-weight:600;"><?= e($r['niche']) ?></td>
            <td>
              <input type="number" name="min_rpm[]" value="<?= e($r['min_rpm']) ?>"
                     min="0.01" max="10" step="0.01" style="width:80px;">
            </td>
            <td>
              <input type="number" name="avg_rpm[]" value="<?= e($r['avg_rpm']) ?>"
                     min="0.01" max="10" step="0.01" style="width:80px;border-color:#10b981;">
            </td>
            <td>
              <input type="number" name="max_rpm[]" value="<?= e($r['max_rpm']) ?>"
                     min="0.01" max="10" step="0.01" style="width:80px;">
            </td>
            <td>
              <form method="POST" action="rpm-rates.php" style="display:inline;"
                    onsubmit="return confirm('Delete this niche?')">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="action"     value="delete_niche">
                <input type="hidden" name="del_id"     value="<?= (int)$r['id'] ?>">
                <button type="submit" class="btn btn-danger" style="padding:.3rem .6rem;font-size:.72rem;">✕</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </form>

    <!-- Add new niche -->
    <form method="POST" action="rpm-rates.php" style="padding:1rem 1.25rem;border-top:2px dashed #e2e6ea;display:grid;grid-template-columns:60px 1fr 80px 80px 80px auto;gap:.6rem;align-items:end;">
      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
      <input type="hidden" name="action"     value="add_niche">
      <div>
        <label style="font-size:.72rem;font-weight:700;color:#6b7280;display:block;margin-bottom:.3rem;">ICON</label>
        <input type="text" name="new_icon" value="📊" style="width:52px;text-align:center;font-size:1.1rem;">
      </div>
      <div>
        <label style="font-size:.72rem;font-weight:700;color:#6b7280;display:block;margin-bottom:.3rem;">NICHE NAME</label>
        <input type="text" name="new_niche" placeholder="e.g. Crypto & Web3" maxlength="100">
      </div>
      <div>
        <label style="font-size:.72rem;font-weight:700;color:#6b7280;display:block;margin-bottom:.3rem;">MIN ($)</label>
        <input type="number" name="new_min" value="0.20" min="0.01" max="10" step="0.01">
      </div>
      <div>
        <label style="font-size:.72rem;font-weight:700;color:#10b981;display:block;margin-bottom:.3rem;">AVG ($)</label>
        <input type="number" name="new_avg" value="0.50" min="0.01" max="10" step="0.01" style="border-color:#10b981;">
      </div>
      <div>
        <label style="font-size:.72rem;font-weight:700;color:#6b7280;display:block;margin-bottom:.3rem;">MAX ($)</label>
        <input type="number" name="new_max" value="1.00" min="0.01" max="10" step="0.01">
      </div>
      <button type="submit" class="btn btn-primary" style="white-space:nowrap;">+ Add Niche</button>
    </form>

  </div><!-- /.card -->
</div>

</body>
</html>
