<?php
/**
 * CHECKOUT (Beli Barang)
 * Variabel: $barang, $pembeli [alamat, nama, noHp], $metode [namaMetode => ['butuh_bukti','instruksi']], $tersedia (bool), $errors, $old
 * FORM: POST /beli/{idBarang} | field: _csrf, jumlah, alamatPengiriman, metodePembayaran
 */
$this->title = 'Checkout';
$alamatAwal = old('alamatPengiriman', $pembeli['alamat'] ?? '');
?>
<h1>Checkout</h1>
<?php if (!$tersedia): ?>
  <div class="alert alert-bad">Barang sudah tidak tersedia.</div>
  <a class="btn" href="<?= e(url('/barang')) ?>">Cari barang lain</a>
<?php else: ?>
<form class="card form" method="post" action="<?= e(url('/beli/' . $barang['idBarang'])) ?>" novalidate>
  <?= csrf_field() ?>
  <div class="ringkas">
    <?php if ($barang['gambar']): ?><img class="thumb" src="<?= e(upload_url($barang['gambar'])) ?>" alt=""><?php endif; ?>
    <div>
      <strong><?= e($barang['namaBarang']) ?></strong><br>
      <span class="muted"><?= e($barang['namaToko']) ?> &middot; stok <?= (int)$barang['stok'] ?></span><br>
      <?php if (!empty($negoDiterima)): ?>
        <span class="harga" style="color:var(--ok);font-size:1.15rem;font-weight:800"><?= e(rupiah($hargaSatuan)) ?></span>
        <s class="muted" style="font-size:0.88rem;margin-left:0.4rem"><?= e(rupiah($barang['harga'])) ?></s>
        <span class="badge" style="background:var(--ok);color:#fff;font-size:0.75rem;padding:2px 8px;border-radius:999px;margin-left:0.35rem">Nego Disetujui</span>
      <?php else: ?>
        <span class="harga"><?= e(rupiah($barang['harga'])) ?></span>
      <?php endif; ?>
    </div>
  </div>

  <?php $this->partial('partials/field', ['name' => 'jumlah', 'label' => 'Jumlah', 'type' => 'number', 'value' => old('jumlah', 1), 'errors' => $errors, 'extra' => 'min="1" max="' . (int)$barang['stok'] . '"']); ?>

  <!-- ALAMAT PENGIRIMAN dengan popup modal -->
  <div class="field <?= isset($errors['alamatPengiriman']) ? 'has-err' : '' ?>">
    <label>Alamat Pengiriman <span style="color:var(--bad)">*</span></label>

    <!-- Control alamat — klik untuk buka modal -->
    <div class="address-field-wrap" data-address-target="alamatPengirimanInput">
      <div class="address-input-control" onclick="window.openModalAlamat('alamatPengirimanInput')">
        <div class="address-input-content">
          <?php if ($alamatAwal): ?>
            <div class="address-input-text"><?= e($alamatAwal) ?></div>
          <?php else: ?>
            <div class="address-input-placeholder">Pilih / isi alamat pengiriman pesanan Anda...</div>
          <?php endif; ?>
        </div>
        <button type="button" class="address-input-btn" aria-label="Atur Alamat">
          <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span><?= $alamatAwal ? 'Ubah' : 'Pilih Alamat' ?></span>
        </button>
      </div>
    </div>

    <!-- Hidden textarea yang dikirim ke server -->
    <textarea id="alamatPengirimanInput" name="alamatPengiriman" style="display:none"><?= e($alamatAwal) ?></textarea>

    <?php if (isset($errors['alamatPengiriman'])): ?>
      <div class="err"><?= e($errors['alamatPengiriman']) ?></div>
    <?php endif; ?>
  </div>

  <div class="field <?= isset($errors['metodePembayaran']) ? 'has-err' : '' ?>">
    <label>Metode pembayaran</label>
    <?php foreach ($metode as $nama => $cfg): ?>
      <label class="radio"><input type="radio" name="metodePembayaran" value="<?= e($nama) ?>" <?= old('metodePembayaran') === $nama ? 'checked' : '' ?>> <?= e($nama) ?></label>
    <?php endforeach; ?>
    <?php if (isset($errors['metodePembayaran'])): ?><div class="err"><?= e($errors['metodePembayaran']) ?></div><?php endif; ?>
  </div>
  <div class="row-btn">
    <a class="btn btn-ghost" href="<?= e(url('/barang/' . $barang['idBarang'])) ?>">Batal</a>
    <button class="btn" type="submit">Buat Pesanan</button>
  </div>
</form>
<?php endif; ?>
