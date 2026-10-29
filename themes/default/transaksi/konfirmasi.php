<?php
/**
 * KONFIRMASI PEMBAYARAN (penjual) - Sesuai Alab Dashboard Penjualan
 * Variabel: $daftar [idTransaksi,tanggal,namaPembeli,barang,total,metodePembayaran,statusPembayaran,buktiPembayaran],
 *           $statusDipilih, $semuaStatus
 * FORM filter: GET /penjual/konfirmasi?status=...
 * FORM aksi  : POST /penjual/konfirmasi/{id}/terima | /tolak  | field: _csrf
 */
$this->title = 'Konfirmasi Pembayaran';
?>

<div class="seller-card">
      <div class="seller-card-head">
        <div>
          <h1 class="seller-card-title">Konfirmasi Pembayaran</h1>
          <p class="seller-card-sub">Verifikasi bukti transfer dari pembeli sebelum memproses pesanan</p>
        </div>

        <form method="get" action="<?= e(url('/penjual/konfirmasi')) ?>" class="seller-filter-form">
          <select class="seller-filter-select" name="status" onchange="this.form.submit()">
            <option value="semua" <?= $statusDipilih === 'semua' ? 'selected' : '' ?>>Semua Status</option>
            <?php foreach ($semuaStatus as $s): ?>
              <option value="<?= e($s) ?>" <?= $statusDipilih === $s ? 'selected' : '' ?>><?= e($s) ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </div>

      <?php if (!$daftar): ?>
        <div class="seller-kosong">
          <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p>Tidak ada data pembayaran untuk status ini.</p>
        </div>
      <?php else: ?>
        <div class="table-wrap seller-table-wrap">
          <table class="seller-table">
            <thead>
              <tr>
                <th>No. Pesanan</th>
                <th>Pembeli</th>
                <th>Jumlah</th>
                <th>Metode</th>
                <th>Bukti Transfer</th>
                <th>Status</th>
                <th style="text-align:center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($daftar as $p): ?>
                <tr>
                  <td>
                    <strong style="font-family:monospace;font-size:1rem">#CM-<?= str_pad((string)$p['idTransaksi'], 3, '0', STR_PAD_LEFT) ?></strong>
                    <div style="font-size:0.8rem;color:var(--muted)"><?= e(tanggal($p['tanggal'])) ?></div>
                  </td>
                  <td><strong><?= e($p['namaPembeli']) ?></strong></td>
                  <td><strong class="seller-prod-price"><?= e(rupiah($p['total'])) ?></strong></td>
                  <td><span class="seller-badge-metode"><?= e($p['metodePembayaran']) ?></span></td>
                  <td>
                    <?php if ($p['buktiPembayaran']): ?>
                      <a href="<?= e(upload_url($p['buktiPembayaran'])) ?>" target="_blank" rel="noopener" class="seller-bukti-preview" title="Klik untuk perbesar bukti">
                        <img class="seller-thumb" src="<?= e(upload_url($p['buktiPembayaran'])) ?>" alt="Bukti Transfer">
                      </a>
                    <?php else: ?>
                      <span class="muted">&mdash;</span>
                    <?php endif; ?>
                  </td>
                  <td><?php $this->partial('partials/status_badge', ['s' => $p['statusPembayaran']]); ?></td>
                  <td class="aksi" style="text-align:center">
                    <?php if ($p['statusPembayaran'] === 'Menunggu Konfirmasi'): ?>
                      <div style="display:flex;gap:0.4rem;justify-content:center">
                        <form method="post" action="<?= e(url('/penjual/konfirmasi/' . $p['idTransaksi'] . '/terima')) ?>" style="display:inline">
                          <?= csrf_field() ?>
                          <button class="btn btn-sm" type="submit">Terima</button>
                        </form>
                        <form method="post" action="<?= e(url('/penjual/konfirmasi/' . $p['idTransaksi'] . '/tolak')) ?>" style="display:inline">
                          <?= csrf_field() ?>
                          <button class="btn btn-sm btn-danger" type="submit">Tolak</button>
                        </form>
                      </div>
                    <?php else: ?>
                      <span class="muted">&mdash;</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
