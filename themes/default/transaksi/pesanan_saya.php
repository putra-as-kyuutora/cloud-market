<?php
/** PESANAN SAYA (pembeli). Variabel: $pesanan [idTransaksi,tanggal,barang,total,metodePembayaran,statusPembayaran,statusPengiriman] */
$this->title = 'Pesanan Saya';
?>
<h1>Pesanan Saya</h1>
<?php if (!$pesanan): ?><p class="kosong">Belum ada pesanan.</p><?php else: ?>
<div class="table-wrap"><table>
  <thead><tr><th>No</th><th>Tanggal</th><th>Barang</th><th>Total</th><th>Pembayaran</th><th>Pengiriman</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($pesanan as $p): ?>
    <tr>
      <td>#CM-<?= str_pad((string)$p['idTransaksi'], 3, '0', STR_PAD_LEFT) ?></td>
      <td><?= e(tanggal($p['tanggal'])) ?></td>
      <td><?= e($p['barang']) ?></td>
      <td><?= e(rupiah($p['total'])) ?></td>
      <td><?php $this->partial('partials/status_badge', ['s' => $p['statusPembayaran']]); ?></td>
      <td><?php $this->partial('partials/status_badge', ['s' => $p['statusPengiriman']]); ?></td>
      <td class="aksi">
        <a class="btn btn-sm btn-ghost" href="<?= e(url('/pesanan/' . $p['idTransaksi'])) ?>">Detail</a>
        <?php if (in_array($p['statusPembayaran'], ['Menunggu Pembayaran', 'Ditolak'], true)): ?>
          <a class="btn btn-sm" href="<?= e(url('/pesanan/' . $p['idTransaksi'] . '/bayar')) ?>">Bayar</a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table></div>
<?php endif; ?>
