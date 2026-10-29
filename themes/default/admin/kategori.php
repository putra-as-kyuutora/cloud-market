<?php
/**
 * KELOLA KATEGORI - Desain Grid Modern & Responsif.
 * Variabel: $kategori [idKategori, namaKategori, jumlahBarang, deskripsi]
 */
$this->title = 'Kelola Kategori';
?>

<div class="seller-card-head" style="margin-bottom: 20px">
  <div>
    <h1 class="seller-card-title">Kelola Kategori</h1>
    <p class="seller-card-sub">Atur kategori produk yang tersedia di katalog Cloud Market</p>
  </div>
  <a href="<?= e(url('/admin/kategori/tambah')) ?>" class="btn btn-primary">
    + Tambah Kategori
  </a>
</div>

<?php if (empty($kategori)): ?>
  <div class="seller-card">
    <div class="seller-kosong">
      <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
      </svg>
      <p>Belum ada kategori produk.</p>
      <a href="<?= e(url('/admin/kategori/tambah')) ?>" class="btn btn-primary">Tambah Kategori Pertama</a>
    </div>
  </div>
<?php else: ?>
  <div class="kat-grid">
    <?php foreach ($kategori as $k): ?>
      <?php
        $kode = 'CAT' . str_pad((string)$k['idKategori'], 3, '0', STR_PAD_LEFT);
        $desk = !empty($k['deskripsi']) ? $k['deskripsi'] : 'Barang & kebutuhan kategori ' . $k['namaKategori'];
        $adaBarang = (int)$k['jumlahBarang'] > 0;
      ?>
      <div class="kat-card">
        <!-- Header Kartu -->
        <div class="kat-head">
          <div class="kat-icon-box">
            <svg viewBox="0 0 24 24">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
              <path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/>
            </svg>
          </div>
          <div class="kat-title-wrap">
            <div class="kat-nama" title="<?= e($k['namaKategori']) ?>"><?= e($k['namaKategori']) ?></div>
            <div class="kat-kode"><?= e($kode) ?></div>
          </div>
        </div>

        <!-- Deskripsi -->
        <div class="kat-deskripsi" title="<?= e($desk) ?>">
          <?= e($desk) ?>
        </div>

        <!-- Footer: Jumlah & Aksi -->
        <div class="kat-foot">
          <div class="kat-jumlah">
            <?= (int)$k['jumlahBarang'] ?> Produk
          </div>

          <div class="kat-aksi">
            <!-- Edit Button -->
            <a href="<?= e(url('/admin/kategori/' . $k['idKategori'] . '/edit')) ?>" class="kat-btn-action edit" title="Edit Kategori">
              <svg viewBox="0 0 24 24">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 9.5-9.5z"/>
              </svg>
            </a>

            <!-- Delete Button -->
            <?php if (!$adaBarang): ?>
              <form method="post" action="<?= e(url('/admin/kategori/' . $k['idKategori'] . '/hapus')) ?>" style="display:inline" onsubmit="return confirm('Hapus kategori <?= e($k['namaKategori']) ?>?')">
                <?= csrf_field() ?>
                <button type="submit" class="kat-btn-action hapus" title="Hapus Kategori">
                  <svg viewBox="0 0 24 24">
                    <path d="M3 6h18M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                  </svg>
                </button>
              </form>
            <?php else: ?>
              <button type="button" class="kat-btn-action disabled" title="Kategori masih memiliki produk aktif, tidak dapat dihapus" onclick="alert('Kategori ini masih memiliki <?= (int)$k['jumlahBarang'] ?> produk aktif. Hapus atau pindahkan produk tersebut terlebih dahulu.')">
                <svg viewBox="0 0 24 24">
                  <path d="M3 6h18M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
              </button>
            <?php endif; ?>
          </div>
        </div>

      </div>
    <?php endforeach; ?>
  </div>

  <div class="adm-info-box">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
      <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
    </svg>
    <div>
      <strong>Petunjuk Pengelolaan Kategori:</strong> Kategori yang memiliki produk aktif tidak dapat dihapus demi menjaga integritas data katalog. Edit nama atau deskripsi dapat dilakukan sewaktu-waktu.
    </div>
  </div>
<?php endif; ?>
