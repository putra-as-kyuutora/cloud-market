<?php /** Kartu produk. Variabel: $b (baris Barang: idBarang,namaBarang,harga,stok,gambar,namaToko,namaKategori) */ ?>
<a class="kartu-produk" href="<?= e(url('/barang/' . $b['idBarang'])) ?>">
  <div class="foto">
    <?php if (!empty($b['gambar'])): ?>
      <img src="<?= e(upload_url($b['gambar'])) ?>" alt="<?= e($b['namaBarang']) ?>" loading="lazy">
    <?php else: ?>
      <div class="noimg">
        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
        </svg>
        <span>Tanpa Foto</span>
      </div>
    <?php endif; ?>
    <?php if ((int)$b['stok'] < 1): ?>
      <span class="ribbon">Habis</span>
    <?php endif; ?>
  </div>
  <div class="isi">
    <div class="kondisi-tag"><?= e($b['namaKategori']) ?></div>
    <div class="nama" title="<?= e($b['namaBarang']) ?>"><?= e($b['namaBarang']) ?></div>
    <div class="harga"><?= e(rupiah($b['harga'])) ?></div>
    <div class="toko">
      <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
      <span><?= e($b['namaToko']) ?></span>
    </div>
  </div>
</a>
