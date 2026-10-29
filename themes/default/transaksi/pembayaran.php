<?php
/**
 * PEMBAYARAN
 * Variabel: $transaksi [idTransaksi,metodePembayaran,statusPembayaran,statusPengiriman,total,buktiPembayaran,alamatPengiriman],
 *           $items [namaBarang,jumlah,hargaSatuan,subtotal,gambar], $instruksi (teks), $butuhBukti (bool), $bisaBayar (bool)
 * FORM bayar : POST /pesanan/{id}/bayar (multipart) | field: _csrf, bukti (file; jika $butuhBukti)
 * FORM batal : POST /pesanan/{id}/batal              | field: _csrf
 */
$this->title = 'Pembayaran';
$id = (int)$transaksi['idTransaksi'];
?>
<h1>Pembayaran Pesanan #CM-<?= str_pad((string)$id, 3, '0', STR_PAD_LEFT) ?></h1>
<div class="two-col">
  <div class="card">
    <h3>Rincian</h3>
    <table><tbody>
      <?php foreach ($items as $it): ?>
        <tr><td><?= e($it['namaBarang']) ?> &times; <?= (int)$it['jumlah'] ?></td><td class="r"><?= e(rupiah($it['subtotal'])) ?></td></tr>
      <?php endforeach; ?>
      <tr class="total"><td>Total</td><td class="r"><?= e(rupiah($transaksi['total'])) ?></td></tr>
    </tbody></table>
    <p class="muted">Dikirim ke: <?= e($transaksi['alamatPengiriman']) ?></p>
    <p>Status: <?php $this->partial('partials/status_badge', ['s' => $transaksi['statusPembayaran']]); ?></p>
  </div>
  <div class="card">
    <h3>Metode: <?= e($transaksi['metodePembayaran']) ?></h3>
    <p><?= e($instruksi) ?></p>
    <?php if ($bisaBayar): ?>
      <?php if ($transaksi['statusPembayaran'] === 'Ditolak'): ?><div class="alert alert-bad">Pembayaran ditolak penjual. Silakan unggah bukti yang valid.</div><?php endif; ?>
      <form method="post" action="<?= e(url("/pesanan/$id/bayar")) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if ($butuhBukti): ?>
          <div class="field <?= isset($errors['bukti']) ? 'has-err' : '' ?>">
            <label>Bukti pembayaran</label>
            <input type="file" name="bukti" accept="image/jpeg,image/png,image/webp">
            <?php if (isset($errors['bukti'])): ?><div class="err"><?= e($errors['bukti']) ?></div><?php endif; ?>
          </div>
        <?php endif; ?>
        <button class="btn btn-block" type="submit"><?= $butuhBukti ? 'Kirim Bukti & Selesai' : 'Konfirmasi Pesanan (COD)' ?></button>
      </form>
      <form method="post" action="<?= e(url("/pesanan/$id/batal")) ?>" style="margin-top:.6rem">
        <?= csrf_field() ?>
        <button class="btn btn-block btn-danger" type="submit">Batalkan Pesanan</button>
      </form>
    <?php else: ?>
      <a class="btn" href="<?= e(url("/pesanan/$id")) ?>">Lihat Status</a>
    <?php endif; ?>
  </div>
</div>
