<?php
/**
 * FORM TAMBAH / EDIT BARANG (penjual) - Sesuai Alab Dashboard Penjualan
 * Variabel: $barang (null = tambah | array = edit), $kategori, $aksi (URL POST), $errors, $old
 */
$edit = $barang !== null;
$this->title = $edit ? 'Edit Barang' : 'Tambah Produk Baru';
$v = fn($k) => old($k, $edit ? $barang[$k] : '');
?>

<div class="edit-form-card">
      <div class="seller-card-head" style="margin-bottom: 2rem">
        <div>
          <h1 class="seller-card-title"><?= e($this->title) ?></h1>
          <p class="seller-card-sub">Isi detail informasi produk yang ingin Anda jual di Cloud Market</p>
        </div>
      </div>

      <form method="post" action="<?= e($aksi) ?>" enctype="multipart/form-data" novalidate id="formBarang">
        <?= csrf_field() ?>

        <div class="edit-form-group <?= isset($errors['namaBarang']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="namaBarang">Nama Produk</label>
          <input class="edit-form-input" id="namaBarang" name="namaBarang" type="text"
                 value="<?= e($v('namaBarang')) ?>" placeholder="Contoh: Kipas Angin Miyako, Sepatu Dr.Martens..." required>
          <?php if (isset($errors['namaBarang'])): ?>
            <div class="edit-form-err"><?= e($errors['namaBarang']) ?></div>
          <?php endif; ?>
        </div>

        <div class="edit-form-group <?= isset($errors['idKategori']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="idKategori">Kategori</label>
          <select class="edit-form-input edit-form-select" id="idKategori" name="idKategori">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategori as $k): ?>
              <option value="<?= (int)$k['idKategori'] ?>" <?= (int)$v('idKategori') === (int)$k['idKategori'] ? 'selected' : '' ?>>
                <?= e($k['namaKategori']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($errors['idKategori'])): ?>
            <div class="edit-form-err"><?= e($errors['idKategori']) ?></div>
          <?php endif; ?>
        </div>

        <div class="edit-form-group <?= isset($errors['harga']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="harga">Harga (Rp)</label>
          <input class="edit-form-input" id="harga" name="harga" type="number" step="1000" min="25000"
                 value="<?= e($edit && !isset($old['harga']) ? (int)$barang['harga'] : $v('harga')) ?>" placeholder="25000" required>
          <div class="edit-form-hint">Minimum Rp 25.000</div>
          <?php if (isset($errors['harga'])): ?>
            <div class="edit-form-err"><?= e($errors['harga']) ?></div>
          <?php endif; ?>
        </div>

        <div class="edit-form-group <?= isset($errors['stok']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="stok">Stok</label>
          <input class="edit-form-input" id="stok" name="stok" type="number" min="0"
                 value="<?= e($v('stok') !== '' ? $v('stok') : 1) ?>" required>
          <?php if (isset($errors['stok'])): ?>
            <div class="edit-form-err"><?= e($errors['stok']) ?></div>
          <?php endif; ?>
        </div>

        <div class="edit-form-group <?= isset($errors['deskripsi']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="deskripsi">Deskripsi & Kondisi Barang</label>
          <textarea class="edit-form-textarea" id="deskripsi" name="deskripsi" rows="4"
                    placeholder="Jelaskan kondisi barang, kelengkapan, minus pemakaian..."><?= e($v('deskripsi')) ?></textarea>
          <?php if (isset($errors['deskripsi'])): ?>
            <div class="edit-form-err"><?= e($errors['deskripsi']) ?></div>
          <?php endif; ?>
        </div>

        <div class="edit-form-group <?= isset($errors['gambar']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="gambar">Foto Produk</label>
          <div class="edit-foto-preview-wrap">
            <?php if ($edit && !empty($barang['gambar'])): ?>
              <img class="edit-cur-img" src="<?= e(upload_url($barang['gambar'])) ?>" alt="Foto Produk">
            <?php else: ?>
              <div class="edit-cur-img edit-no-img">No Image</div>
            <?php endif; ?>
            <div style="flex:1">
              <input class="edit-form-input" id="gambar" type="file" name="gambar" accept="image/jpeg,image/png,image/webp">
              <div class="edit-form-hint">Format file: JPG, PNG, atau WEBP. Maks 2MB.</div>
              <?php if (isset($errors['gambar'])): ?>
                <div class="edit-form-err"><?= e($errors['gambar']) ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Tombol Aksi Sesuai Alab -->
        <div class="edit-form-actions">
          <a class="edit-btn-batal" href="<?= e(url('/penjual/barang')) ?>">Batal</a>
          <button class="edit-btn-lanjut" type="submit">
            <span><?= $edit ? 'Simpan Perubahan' : 'Lanjutkan' ?></span>
            <span class="edit-btn-panah">
              <svg viewBox="0 0 70 70" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 35 H55 M35 15 L55 35 L35 55"/>
              </svg>
            </span>
          </button>
        </div>

      </form>
    </div>
