<?php
/**
 * KELOLA AKUN PENGGUNA - Desain Modern & Responsif.
 * Variabel: $pengguna, $q, $bisaNonaktifPembeli
 */
$this->title = 'Kelola Akun';
?>

<div class="seller-card">
  <div class="seller-card-head">
    <div>
      <h1 class="seller-card-title">Kelola Akun Pengguna</h1>
      <p class="seller-card-sub">Daftar akun penjual dan pembeli terdaftar. Pantau aktivitas dan status akun.</p>
    </div>
  </div>

  <!-- Form Pencarian Pengguna -->
  <form method="get" action="<?= e(url('/admin/akun')) ?>" class="seller-laporan-filter">
    <div class="filter-input-wrap">
      <input type="search" name="q" placeholder="Cari berdasarkan nama, username, toko, atau ID pengguna..." value="<?= e($q) ?>">
    </div>
    <button class="btn btn-sm" type="submit">Cari</button>
    <?php if ($q): ?>
      <a href="<?= e(url('/admin/akun')) ?>" class="btn btn-sm btn-ghost">Reset</a>
    <?php endif; ?>
  </form>

  <?php if (empty($pengguna)): ?>
    <div class="seller-kosong">
      <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
      </svg>
      <p>Tidak ada akun pengguna yang ditemukan<?= $q ? ' untuk pencarian "' . e($q) . '"' : '' ?>.</p>
      <?php if ($q): ?>
        <a href="<?= e(url('/admin/akun')) ?>" class="btn btn-sm btn-ghost">Tampilkan Semua Akun</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="table-wrap seller-table-wrap">
      <table class="seller-table">
        <thead>
          <tr>
            <th style="width:100px">ID Akun</th>
            <th>Nama & Toko</th>
            <th>Username & Peran</th>
            <th>No. Telepon</th>
            <th>Status</th>
            <th style="text-align:center;width:150px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pengguna as $u): ?>
            <tr>
              <td>
                <strong style="font-family:monospace;font-size:0.95rem">#<?= e($u['kode']) ?></strong>
              </td>
              <td>
                <strong><?= e($u['nama']) ?></strong>
                <?php if (!empty($u['toko'])): ?>
                  <div style="font-size:0.82rem;color:var(--pnj-muted)"><?= e($u['toko']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <strong><?= e($u['username']) ?></strong>
                <span class="seller-badge-kat" style="margin-left:4px">
                  <?= e(ucfirst($u['peran'])) ?>
                </span>
              </td>
              <td><?= e($u['noHp'] ?: '—') ?></td>
              <td>
                <span class="badge <?= $u['aktif'] ? 'badge-ok' : 'badge-bad' ?>">
                  <?= $u['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                </span>
              </td>
              <td class="aksi" style="text-align:center">
                <form method="post" action="<?= e(url('/admin/akun/' . $u['peran'] . '/' . $u['id'] . '/status')) ?>" style="display:inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="q" value="<?= e($q) ?>">
                  <?php if ($u['aktif']): ?>
                    <button type="submit" name="aksi" value="nonaktifkan" class="user-toggle-btn btn-deactivate" title="Nonaktifkan Akun" onclick="return confirm('Nonaktifkan akun <?= e($u['nama']) ?>?')">
                      <svg viewBox="0 0 24 24"><path d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"/></svg>
                      Nonaktifkan
                    </button>
                  <?php else: ?>
                    <button type="submit" name="aksi" value="aktifkan" class="user-toggle-btn btn-activate" title="Aktifkan Akun">
                      <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4L12 14.01l-3-3"/></svg>
                      Aktifkan
                    </button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
