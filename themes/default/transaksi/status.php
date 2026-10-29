<?php
/**
 * STATUS TRANSAKSI
 * Variabel: $transaksi, $items, $kelompok ('menunggu' | 'berhasil' | 'batal')
 * FORM: POST /pesanan/{id}/diterima | field: _csrf (tampil jika statusPengiriman = 'Sudah Dikirim')
 */
$this->title = 'Status Transaksi';
$id = (int)$transaksi['idTransaksi'];
$pesan = [
    'berhasil' => ['Yeay!!! Transaksi kamu berhasil', 'ok'],
    'batal'    => ['Yaah.... transaksi kamu batal!', 'bad'],
    'menunggu' => [$transaksi['statusPembayaran'] === 'Menunggu Konfirmasi' ? 'Menunggu konfirmasi penjual...' : 'Menunggu Pembayaran...', 'warn'],
][$kelompok];
?>
<h1>Status Transaksi</h1>
<div class="status-box <?= e($pesan[1]) ?>">
  <div class="status-ikon"><?= $kelompok === 'berhasil' ? '&#10003;' : ($kelompok === 'batal' ? '&#10005;' : '&#8987;') ?></div>
  <h2><?= e($pesan[0]) ?></h2>
  <p>Pesanan #CM-<?= str_pad((string)$id, 3, '0', STR_PAD_LEFT) ?> &middot; <?= e(rupiah($transaksi['total'])) ?></p>
  <p>Pembayaran: <?php $this->partial('partials/status_badge', ['s' => $transaksi['statusPembayaran']]); ?>
     &nbsp; Pengiriman: <?php $this->partial('partials/status_badge', ['s' => $transaksi['statusPengiriman']]); ?></p>
  <ul class="plain">
    <?php foreach ($items as $it): ?><li><?= e($it['namaBarang']) ?> &times; <?= (int)$it['jumlah'] ?> &mdash; <?= e(rupiah($it['subtotal'])) ?></li><?php endforeach; ?>
  </ul>
  <div class="row-btn">
    <a class="btn btn-ghost" href="<?= e(url('/pesanan')) ?>">Kembali</a>
    <?php if (in_array($transaksi['statusPembayaran'], ['Menunggu Pembayaran', 'Ditolak'], true)): ?>
      <a class="btn" href="<?= e(url("/pesanan/$id/bayar")) ?>">Bayar Sekarang</a>
    <?php endif; ?>
    <?php if ($transaksi['statusPengiriman'] === 'Sudah Dikirim'): ?>
      <form method="post" action="<?= e(url("/pesanan/$id/diterima")) ?>"><?= csrf_field() ?><button class="btn" type="submit">Pesanan Diterima</button></form>
    <?php endif; ?>
  </div>
</div>
