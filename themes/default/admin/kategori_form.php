<?php
/**
 * FORM TAMBAH / EDIT KATEGORI - Desain Form Modern (Serasi dengan Form Penjual).
 * Variabel: $kategori, $aksi, $punyaDeskripsi, $jumlahBarang, $errors
 */
$edit = ($kategori !== null);
$this->title = $edit ? 'Edit Kategori' : 'Tambah Kategori';
$namaVal = old('namaKategori', $edit ? $kategori['namaKategori'] : '');
$deskVal = old('deskripsi', $edit ? ($kategori['deskripsi'] ?? '') : '');
?>

<div class="edit-form-card">
  <div class="seller-card-head" style="margin-bottom: 24px">
    <div>
      <h1 class="seller-card-title"><?= e($this->title) ?></h1>
      <p class="seller-card-sub">Isi detail informasi kategori untuk katalog platform Cloud Market</p>
    </div>
  </div>

  <form method="post" action="<?= e($aksi) ?>" novalidate>
    <?= csrf_field() ?>

    <!-- Field 1: Nama Kategori -->
    <div class="edit-form-group <?= !empty($errors['namaKategori']) ? 'has-err' : '' ?>">
      <label class="edit-form-lbl" for="namaKategori">Nama Kategori</label>
      <input type="text" id="namaKategori" name="namaKategori" class="edit-form-input"
             value="<?= e($namaVal) ?>" required autofocus placeholder="Contoh: Elektronik, Fashion, Olahraga...">
      <?php if (!empty($errors['namaKategori'])): ?>
        <div class="edit-form-err"><?= e($errors['namaKategori']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Field 2: Deskripsi Kategori -->
    <div class="edit-form-group">
      <label class="edit-form-lbl" for="deskripsi">Deskripsi Kategori</label>
      <textarea id="deskripsi" name="deskripsi" class="edit-form-textarea" rows="4"
                placeholder="Tuliskan deskripsi kategori atau contoh barang yang cocok dalam kategori ini..."><?= e($deskVal) ?></textarea>
      <div class="edit-form-hint">Deskripsi membantu pembeli memahami kelompok produk dalam kategori ini.</div>
    </div>

    <!-- Box Peringatan jika kategori memiliki barang aktif -->
    <?php if ($edit && ($jumlahBarang ?? 0) > 0): ?>
      <div class="adm-info-box" style="background:#FFFBEB;border-color:#FDE68A;margin-bottom:24px">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <div style="color:#92400E">
          <strong>Perhatian:</strong> Kategori ini saat ini memiliki <strong><?= (int)$jumlahBarang ?></strong> produk aktif. Perubahan nama kategori akan langsung memengaruhi seluruh produk terkait.
        </div>
      </div>
    <?php endif; ?>

    <!-- Tombol Aksi: Batal & Simpan -->
    <div class="edit-form-actions">
      <a class="edit-btn-batal" href="<?= e(url('/admin/kategori')) ?>">Batal</a>
      <button class="edit-btn-lanjut" type="submit">
        <span><?= $edit ? 'Simpan Perubahan' : 'Tambah Kategori' ?></span>
        <span class="edit-btn-panah">
          <svg viewBox="0 0 70 70" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 35 H55 M35 15 L55 35 L35 55"/>
          </svg>
        </span>
      </button>
    </div>
  </form>
</div>
