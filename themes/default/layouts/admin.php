<?php
/**
 * LAYOUT ADMIN PANEL - Shell responsif terpadu (sama dengan dashboard penjual).
 * Variabel: $content, $flashSuccess, $flashError, $user, $this->title
 */
use App\Core\Request;

$appName = \App\Core\Config::get('app_name');
$path = Request::path();
$isDashboard  = ($path === '/admin');
$isLaporan    = ($path === '/laporan' || strpos($path, '/laporan/') === 0);
$isAkun       = (strpos($path, '/admin/akun') === 0);
$isVerifikasi = (strpos($path, '/admin/verifikasi-penjual') === 0);
$isKategori   = (strpos($path, '/admin/kategori') === 0);
$pendingVerifCount = (new \App\Models\SellerProfile())->hitungPending();

$user = auth() ?? [];
$adminNama = $user['nama'] ?? 'Administrator';
$inisial = mb_strtoupper(mb_substr($adminNama, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($this->title ? $this->title . ' — ' : '') ?>Admin Panel — <?= e($appName) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800;900&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>

  <!-- Flash Messages -->
  <?php if (!empty($flashSuccess)): ?>
    <div class="pnj-alert pnj-alert-ok auto-dismiss">
      <span><?= e($flashSuccess) ?></span>
      <button type="button" class="pnj-alert-close" onclick="this.closest('.pnj-alert').remove()" aria-label="Tutup">&times;</button>
    </div>
  <?php endif; ?>
  <?php if (!empty($flashError)): ?>
    <div class="pnj-alert pnj-alert-bad auto-dismiss">
      <span><?= e($flashError) ?></span>
      <button type="button" class="pnj-alert-close" onclick="this.closest('.pnj-alert').remove()" aria-label="Tutup">&times;</button>
    </div>
  <?php endif; ?>
  <script>
    (function() {
      var alerts = document.querySelectorAll('.pnj-alert.auto-dismiss');
      alerts.forEach(function(el) {
        setTimeout(function() {
          el.classList.add('pnj-alert-fading');
          setTimeout(function() { if (el && el.parentNode) el.remove(); }, 350);
        }, 2000);
      });
    })();
  </script>

  <div class="pnj-shell">

    <!-- ===== TOPBAR ===== -->
    <header class="pnj-topbar">
      <a href="<?= e(url('/admin')) ?>" class="pnj-topbar-logo">
        <svg viewBox="0 0 85 56" fill="none" stroke="#fff" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 47 C9 47 5 34 15 28 C13 17 26 9 36 17 C41 6 60 8 62 22 C75 20 81 35 72 43 C68 47 64 47 60 47 Z"/>
          <path d="M28 32 C34 28 41 36 49 32 C55 29 58 34 62 32"/>
        </svg>
        <span class="pnj-topbar-title">Cloud Market Admin Panel</span>
      </a>
      <div class="pnj-topbar-right">
        <!-- Tautan ke Marketplace -->
        <a href="<?= e(url('/')) ?>" class="pnj-topbar-link-btn" title="Kunjungi Marketplace">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>
          </svg>
          <span>Marketplace</span>
        </a>

        <div class="pnj-topbar-sep-v"></div>

        <span class="pnj-topbar-nama"><?= e($adminNama) ?></span>
        <a href="<?= e(url('/profil')) ?>" class="pnj-topbar-avatar" title="Profil Admin">
          <span><?= e($inisial) ?></span>
        </a>
      </div>
    </header>

    <div class="pnj-body">

      <!-- ===== SIDEBAR ===== -->
      <nav class="pnj-sidebar">
        <div class="pnj-sidebar-avatar">
          <span><?= e($inisial) ?></span>
        </div>
        <div class="pnj-sidebar-toko">Admin Panel</div>

        <ul class="pnj-nav">
          <li>
            <a href="<?= e(url('/admin')) ?>" class="pnj-nav-item <?= $isDashboard ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              Dashboard
            </a>
          </li>
          <li>
            <a href="<?= e(url('/laporan')) ?>" class="pnj-nav-item <?= $isLaporan ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
              Laporan Transaksi
            </a>
          </li>
          <li>
            <a href="<?= e(url('/admin/akun')) ?>" class="pnj-nav-item <?= $isAkun ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              Kelola Akun
            </a>
          </li>
          <li>
            <a href="<?= e(url('/admin/verifikasi-penjual')) ?>" class="pnj-nav-item <?= $isVerifikasi ? 'aktif' : '' ?>" style="display:flex; justify-content:space-between; align-items:center;">
              <span style="display:flex; align-items:center; gap:0.6rem;">
                <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Verifikasi Toko
              </span>
              <?php if ($pendingVerifCount > 0): ?>
                <span class="badge" style="background:#EF4444; color:#fff; font-size:0.75rem; padding:2px 7px; border-radius:999px; font-weight:700;"><?= $pendingVerifCount ?></span>
              <?php endif; ?>
            </a>
          </li>
          <li>
            <a href="<?= e(url('/admin/kategori')) ?>" class="pnj-nav-item <?= $isKategori ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              Kelola Kategori
            </a>
          </li>
        </ul>

        <div class="pnj-sidebar-bottom">
          <a href="<?= e(url('/profil')) ?>" class="pnj-sidebar-link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil Admin
          </a>
          <a href="<?= e(url('/')) ?>" class="pnj-sidebar-link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Ke Beranda
          </a>
          <a href="<?= e(url('/logout')) ?>" class="pnj-sidebar-link pnj-logout">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            Keluar
          </a>
        </div>
      </nav>

      <!-- ===== MAIN CONTENT ===== -->
      <main class="pnj-main">
        <?= $content ?>
      </main>

    </div>
  </div>

</body>
</html>
