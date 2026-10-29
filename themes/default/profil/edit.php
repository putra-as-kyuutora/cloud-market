<?php
/**
 * KELOLA PROFIL (semua peran)
 * Variabel: $profil (baris akun: nama, username, [alamat, noHp, namaToko, deskripsiToko untuk penjual/pembeli]), $errors, $old
 * FORM: POST /profil | field: _csrf, nama, alamat, noHp, (penjual) namaToko, deskripsiToko, password_baru (opsional)
 */
$this->title = 'Profil';
$p = peran();
$v = fn($k) => old($k, $profil[$k] ?? '');
$alamatSaatIni = $v('alamat');
?>
<h1>Profil Saya</h1>
<form class="card form" method="post" action="<?= e(url('/profil')) ?>" novalidate>
  <?= csrf_field() ?>
  <div class="field"><label>Username</label><input value="<?= e($profil['username'] ?? '') ?>" disabled></div>
  <?php $this->partial('partials/field', ['name' => 'nama', 'label' => 'Nama', 'value' => $v('nama'), 'errors' => $errors]); ?>
  <?php if ($p !== 'admin'): ?>
    <?php $this->partial('partials/field', ['name' => 'noHp', 'label' => 'Nomor HP', 'value' => $v('noHp'), 'errors' => $errors]); ?>

    <!-- ALAMAT dengan popup modal -->
    <div class="field <?= isset($errors['alamat']) ? 'has-err' : '' ?>">
      <label>Alamat Lengkap <span style="color:var(--bad)">*</span></label>

      <div class="address-field-wrap" data-address-target="alamatInput">
        <div class="address-input-control" onclick="window.openModalAlamat('alamatInput')">
          <div class="address-input-content">
            <?php if ($alamatSaatIni): ?>
              <div class="address-input-text"><?= e($alamatSaatIni) ?></div>
            <?php else: ?>
              <div class="address-input-placeholder">Pilih / isi alamat tempat tinggal Anda...</div>
            <?php endif; ?>
          </div>
          <button type="button" class="address-input-btn" aria-label="Atur Alamat">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <span><?= $alamatSaatIni ? 'Ubah' : 'Pilih Alamat' ?></span>
          </button>
        </div>
      </div>

      <!-- Hidden textarea -->
      <textarea id="alamatInput" name="alamat" style="display:none"><?= e($alamatSaatIni) ?></textarea>
      <?php if (isset($errors['alamat'])): ?><div class="err"><?= e($errors['alamat']) ?></div><?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if ($p === 'penjual'): ?>
    <?php $this->partial('partials/field', ['name' => 'namaToko', 'label' => 'Nama toko', 'value' => $v('namaToko'), 'errors' => $errors]); ?>
    <?php $this->partial('partials/field', ['name' => 'deskripsiToko', 'label' => 'Deskripsi toko', 'type' => 'textarea', 'value' => $v('deskripsiToko'), 'errors' => $errors]); ?>
  <?php endif; ?>
  <?php $this->partial('partials/field', ['name' => 'password_baru', 'label' => 'Password baru (kosongkan jika tidak diganti)', 'type' => 'password', 'errors' => $errors, 'extra' => 'autocomplete="new-password"']); ?>
  <button class="btn" type="submit">Simpan Perubahan</button>
</form>
