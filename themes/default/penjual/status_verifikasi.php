<?php
/**
 * HALAMAN STATUS VERIFIKASI PENJUAL (KTP Review)
 * Variabel: $sellerProfile, $user
 */
$this->title = 'Status Verifikasi Toko';
$status = $sellerProfile['statusVerifikasi'] ?? 'pending';
?>

<div class="container" style="max-width: 680px; margin: 3rem auto 5rem;">
  <div class="seller-card" style="background:#fff; border-radius: 24px; padding: 3rem 2.5rem; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.06);">

    <?php if ($status === 'approved'): ?>
      <!-- STATUS: APPROVED -->
      <div style="width: 72px; height: 72px; background: rgba(16, 185, 129, 0.12); color: #10B981; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
      </div>

      <span class="badge" style="background:#d1fae5; color:#065f46; font-size:0.82rem; font-weight:700; padding: 4px 12px; border-radius: 999px; display:inline-block; margin-bottom: 1rem;">
        VERIFIKASI DISETUJUI
      </span>

      <h1 style="font-size: 1.85rem; font-weight: 800; color: #111; margin-bottom: 0.75rem;">
        Selamat! Toko Anda Telah Aktif
      </h1>
      <p style="color: var(--muted); font-size: 0.98rem; line-height: 1.6; margin-bottom: 2rem;">
        Pengajuan toko <strong><?= e($sellerProfile['namaToko']) ?></strong> telah disetujui oleh Administrator. Anda sekarang dapat mulai mengunggah produk dan menerima pesanan dari pembeli.
      </p>

      <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="<?= e(url('/penjual/barang')) ?>" class="btn" style="padding: 0.75rem 2rem; font-weight: 700;">
          Buka Dashboard Toko &rarr;
        </a>
      </div>

    <?php elseif ($status === 'rejected'): ?>
      <!-- STATUS: REJECTED -->
      <div style="width: 72px; height: 72px; background: rgba(239, 68, 68, 0.12); color: #EF4444; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </div>

      <span class="badge" style="background:#fee2e2; color:#991b1b; font-size:0.82rem; font-weight:700; padding: 4px 12px; border-radius: 999px; display:inline-block; margin-bottom: 1rem;">
        VERIFIKASI BELUM DISETUJUI
      </span>

      <h1 style="font-size: 1.85rem; font-weight: 800; color: #111; margin-bottom: 0.75rem;">
        Pengajuan Toko Perlu Diperbaiki
      </h1>

      <div style="background: #FFF5F5; border: 1px solid #FED7D7; border-radius: 14px; padding: 1.25rem; margin: 1.5rem 0 2rem; text-align: left;">
        <div style="font-weight: 700; font-size: 0.9rem; color: #C53030; margin-bottom: 0.25rem;">Alasan Penolakan dari Admin:</div>
        <div style="font-size: 0.95rem; color: #4A5568; line-height: 1.5;">
          <?= nl2br(e($sellerProfile['alasanPenolakan'] ?: 'Foto KTP / identitas belum memenuhi persyaratan (buram atau tidak sesuai).')) ?>
        </div>
      </div>

      <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="<?= e(url('/penjual/daftar')) ?>" class="btn" style="padding: 0.75rem 2rem; font-weight: 700;">
          Perbaiki Berkas &amp; Ajukan Ulang &rarr;
        </a>
      </div>

    <?php else: ?>
      <!-- STATUS: PENDING REVIEW -->
      <div style="width: 72px; height: 72px; background: rgba(245, 158, 11, 0.12); color: #F59E0B; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
      </div>

      <span class="badge" style="background:#fef3c7; color:#92400e; font-size:0.82rem; font-weight:700; padding: 4px 12px; border-radius: 999px; display:inline-block; margin-bottom: 1rem;">
        MENUNGGU TINJAUAN ADMIN
      </span>

      <h1 style="font-size: 1.85rem; font-weight: 800; color: #111; margin-bottom: 0.75rem;">
        Pengajuan Sedang Diproses
      </h1>
      <p style="color: var(--muted); font-size: 0.98rem; line-height: 1.6; margin-bottom: 2rem;">
        Terima kasih telah mendaftar! Berkas KTP dan data toko <strong><?= e($sellerProfile['namaToko']) ?></strong> sedang dalam proses pemeriksaan oleh tim verifikasi Cloud Market.
      </p>

      <div style="background: #F8FAFC; border-radius: 14px; padding: 1.25rem 1.5rem; text-align: left; margin-bottom: 2.25rem; font-size: 0.9rem;">
        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; border-bottom:1px solid rgba(0,0,0,0.05); padding-bottom:0.5rem;">
          <span style="color:var(--muted)">Nama Toko:</span>
          <strong><?= e($sellerProfile['namaToko']) ?></strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; border-bottom:1px solid rgba(0,0,0,0.05); padding-bottom:0.5rem;">
          <span style="color:var(--muted)">No. KTP (NIK):</span>
          <code><?= e(substr($sellerProfile['noKtp'] ?? '################', 0, 6) . '******' . substr($sellerProfile['noKtp'] ?? '', -4)) ?></code>
        </div>
        <div style="display:flex; justify-content:space-between;">
          <span style="color:var(--muted)">Estimasi Waktu:</span>
          <span style="color:#059669; font-weight:600;">1 - 3 Hari Kerja</span>
        </div>
      </div>

      <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="<?= e(url('/')) ?>" class="btn btn-ghost">Kembali ke Beranda</a>
        <a href="<?= e(url('/penjual/status-verifikasi')) ?>" class="btn" style="background:#111; color:#fff">Muat Ulang Status</a>
      </div>

    <?php endif; ?>

  </div>
</div>
