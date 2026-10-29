<?php
/**
 * PILIH PRODUK UNTUK DI EDIT (Penjual) - Alab/edit-produk.html
 * Variabel: $barang [array of Barang milik penjual], $user
 */
$this->title = 'Pilih Produk Untuk di Edit';
?>

<div class="edit-card">
  <h1 class="edit-card-heading">Pilih Produk Untuk di Edit</h1>

  <?php if (empty($barang)): ?>
    <div class="edit-kosong">
      <p>Belum ada produk untuk diedit.</p>
      <a class="btn" href="<?= e(url('/penjual/barang/tambah')) ?>">+ Tambah Produk Baru</a>
    </div>
  <?php else: ?>
    <div class="edit-product-list">
      <?php foreach ($barang as $idx => $b): ?>
        <a class="edit-product-row" href="<?= e(url('/penjual/barang/' . $b['idBarang'] . '/edit')) ?>" title="Klik untuk edit <?= e($b['namaBarang']) ?>">
          <!-- Foto Produk -->
          <div class="edit-product-foto">
            <?php if (!empty($b['gambar'])): ?>
              <img src="<?= e(upload_url($b['gambar'])) ?>" alt="<?= e($b['namaBarang']) ?>">
            <?php else: ?>
              <div class="edit-foto-fallback f<?= ($idx % 5) + 1 ?>"></div>
            <?php endif; ?>
          </div>

          <!-- Info Nama & Harga -->
          <div class="edit-product-info">
            <div class="edit-product-nama"><?= e($b['namaBarang']) ?></div>
            <div class="edit-product-harga"><?= e(rupiah($b['harga'])) ?></div>
          </div>

          <!-- Garis Pemisah Vertikal -->
          <div class="edit-product-sep"></div>

          <!-- Ikon Pensil Sesuai Mockup Alab -->
          <div class="edit-product-pensil" aria-label="Edit">
            <svg viewBox="0 0 108 81" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 50 L56 8 C60 5 64 6 67 9 L96 36 C99 39 99 43 95 46 L62 58"/>
              <path d="M4 50 L20 66 H62"/>
              <path d="M28 58 L74 20"/>
              <path d="M26 78 H100"/>
            </svg>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
