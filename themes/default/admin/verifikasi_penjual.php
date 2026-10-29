<?php
/**
 * DAFTAR VERIFIKASI PENJUAL (Admin)
 * Variabel: $daftar, $statusFilter, $pendingCount
 */
$this->title = 'Verifikasi Penjual & KTP';
?>

<div class="adm-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.75rem;">
  <div>
    <h1 style="font-size: 1.65rem; font-weight: 800; color: #111; margin:0;">Verifikasi Penjual &amp; KTP</h1>
    <p style="color: var(--pnj-muted); font-size: 0.9rem; margin-top: 0.25rem;">
      Tinjau berkas identitas KTP calon penjual untuk memastikan keamanan marketplace.
    </p>
  </div>
  <?php if ($pendingCount > 0): ?>
    <div style="background:#fef3c7; color:#92400e; border:1px solid #fcd34d; padding:0.45rem 1rem; border-radius:999px; font-weight:700; font-size:0.85rem;">
      <?= (int)$pendingCount ?> Pengajuan Menunggu Tinjauan
    </div>
  <?php endif; ?>
</div>

<!-- FILTER TABS -->
<div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; border-bottom:1px solid rgba(0,0,0,0.08); padding-bottom:0.75rem;">
  <?php foreach (['pending' => 'Menunggu Tinjauan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'semua' => 'Semua Pengajuan'] as $k => $label): ?>
    <a href="<?= e(url('/admin/verifikasi-penjual', ['status' => $k])) ?>"
       style="padding:0.45rem 1rem; border-radius:10px; font-weight:700; font-size:0.88rem; text-decoration:none; transition:all 0.15s ease; <?= $statusFilter === $k ? 'background:#111; color:#fff;' : 'background:#f4f4f5; color:#555;' ?>">
      <?= e($label) ?>
      <?php if ($k === 'pending' && $pendingCount > 0): ?>
        <span style="background:<?= $statusFilter === 'pending' ? '#ef4444' : '#ef4444' ?>; color:#fff; font-size:0.72rem; padding:1px 6px; border-radius:999px; margin-left:0.25rem;"><?= $pendingCount ?></span>
      <?php endif; ?>
    </a>
  <?php endforeach; ?>
</div>

<!-- TABEL PENGAJUAN -->
<div class="seller-card" style="background:#fff; border-radius:18px; padding:1.25rem; border:1px solid rgba(0,0,0,0.08); overflow-x:auto;">
  <?php if (empty($daftar)): ?>
    <div style="text-align:center; padding:3.5rem 1.5rem; color:var(--pnj-muted);">
      <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35; margin-bottom:0.75rem;">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
      </svg>
      <p style="font-weight:600; font-size:1rem; margin:0;">Tidak ada pengajuan verifikasi dalam kategori ini.</p>
    </div>
  <?php else: ?>
    <table class="adm-table" style="width:100%; border-collapse:collapse; text-align:left; font-size:0.9rem;">
      <thead>
        <tr style="border-bottom:2px solid #f1f1f1; color:var(--pnj-muted); font-size:0.8rem; text-transform:uppercase; letter-spacing:0.04em;">
          <th style="padding:0.75rem 1rem;">Pemohon</th>
          <th style="padding:0.75rem 1rem;">Nama Toko</th>
          <th style="padding:0.75rem 1rem;">No. KTP</th>
          <th style="padding:0.75rem 1rem;">Tanggal Diajukan</th>
          <th style="padding:0.75rem 1rem;">Status</th>
          <th style="padding:0.75rem 1rem; text-align:right;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($daftar as $p): ?>
          <tr style="border-bottom:1px solid #f5f5f5;">
            <td style="padding:1rem;">
              <strong><?= e($p['nama']) ?></strong><br>
              <span style="color:var(--pnj-muted); font-size:0.82rem;">@<?= e($p['username']) ?> &middot; <?= e($p['noHp']) ?></span>
            </td>
            <td style="padding:1rem;">
              <strong><?= e($p['namaToko']) ?></strong>
            </td>
            <td style="padding:1rem;">
              <code><?= e($p['noKtp'] ?: '-') ?></code>
            </td>
            <td style="padding:1rem; color:var(--pnj-muted); font-size:0.85rem;">
              <?= e(date('d M Y, H:i', strtotime($p['created_at']))) ?>
            </td>
            <td style="padding:1rem;">
              <?php if ($p['statusVerifikasi'] === 'approved'): ?>
                <span class="badge" style="background:#d1fae5; color:#065f46; font-size:0.78rem; padding:3px 9px; border-radius:999px; font-weight:700;">Disetujui</span>
              <?php elseif ($p['statusVerifikasi'] === 'rejected'): ?>
                <span class="badge" style="background:#fee2e2; color:#991b1b; font-size:0.78rem; padding:3px 9px; border-radius:999px; font-weight:700;">Ditolak</span>
              <?php else: ?>
                <span class="badge" style="background:#fef3c7; color:#92400e; font-size:0.78rem; padding:3px 9px; border-radius:999px; font-weight:700;">Menunggu Review</span>
              <?php endif; ?>
            </td>
            <td style="padding:1rem; text-align:right;">
              <a href="<?= e(url('/admin/verifikasi-penjual/' . $p['idUser'])) ?>"
                 class="btn btn-sm" style="background:#111; color:#fff; padding:0.4rem 0.85rem; border-radius:8px; font-weight:600; text-decoration:none;">
                Tinjau Berkas &rarr;
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
