<?php
/**
 * DASHBOARD ADMIN - Desain Modern & Responsif (Serasi dengan Dashboard Penjual).
 * Variabel: $totalTransaksi, $produkAktif, $totalPengguna, $transaksiBaru, $barangBaru, $penggunaBaru, $pembeliBaru
 */
$this->title = 'Dashboard';

// Susun list aktivitas terbaru secara dinamis
$listAktivitas = [];

if (!empty($transaksiBaru[0])) {
    $t = $transaksiBaru[0];
    $listAktivitas[] = [
        'tipe'  => 'Transaksi Baru',
        'sub'   => '#CM-' . str_pad((string)$t['idTransaksi'], 3, '0', STR_PAD_LEFT) . ' &middot; ' . ($t['namaPembeli'] ?? 'Pembeli'),
        'waktu' => tanggal($t['tanggal'] ?? null),
        'dot'   => '#012CED'
    ];
}

if (!empty($barangBaru[0])) {
    $b = $barangBaru[0];
    $listAktivitas[] = [
        'tipe'  => 'Produk Ditambahkan',
        'sub'   => ($b['namaBarang'] ?? '') . ' (' . ($b['namaToko'] ?? 'Toko') . ')',
        'waktu' => 'Baru saja',
        'dot'   => '#0B911B'
    ];
}

if (!empty($penggunaBaru[0])) {
    $u = $penggunaBaru[0];
    $listAktivitas[] = [
        'tipe'  => 'Pengguna Baru Terdaftar',
        'sub'   => ($u['nama'] ?? '') . ' (Penjual)',
        'waktu' => 'Baru saja',
        'dot'   => '#A81EF8'
    ];
} elseif (!empty($pembeliBaru[0])) {
    $u = $pembeliBaru[0];
    $listAktivitas[] = [
        'tipe'  => 'Pengguna Baru Terdaftar',
        'sub'   => ($u['nama'] ?? '') . ' (Pembeli)',
        'waktu' => 'Baru saja',
        'dot'   => '#A81EF8'
    ];
}

if (count($transaksiBaru) > 1) {
    $t2 = $transaksiBaru[1];
    $listAktivitas[] = [
        'tipe'  => 'Transaksi Diproses',
        'sub'   => '#CM-' . str_pad((string)$t2['idTransaksi'], 3, '0', STR_PAD_LEFT) . ' &middot; ' . ($t2['namaPembeli'] ?? ''),
        'waktu' => tanggal($t2['tanggal'] ?? null),
        'dot'   => '#0B911B'
    ];
}

if (empty($listAktivitas)) {
    $listAktivitas = [
        ['tipe' => 'Transaksi Baru', 'sub' => 'Belum ada transaksi terbaru', 'waktu' => '-', 'dot' => '#012CED'],
        ['tipe' => 'Produk Ditambahkan', 'sub' => 'Belum ada produk baru', 'waktu' => '-', 'dot' => '#0B911B'],
        ['tipe' => 'Pengguna Baru Terdaftar', 'sub' => 'Belum ada pendaftaran baru', 'waktu' => '-', 'dot' => '#A81EF8'],
    ];
}
?>

<!-- Header Selamat Datang -->
<div class="seller-card-head" style="margin-bottom: 24px">
  <div>
    <h1 class="seller-card-title">Dashboard Overview</h1>
    <p class="seller-card-sub">Selamat datang di panel kendali Cloud Market. Pantau ringkasan performa dan aktivitas platform secara real-time.</p>
  </div>
</div>

