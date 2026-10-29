<?php
/**
 * PARTIAL: GLOBAL HEADER / NAVBAR
 * Dipakai oleh: layouts/main.php  (inner pages)
 *               beranda/index.php  (full-canvas homepage)
 *
 * Variabel yang harus tersedia di scope pemanggil:
 *   $menu  — array [label, path, prefix]
 *   $user  — array|null  (dari auth())
 *   $appName — string
 *
 * Jika dipanggil via $this->partial(), variabel dikirim lewat
 * parameter kedua: $this->partial('partials/_header', compact('menu','user','appName'))
 */
$appName = $appName ?? \App\Core\Config::get('app_name');
$user    = $user    ?? auth();
?>
<header class="topbar">
  <div class="container topbar-in">

    <!-- Brand / Logo -->
    <a class="brand" href="<?= e(url('/')) ?>">
      <svg viewBox="0 0 198 130" width="34" height="22" fill="none" stroke="currentColor"
           stroke-width="10" stroke-linecap="round" stroke-linejoin="round">
        <path d="M50 108h92a32 32 0 0 0 6-63A44 44 0 0 0 64 40a34 34 0 0 0-14 68z"/>
      </svg>
      <?= e($appName) ?>
    </a>

    <!-- Search Bar (tersedia untuk semua pengunjung marketplace) -->
    <form class="search" action="<?= e(url('/barang')) ?>" method="get" role="search" autocomplete="off">
      <svg class="search-icon" viewBox="0 0 24 24" width="18" height="18" fill="none"
           stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/>
      </svg>
      <input type="search" name="q" placeholder="Cari barang bekas..."
             value="<?= e($_GET['q'] ?? '') ?>" aria-label="Cari barang">
    </form>

    <!-- Nav -->
    <nav class="nav">

      <!-- Menu Links -->
      <div class="nav-links">
        <?php foreach ($menu as [$label, $path, $prefix]): ?>
          <a href="<?= e(url($path)) ?>"
             class="<?= menu_aktif($prefix) ? 'aktif' : '' ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
      </div>

      <!-- Action Icons (hanya jika login) -->
      <?php if ($user): ?>
      <div class="nav-icons">
        <!-- Keranjang = Pesanan Saya (khusus pembeli & penjual yang berbelanja) -->
        <?php if (!\App\Core\Auth::isAdmin()): ?>
          <a href="<?= e(url('/pesanan')) ?>" class="nav-icon-btn" title="Pesanan Saya">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
          </a>
        <?php endif; ?>
        <!-- Notifikasi -->
        <a href="javascript:void(0)" class="nav-icon-btn" title="Notifikasi"
           onclick="alert('Belum ada notifikasi baru.')">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
        </a>
        <!-- Pesan / Chat -->
        <a href="<?= e(url('/pesan')) ?>" class="nav-icon-btn <?= (strpos(\App\Core\Request::path(), '/pesan') === 0) ? 'aktif' : '' ?>" title="Pesan / Chat">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
        </a>
        <!-- Toko / Jualan -->
        <?php if (\App\Core\Auth::isAdmin()): ?>
          <a href="<?= e(url('/admin')) ?>" class="nav-toko-btn" title="Dashboard Admin">
            <span>Panel Admin</span>
          </a>
        <?php elseif (\App\Core\Auth::isSellerApproved()): ?>
          <a href="<?= e(url('/penjual/barang')) ?>" class="nav-toko-btn" title="Dashboard Toko Saya">
            <span>Toko Saya</span>
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
          </a>
        <?php elseif (\App\Core\Auth::isSeller() && \App\Core\Auth::sellerStatus() === 'pending'): ?>
          <a href="<?= e(url('/penjual/status-verifikasi')) ?>" class="nav-toko-btn" style="background:#fef3c7;color:#92400e;border:1px solid #fcd34d" title="Verifikasi Toko Pending">
            <span>Verifikasi KTP</span>
          </a>
        <?php elseif (\App\Core\Auth::isSeller() && \App\Core\Auth::sellerStatus() === 'rejected'): ?>
          <a href="<?= e(url('/penjual/status-verifikasi')) ?>" class="nav-toko-btn" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5" title="Verifikasi Ditolak">
            <span>Toko Ditolak</span>
          </a>
        <?php else: ?>
          <a href="<?= e(url('/penjual/daftar')) ?>" class="nav-toko-btn" style="background:#111;color:#fff" title="Mulai Berjualan / Buka Toko">
            <span>Buka Toko</span>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <div class="nav-divider"></div>

      <!-- User Area -->
      <?php if ($user): ?>
        <!-- Avatar Dropdown -->
        <div class="nav-user" id="navUser">
          <span class="who"><?= e($user['toko'] ?: $user['nama']) ?></span>
          <div class="topbar-avatar"><?= e(mb_strtoupper(mb_substr($user['nama'], 0, 1))) ?></div>
          <div class="nav-dropdown" id="navDropdown">
            <a href="<?= e(url('/profil')) ?>" class="nav-drop-item">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                   stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              Edit Profil
            </a>
            <div class="nav-drop-sep"></div>
            <a href="<?= e(url('/logout')) ?>" class="nav-drop-item nav-drop-danger">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                   stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              Keluar
            </a>
          </div>
        </div>
      <?php else: ?>
        <a class="btn btn-sm btn-ghost" href="<?= e(url('/login')) ?>">Login</a>
        <a class="btn btn-sm" href="<?= e(url('/registrasi')) ?>">Daftar</a>
      <?php endif; ?>

    </nav>
  </div>
</header>

<script>
// Avatar dropdown toggle — safe to run multiple times
(function () {
  var u = document.getElementById('navUser');
  var d = document.getElementById('navDropdown');
  if (!u || !d) return;
  u.addEventListener('click', function (e) { e.stopPropagation(); d.classList.toggle('open'); });
  document.addEventListener('click', function () { d.classList.remove('open'); });
}());
</script>
