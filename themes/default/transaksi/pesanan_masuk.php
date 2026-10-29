<?php
/**
 * PESANAN MASUK (penjual) - Sesuai Alab Dashboard Penjualan
 * Variabel: $pesanan [idTransaksi,tanggal,namaPembeli,barang,total,metodePembayaran,statusPembayaran,statusPengiriman]
 * FORM kirim: POST /penjual/pesanan/{id}/kirim | field: _csrf
 */
$this->title = 'Pesanan Masuk';
?>

<div class="seller-card">
      <div class="seller-card-head">
        <div>
          <h1 class="seller-card-title">Pesanan Masuk</h1>
          <p class="seller-card-sub">Daftar pesanan baru dari pembeli yang siap dikonfirmasi dan dikirim</p>
        </div>
      </div>

      <?php if (!$pesanan): ?>
        <div class="seller-kosong">
          <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
          <p>Belum ada pesanan masuk saat ini.</p>
        </div>
      <?php else: ?>
        <div class="table-wrap seller-table-wrap">
          <table class="seller-table">
            <thead>
              <tr>
                <th>No. Pesanan</th>
                <th>Pembeli</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Pengiriman</th>
                <th style="text-align:center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pesanan as $p): ?>
                <tr>
                  <td><strong style="font-family:monospace;font-size:1rem">#CM-<?= str_pad((string)$p['idTransaksi'], 3, '0', STR_PAD_LEFT) ?></strong></td>
                  <td><strong><?= e($p['namaPembeli']) ?></strong></td>
                  <td><?= e($p['barang']) ?></td>
                  <td><strong class="seller-prod-price"><?= e(rupiah($p['total'])) ?></strong></td>
                  <td><?php $this->partial('partials/status_badge', ['s' => $p['statusPembayaran']]); ?></td>
                  <td><?php $this->partial('partials/status_badge', ['s' => $p['statusPengiriman']]); ?></td>
                  <td class="aksi" style="text-align:center">
                    <?php if ($p['statusPembayaran'] === 'Menunggu Konfirmasi'): ?>
                      <a class="btn btn-sm" href="<?= e(url('/penjual/konfirmasi')) ?>">Konfirmasi</a>
                    <?php elseif ($p['statusPembayaran'] === 'Sudah Dibayar' && $p['statusPengiriman'] === 'Sedang Diproses'): ?>
                      <form method="post" action="<?= e(url('/penjual/pesanan/' . $p['idTransaksi'] . '/kirim')) ?>" style="display:inline">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm" type="submit">Tandai Dikirim</button>
                      </form>
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
