<?php
/**
 * FORM EDIT PRODUK (Penjual) - Alab/edit-produk-setelah-klik.html
 * Variabel: $barang, $kategori, $aksi, $errors, $old
 */
$this->title = 'Edit Produk - Ubah Data';
$user = auth() ?? [];
$namaToko = $user['toko'] ?? ($user['nama'] ?? 'Toko Saya');
$inisialToko = mb_strtoupper(mb_substr($namaToko, 0, 1));
$v = fn($k) => old($k, $barang[$k] ?? '');
?>

<div class="edit-form-card">

      <form method="post" action="<?= e($aksi) ?>" enctype="multipart/form-data" novalidate id="formEditBarang">
        <?= csrf_field() ?>

        <!-- Judul -->
        <div class="edit-form-group <?= isset($errors['namaBarang']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="judul">Judul</label>
          <input class="edit-form-input" id="judul" name="namaBarang" type="text"
                 value="<?= e($v('namaBarang')) ?>" placeholder="Nama produk..." required>
          <?php if (isset($errors['namaBarang'])): ?>
            <div class="edit-form-err"><?= e($errors['namaBarang']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Deskripsi -->
        <div class="edit-form-group <?= isset($errors['deskripsi']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="deskripsi">Deskripsi</label>
          <textarea class="edit-form-textarea" id="deskripsi" name="deskripsi"
                    rows="4" placeholder="Deskripsikan kondisi barang secara detail..."><?= e($v('deskripsi')) ?></textarea>
          <?php if (isset($errors['deskripsi'])): ?>
            <div class="edit-form-err"><?= e($errors['deskripsi']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Detail - Kategori -->
        <div class="edit-form-group <?= isset($errors['idKategori']) ? 'has-err' : '' ?>">
          <div class="edit-form-lbl">Detail</div>
          <label class="edit-form-sublbl" for="kategori">Kategori</label>
          <select class="edit-form-input edit-form-select" id="kategori" name="idKategori">
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

        <!-- Harga -->
        <div class="edit-form-group <?= isset($errors['harga']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="harga">Harga</label>
          <input class="edit-form-input" id="harga" name="harga" type="number" step="1000" min="25000"
                 value="<?= e($v('harga')) ?>" placeholder="25000" required>
          <div class="edit-form-hint">Minimum rp.25.000</div>
          <?php if (isset($errors['harga'])): ?>
            <div class="edit-form-err"><?= e($errors['harga']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Stok & Foto -->
        <div class="edit-form-group <?= isset($errors['stok']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="stok">Stok Barang</label>
          <input class="edit-form-input" id="stok" name="stok" type="number" min="0"
                 value="<?= e($v('stok') !== '' ? $v('stok') : 1) ?>" required>
          <?php if (isset($errors['stok'])): ?>
            <div class="edit-form-err"><?= e($errors['stok']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Foto Produk -->
        <div class="edit-form-group <?= isset($errors['gambar']) ? 'has-err' : '' ?>">
          <label class="edit-form-lbl" for="gambar">Foto Produk</label>
          <div class="edit-foto-preview-wrap">
            <?php if (!empty($barang['gambar'])): ?>
              <img src="<?= e(upload_url($barang['gambar'])) ?>" alt="Foto saat ini" class="edit-cur-img" id="previewImg">
            <?php else: ?>
              <div class="edit-cur-img edit-no-img" id="previewImg"><span>Tanpa Foto</span></div>
            <?php endif; ?>
            <div class="edit-foto-upload-ctrl">
              <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp"
                     onchange="previewFoto(this)">
              <div class="edit-form-hint">Pilih foto baru jika ingin mengganti foto saat ini.</div>
            </div>
          </div>
          <?php if (isset($errors['gambar'])): ?>
            <div class="edit-form-err"><?= e($errors['gambar']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Tombol Aksi Sesuai Mockup Alab -->
        <div class="edit-form-actions">
          <a class="edit-btn-batal" href="<?= e(url('/penjual/barang/edit')) ?>">
            Batal
          </a>

          <button class="edit-btn-lanjut" type="submit">
            <span>Simpan Perubahan</span>
            <div class="edit-btn-panah">
              <svg viewBox="0 0 70 70" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="35" cy="35" r="30"/>
                <path d="M18 35 H50 M38 22 L51 35 L38 48"/>
              </svg>
            </div>
          </button>
        </div>

      </form>

    </div>

<script>
function previewFoto(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var prev = document.getElementById('previewImg');
      if (prev.tagName.toLowerCase() === 'img') {
        prev.src = e.target.result;
      } else {
        var newImg = document.createElement('img');
        newImg.src = e.target.result;
        newImg.className = 'edit-cur-img';
        newImg.id = 'previewImg';
        prev.parentNode.replaceChild(newImg, prev);
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