<!-- ==================== STAT CARDS GRID ==================== -->
<div class="stat-grid">
  <!-- 1. Total Transaksi -->
  <div class="stat-card">
    <div class="stat-icon-wrap stat-icon-blue">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 6v12M15 9.5c-.8-1-2.2-1.5-3-1.5s-2.5.5-2.5 2 1.5 2.5 3 2.5 2.5.5 2.5 2-1.5 2-3 2-2.5-.5-3-1.5"/>
      </svg>
    </div>
    <div class="stat-info">
      <div class="stat-val"><?= number_format($totalTransaksi, 0, ',', '.') ?></div>
      <div class="stat-label">Total Transaksi</div>
    </div>
  </div>

  <!-- 2. Produk Aktif -->
  <div class="stat-card">
    <div class="stat-icon-wrap stat-icon-green">
      <svg viewBox="0 0 24 24">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        <path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/>
      </svg>
    </div>
    <div class="stat-info">
      <div class="stat-val"><?= number_format($produkAktif, 0, ',', '.') ?></div>
      <div class="stat-label">Produk Aktif</div>
    </div>
  </div>

  <!-- 3. Total Pengguna -->
  <div class="stat-card">
    <div class="stat-icon-wrap stat-icon-purple">
      <svg viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
    </div>
    <div class="stat-info">
      <div class="stat-val"><?= number_format($totalPengguna, 0, ',', '.') ?></div>
      <div class="stat-label">Total Pengguna Terdaftar</div>
    </div>
  </div>

  <!-- 4. Verifikasi Toko Pending -->
  <a href="<?= e(url('/admin/verifikasi-penjual')) ?>" class="stat-card" style="text-decoration:none; color:inherit; border:<?= !empty($pendingVerifikasi) ? '1.5px solid #F59E0B' : '1px solid rgba(0,0,0,0.06)' ?>;">
    <div class="stat-icon-wrap" style="background:<?= !empty($pendingVerifikasi) ? '#FEF3C7' : '#F4F4F5' ?>; color:<?= !empty($pendingVerifikasi) ? '#D97706' : '#71717A' ?>;">
      <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
      </svg>
    </div>
    <div class="stat-info">
      <div class="stat-val" style="color:<?= !empty($pendingVerifikasi) ? '#D97706' : 'inherit' ?>;"><?= (int)($pendingVerifikasi ?? 0) ?></div>
      <div class="stat-label">Pengajuan Toko Pending &rarr;</div>
    </div>
  </a>
</div>

<!-- ==================== 2-COLUMN DASHBOARD GRID ==================== -->
<div class="adm-dash-grid">

  <!-- Kolom Kiri: Aktivitas Terbaru -->
  <div class="seller-card">
    <div class="seller-card-head" style="margin-bottom: 20px">
      <div>
        <h2 class="seller-card-title" style="font-size: 1.25rem">Aktivitas Terbaru</h2>
        <p class="seller-card-sub">Pembaruan transaksi, produk, dan pendaftaran pengguna terkini</p>
      </div>
      <a href="<?= e(url('/laporan')) ?>" class="btn btn-sm btn-ghost">Lihat Laporan &rarr;</a>
    </div>

    <div class="akt-list">
      <?php foreach ($listAktivitas as $akt): ?>
        <div class="akt-item">
          <div class="akt-dot" style="background:<?= $akt['dot'] ?>"></div>
          <div class="akt-content">
            <div class="akt-tipe"><?= e($akt['tipe']) ?></div>
            <div class="akt-sub"><?= e($akt['sub']) ?></div>
          </div>
          <div class="akt-waktu"><?= e($akt['waktu']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Kolom Kanan: Pintasan Menu & Info -->
  <div class="seller-card">
    <div class="seller-card-head" style="margin-bottom: 20px">
      <div>
        <h2 class="seller-card-title" style="font-size: 1.25rem">Aksi Cepat</h2>
        <p class="seller-card-sub">Pintasan navigasi utama</p>
      </div>
    </div>

    <div class="adm-quick-actions">
      <a href="<?= e(url('/admin/akun')) ?>" class="adm-quick-link">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Kelola Akun Pengguna</span>
      </a>

      <a href="<?= e(url('/admin/kategori')) ?>" class="adm-quick-link">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <span>Kelola Kategori Produk</span>
      </a>

      <a href="<?= e(url('/admin/kategori/tambah')) ?>" class="adm-quick-link">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        <span>Tambah Kategori Baru</span>
      </a>

      <a href="<?= e(url('/laporan')) ?>" class="adm-quick-link">
        <svg viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <span>Laporan Transaksi Platform</span>
      </a>

      <a href="<?= e(url('/')) ?>" class="adm-quick-link" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
        <span>Lihat Marketplace</span>
      </a>
    </div>
  </div>

</div>
