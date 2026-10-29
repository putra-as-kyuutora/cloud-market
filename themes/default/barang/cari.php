<?php
/**
 * CARI BARANG
 * Variabel: $filter [q,kategori,min,max,urut], $hasil [items,total,page,pages], $kategori
 * FORM: GET /barang | field: q, kategori, min, max, urut(terbaru|termurah|termahal), page
 */
$this->title = 'Cari Barang';
?>
<h1>Cari Barang</h1>
<form class="filter" method="get" action="<?= e(url('/barang')) ?>">
  <input type="search" name="q" placeholder="Nama barang..." value="<?= e($filter['q']) ?>">
  <select name="kategori">
    <option value="">Semua kategori</option>
    <?php foreach ($kategori as $k): ?>
      <option value="<?= (int)$k['idKategori'] ?>" <?= (int)$filter['kategori'] === (int)$k['idKategori'] ? 'selected' : '' ?>><?= e($k['namaKategori']) ?></option>
    <?php endforeach; ?>
  </select>
  <input type="number" name="min" min="0" placeholder="Harga min" value="<?= e($filter['min']) ?>">
  <input type="number" name="max" min="0" placeholder="Harga max" value="<?= e($filter['max']) ?>">
  <select name="urut">
    <?php foreach (['terbaru' => 'Terbaru', 'termurah' => 'Harga terendah', 'termahal' => 'Harga tertinggi'] as $v => $l): ?>
      <option value="<?= $v ?>" <?= $filter['urut'] === $v ? 'selected' : '' ?>><?= $l ?></option>
    <?php endforeach; ?>
  </select>
  <button class="btn" type="submit">Cari</button>
</form>

<p class="muted"><?= (int)$hasil['total'] ?> barang ditemukan</p>
<?php if (!$hasil['items']): ?>
  <div class="center-box"><h2>Barang tidak ditemukan</h2><p>Coba periksa kata kunci atau ubah filter pencarian.</p></div>
<?php endif; ?>
<div class="grid">
  <?php foreach ($hasil['items'] as $b) $this->partial('partials/produk_kartu', ['b' => $b]); ?>
</div>
<?php $this->partial('partials/paginasi', ['hasil' => $hasil, 'filter' => $filter, 'path' => '/barang']); ?>
