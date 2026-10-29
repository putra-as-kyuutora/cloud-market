<?php
/**
 * LAYOUT DASHBOARD PENJUAL - Full-frame canvas, sesuai referensi Alab.
 * Variabel: $content, $flashSuccess, $flashError, $user, $this->title
 */
use App\Core\Request;

$appName = \App\Core\Config::get('app_name');
$path    = Request::path();

$user       = auth() ?? [];
$namaToko   = $user['toko'] ?? ($user['nama'] ?? 'Toko Saya');
$inisial    = mb_strtoupper(mb_substr($namaToko, 0, 1));

// Active state helpers
$isProduk     = ($path === '/penjual/barang' || strpos($path, '/penjual/barang') === 0);
$isTambah     = (strpos($path, '/penjual/barang/tambah') === 0);
$isEdit       = (strpos($path, '/penjual/barang/edit') === 0 || (strpos($path, '/penjual/barang/') !== false && strpos($path, '/edit') !== false));
$isPesanan    = ($path === '/penjual/pesanan' || strpos($path, '/penjual/pesanan') === 0);
$isKonfirmasi = (strpos($path, '/penjual/konfirmasi') === 0);
$isLaporan    = ($path === '/laporan' || strpos($path, '/laporan/') === 0);
$isPesan      = ($path === '/pesan' || strpos($path, '/pesan/') === 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($this->title ? $this->title . ' — ' : '') ?>Dashboard Penjual — <?= e($appName) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800;900&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/penjual.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/modal_alamat.css')) ?>">
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
      <a href="<?= e(url('/penjual/barang')) ?>" class="pnj-topbar-logo">
        <svg viewBox="0 0 85 56" fill="none" stroke="#fff" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 47 C9 47 5 34 15 28 C13 17 26 9 36 17 C41 6 60 8 62 22 C75 20 81 35 72 43 C68 47 64 47 60 47 Z"/>
          <path d="M28 32 C34 28 41 36 49 32 C55 29 58 34 62 32"/>
        </svg>
        <span class="pnj-topbar-title">Cloud Market Dashboard Penjualan</span>
      </a>
      <div class="pnj-topbar-right">
        <!-- Tautan ke Marketplace -->
        <a href="<?= e(url('/')) ?>" class="pnj-topbar-link-btn" title="Kunjungi Marketplace">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>
          </svg>
          <span>Marketplace</span>
        </a>

        <!-- Cloud Message / Chat -->
        <a href="<?= e(url('/pesan')) ?>" class="pnj-topbar-icon-btn <?= $isPesan ? 'aktif' : '' ?>" title="Cloud Message">
          <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
          </svg>
        </a>

        <div class="pnj-topbar-sep-v"></div>

        <span class="pnj-topbar-nama"><?= e($namaToko) ?></span>
        <a href="<?= e(url('/profil')) ?>" class="pnj-topbar-avatar" title="Profil & Pengaturan Toko">
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
        <div class="pnj-sidebar-toko"><?= e($namaToko) ?></div>

        <ul class="pnj-nav">
          <li>
            <a href="<?= e(url('/penjual/barang')) ?>"
               class="pnj-nav-item <?= ($isProduk && !$isTambah && !$isEdit) ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
              Produk Saya
            </a>
          </li>
          <li>
            <a href="<?= e(url('/penjual/barang/tambah')) ?>"
               class="pnj-nav-item <?= $isTambah ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
              Tambah Produk
            </a>
          </li>
          <li>
            <a href="<?= e(url('/penjual/barang/edit')) ?>"
               class="pnj-nav-item <?= ($isEdit && !$isTambah) ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Edit Produk
            </a>
          </li>
          <li>
            <a href="<?= e(url('/penjual/pesanan')) ?>"
               class="pnj-nav-item <?= $isPesanan ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
              Pesanan Masuk
            </a>
          </li>
          <li>
            <a href="<?= e(url('/penjual/konfirmasi')) ?>"
               class="pnj-nav-item <?= $isKonfirmasi ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M12 8c-1.66 0-3 .9-3 2s1.34 2 3 2 3 .9 3 2-1.34 2-3 2m0-8c1.11 0 2.08.4 2.6 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.4-2.6-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Konfirmasi Pembayaran
            </a>
          </li>
          <li>
            <a href="<?= e(url('/laporan')) ?>"
               class="pnj-nav-item <?= $isLaporan ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
              Laporan Transaksi
            </a>
          </li>
          <li>
            <a href="<?= e(url('/pesan')) ?>"
               class="pnj-nav-item <?= $isPesan ? 'aktif' : '' ?>">
              <svg class="pnj-nav-icon" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              Cloud Message
            </a>
          </li>
        </ul>

        <div class="pnj-sidebar-bottom">
          <a href="<?= e(url('/profil')) ?>" class="pnj-sidebar-link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Pengaturan Toko
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

<?php $this->partial('partials/modal_alamat'); ?>
<script src="<?= e(asset('js/modal_alamat.js')) ?>"></script>
</body>
</html>
