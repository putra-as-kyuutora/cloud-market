<?php
/**
 * PRODUK SAYA (penjual) - Sesuai Alab Dashboard Penjualan
 * Variabel: $barang [baris Barang milik penjual]
 */
$this->title = 'Produk Saya';
?>

<div class="seller-card">
      <div class="seller-card-head">
        <div>
          <h1 class="seller-card-title">Produk Saya</h1>
          <p class="seller-card-sub">Kelola seluruh produk aktif yang dijual di toko Anda</p>
        </div>
        <a class="btn btn-primary" href="<?= e(url('/penjual/barang/tambah')) ?>">+ Tambah Produk</a>
      </div>

      <?php if (!$barang): ?>
        <div class="seller-kosong">
          <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          <p>Belum ada produk di toko Anda.</p>
          <a class="btn" href="<?= e(url('/penjual/barang/tambah')) ?>">Tambah Produk Pertama</a>
        </div>
      <?php else: ?>
        <div class="table-wrap seller-table-wrap">
          <table class="seller-table">
            <thead>
              <tr>
                <th style="width:70px">Foto</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th style="text-align:center">Stok</th>
                <th style="text-align:center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($barang as $b): ?>
                <tr>
                  <td>
                    <?php if ($b['gambar']): ?>
                      <img class="seller-thumb" src="<?= e(upload_url($b['gambar'])) ?>" alt="<?= e($b['namaBarang']) ?>">
                    <?php else: ?>
                      <div class="seller-thumb-empty"></div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a class="seller-prod-link" href="<?= e(url('/barang/' . $b['idBarang'])) ?>"><?= e($b['namaBarang']) ?></a>
                  </td>
                  <td><span class="seller-badge-kat"><?= e($b['namaKategori']) ?></span></td>
                  <td><strong class="seller-prod-price"><?= e(rupiah($b['harga'])) ?></strong></td>
                  <td style="text-align:center"><?= (int)$b['stok'] ?></td>
                  <td class="aksi" style="text-align:center">
                    <a class="btn btn-sm btn-ghost" href="<?= e(url('/penjual/barang/' . $b['idBarang'] . '/edit')) ?>" title="Edit Produk">Edit</a>
                    <a class="btn btn-sm btn-danger" href="<?= e(url('/penjual/barang/' . $b['idBarang'] . '/hapus')) ?>" title="Hapus Produk">Hapus</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
