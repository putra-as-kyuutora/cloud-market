<?php
/**
 * DETAIL BARANG
 * Variabel: $barang [idBarang,namaBarang,harga,stok,deskripsi,gambar,namaKategori,namaToko,verified,namaPenjual,idPenjual]
 * Aksi: link ke GET /beli/{idBarang} (hanya pembeli)
 */
$this->title = $barang['namaBarang'];
$inisial = mb_strtoupper(mb_substr($barang['namaToko'] ?: $barang['namaPenjual'], 0, 1));
?>

<!-- Breadcrumb / Back -->
<nav class="detail-breadcrumb">
  <a href="javascript:history.back()" class="detail-back">
    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/></svg>
    Kembali
  </a>
  <span class="detail-bc-sep">›</span>
  <a href="<?= e(url('/barang')) ?>">Cari Barang</a>
  <span class="detail-bc-sep">›</span>
  <span><?= e($barang['namaKategori']) ?></span>
</nav>

<!-- Card Utama -->
<div class="detail-card">

  <!-- Kolom Kiri: Foto -->
  <div class="detail-foto-col">
    <div class="detail-foto-utama" id="fotoUtama">
      <?php if ($barang['gambar']): ?>
        <img src="<?= e(upload_url($barang['gambar'])) ?>" alt="<?= e($barang['namaBarang']) ?>" id="mainImg">
      <?php else: ?>
        <div class="detail-noimg">
          <svg viewBox="0 0 24 24" width="56" height="56" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.3"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          <span>Belum ada foto</span>
        </div>
      <?php endif; ?>
    </div>
    <!-- Thumbnail strip (placeholder warna jika tidak ada gambar) -->
    <div class="detail-thumbs">
      <?php if ($barang['gambar']): ?>
        <div class="detail-thumb sel" onclick="selectThumb(this, '<?= e(upload_url($barang['gambar'])) ?>')">
          <img src="<?= e(upload_url($barang['gambar'])) ?>" alt="">
        </div>
      <?php else: ?>
        <div class="detail-thumb sel"></div>
        <div class="detail-thumb" style="opacity:.45"></div>
        <div class="detail-thumb" style="opacity:.25"></div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Kolom Kanan: Info & Aksi -->
  <div class="detail-info-col">

    <!-- Kategori nav bar (sesuai mockup nav hitam di dalam card) -->
    <div class="detail-nav-bar">
      <span class="detail-nav-item aktif"><?= e($barang['namaKategori']) ?></span>
      <a href="<?= e(url('/barang?kategori=' . urlencode($barang['namaKategori']))) ?>" class="detail-nav-item">Lihat Semua Kategori →</a>
    </div>

    <!-- Judul & Harga -->
    <h1 class="detail-judul"><?= e($barang['namaBarang']) ?></h1>
    <div class="detail-harga"><?= e(rupiah($barang['harga'])) ?></div>

    <!-- Meta: kondisi, kategori -->
    <div class="detail-meta">
      <span class="detail-dot"></span><span>Bekas</span>
      <span class="detail-dot"></span><span><?= e($barang['namaKategori']) ?></span>
      <?php if ($barang['verified']): ?>
        <span class="detail-dot"></span><span class="detail-verified">✔ Terverifikasi</span>
      <?php endif; ?>
    </div>

    <!-- Tombol Aksi -->
    <div class="detail-actions">
      <?php if ((int)$barang['stok'] < 1): ?>
        <button class="detail-btn detail-btn-beli" disabled>Stok Habis</button>
      <?php elseif (\App\Core\Auth::isAdmin()): ?>
        <div class="alert alert-info" style="margin:0;border-radius:12px;background:#F2F2F7;color:#374151;border:1px solid #E5E7EB;font-size:0.88rem;line-height:1.45;">
          <strong>Mode Administrator:</strong> Anda dapat melihat &amp; memantau detail produk ini (transaksi pembelian dinonaktifkan untuk akun Admin).
        </div>
      <?php elseif (!\App\Core\Auth::check()): ?>
        <a class="detail-btn detail-btn-beli" href="<?= e(url('/login')) ?>">Login untuk Membeli</a>
      <?php elseif ((int)$barang['idPenjual'] === \App\Core\Auth::id()): ?>
        <div class="alert alert-info" style="margin:0;border-radius:12px;background:#F2F2F7;color:#374151;border:1px solid #E5E7EB;font-size:0.88rem;line-height:1.45;">
          Ini adalah produk dari toko Anda sendiri.
        </div>
        <a class="detail-btn detail-btn-beli" href="<?= e(url('/penjual/barang/' . $barang['idBarang'] . '/edit')) ?>" style="text-align:center">Edit Produk Ini</a>
      <?php else: ?>
        <a class="detail-btn detail-btn-beli" href="<?= e(url('/beli/' . $barang['idBarang'])) ?>">Beli Langsung</a>
        <button class="detail-btn detail-btn-keranjang" onclick="alert('Fitur keranjang: langsung klik Beli Langsung untuk memproses pesanan.')">+ Keranjang</button>
        <a class="detail-btn detail-btn-nego" href="<?= e(url('/pesan', ['barang' => $barang['idBarang'], 'nego' => 1])) ?>">Nego Barang</a>
      <?php endif; ?>
    </div>

    <!-- Deskripsi -->
    <div class="detail-desc-wrap">
      <div class="detail-desc-label">Detail Barang</div>
      <p class="detail-desc pre"><?= e($barang['deskripsi'] ?: 'Belum ada deskripsi untuk barang ini.') ?></p>
      <div class="detail-stok-info">Stok tersedia: <strong><?= (int)$barang['stok'] ?></strong> unit</div>
    </div>

    <hr class="detail-sep">

    <!-- Info Penjual -->
    <div class="detail-seller">
      <div class="detail-seller-avatar"><?= e($inisial) ?></div>
      <div class="detail-seller-info">
        <div class="detail-seller-nama"><?= e($barang['namaToko'] ?: $barang['namaPenjual']) ?></div>
        <div class="detail-seller-sub">
          <?= e($barang['namaPenjual']) ?>
          <?php if ($barang['verified']): ?> &middot; <span class="detail-verified">Terverifikasi</span><?php endif; ?>
        </div>
        <div class="detail-seller-stars">
          <?php for ($i = 0; $i < 5; $i++): ?><svg viewBox="0 0 16 16" width="16" height="16" fill="#F6E019" stroke="#E0C800" stroke-width="0.8"><path d="M8 1l1.9 4 4.4.6-3.2 3.1.8 4.3L8 11l-3.9 2 .8-4.3L1.7 5.6 6.1 5z"/></svg><?php endfor; ?>
        </div>
      </div>
      <div class="detail-seller-btns">
        <a class="detail-sec-btn" href="<?= e(url('/barang', ['q' => $barang['namaToko'] ?: $barang['namaPenjual']])) ?>">Lihat Produk Lain</a>
        <a class="detail-sec-btn" href="<?= e(url('/pesan', ['barang' => $barang['idBarang']])) ?>">Kirim Pesan</a>
      </div>
    </div>

  </div>
</div>

<script>
function selectThumb(el, src) {
  document.querySelectorAll('.detail-thumb').forEach(function(t){ t.classList.remove('sel'); });
  el.classList.add('sel');
  var img = document.getElementById('mainImg');
  if (img && src) img.src = src;
}
</script>
