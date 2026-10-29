<?php
/**
 * PARTIAL: SIDEBAR PENJUAL (Sesuai mockup Alab/edit-produk.html)
 * Variabel: $aktif (string: 'produk', 'tambah', 'edit', 'pesanan', 'konfirmasi', 'laporan')
 */
$user = auth() ?? [];
$namaToko = $user['toko'] ?? ($user['nama'] ?? 'Toko Saya');
$inisialToko = mb_strtoupper(mb_substr($namaToko, 0, 1));
$aktif = $aktif ?? '';
?>
<aside class="seller-side">
  <div class="seller-side-profile">
    <div class="seller-side-avatar"><span><?= e($inisialToko) ?></span></div>
    <div class="seller-side-toko"><?= e($namaToko) ?></div>
  </div>

  <nav class="seller-side-nav">
    <a class="seller-menu-item <?= $aktif === 'produk' ? 'aktif' : '' ?>" href="<?= e(url('/penjual/barang')) ?>">Produk Saya</a>
    <a class="seller-menu-item <?= $aktif === 'tambah' ? 'aktif' : '' ?>" href="<?= e(url('/penjual/barang/tambah')) ?>">Tambah Produk</a>
    <a class="seller-menu-item <?= $aktif === 'edit' ? 'aktif' : '' ?>" href="<?= e(url('/penjual/barang/edit')) ?>">Edit Produk</a>
    <a class="seller-menu-item <?= $aktif === 'pesanan' ? 'aktif' : '' ?>" href="<?= e(url('/penjual/pesanan')) ?>">Pesanan Masuk</a>
    <a class="seller-menu-item <?= $aktif === 'konfirmasi' ? 'aktif' : '' ?>" href="<?= e(url('/penjual/konfirmasi')) ?>">Konfirmasi Pembayaran</a>
    <a class="seller-menu-item <?= $aktif === 'laporan' ? 'aktif' : '' ?>" href="<?= e(url('/laporan')) ?>">Laporan Transaksi</a>
  </nav>
</aside>
