<?php
/**
 * HALAMAN REGISTRASI PENGGUNA BARU
 * Pengguna otomatis menjadi Pembeli dan dapat mengajukan Buka Toko kapan saja.
 */
$this->title = 'Daftar Akun Baru';
$alamatLama = old('alamat', '');
?>
<h1>Registrasi</h1>
<p class="sub">Buat akun untuk mulai berbelanja &amp; berjualan</p>
<form method="post" action="<?= e(url('/registrasi')) ?>" novalidate>
  <?= csrf_field() ?>

  <?php if (isset($errors['umum'])): ?>
    <div class="alert alert-bad"><?= e($errors['umum']) ?></div>
  <?php endif; ?>

  <?php $this->partial('partials/field', [
      'name' => 'nama',
      'label' => 'Nama Lengkap',
      'placeholder' => 'Contoh: Budi Santoso',
      'errors' => $errors
  ]); ?>

  <?php $this->partial('partials/field', [
      'name' => 'username',
      'label' => 'Username',
      'placeholder' => 'Pilih username unik (min 4 karakter)',
      'errors' => $errors,
      'extra' => 'autocomplete="username"'
  ]); ?>

  <?php $this->partial('partials/field', [
      'name' => 'email',
      'label' => 'Alamat Email (Opsional)',
      'type' => 'email',
      'placeholder' => 'nama@contoh.com',
      'errors' => $errors,
      'extra' => 'autocomplete="email"'
  ]); ?>

  <?php $this->partial('partials/field', [
      'name' => 'noHp',
      'label' => 'Nomor HP / WhatsApp',
      'type' => 'tel',
      'placeholder' => '081234567890',
      'errors' => $errors
  ]); ?>

  <!-- ALAMAT dengan popup modal -->
  <div class="field <?= isset($errors['alamat']) ? 'has-err' : '' ?>">
    <label>Alamat Lengkap <span style="font-size:0.82rem;color:var(--muted);font-weight:400">(Opsional)</span></label>

    <div class="address-field-wrap" data-address-target="alamatRegInput">
      <div class="address-input-control" onclick="window.openModalAlamat('alamatRegInput')">
        <div class="address-input-content">
          <?php if ($alamatLama): ?>
            <div class="address-input-text"><?= e($alamatLama) ?></div>
          <?php else: ?>
            <div class="address-input-placeholder">Pilih / isi alamat tempat tinggal Anda...</div>
          <?php endif; ?>
        </div>
        <button type="button" class="address-input-btn" aria-label="Atur Alamat">
          <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span><?= $alamatLama ? 'Ubah' : 'Pilih Alamat' ?></span>
        </button>
      </div>
    </div>

    <!-- Hidden textarea -->
    <textarea id="alamatRegInput" name="alamat" style="display:none"><?= e($alamatLama) ?></textarea>
    <?php if (isset($errors['alamat'])): ?><div class="err"><?= e($errors['alamat']) ?></div><?php endif; ?>
  </div>

  <?php $this->partial('partials/field', [
      'name' => 'password',
      'label' => 'Password',
      'type' => 'password',
      'placeholder' => 'Minimal 6 karakter',
      'errors' => $errors,
      'extra' => 'autocomplete="new-password"'
  ]); ?>

  <button class="btn btn-block" type="submit">Daftar Sekarang &rarr;</button>
</form>
<p class="alt">Sudah punya akun? <a href="<?= e(url('/login')) ?>">Log In</a></p>
