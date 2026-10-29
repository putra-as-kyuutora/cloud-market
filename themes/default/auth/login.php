<?php
/**
 * HALAMAN LOGIN (Unified Multi-Role)
 */
$this->title = 'Log In';
?>
<h1>Log in</h1>
<p class="sub">Masuk ke akun Cloud Market Anda</p>
<form method="post" action="<?= e(url('/login')) ?>" novalidate>
  <?= csrf_field() ?>

  <?php if (isset($errors['umum'])): ?>
    <div class="alert alert-bad"><?= e($errors['umum']) ?></div>
  <?php endif; ?>

  <?php $this->partial('partials/field', [
      'name' => 'username',
      'label' => 'Username atau Email',
      'placeholder' => 'Masukkan username atau email Anda',
      'extra' => 'autocomplete="username" required',
      'errors' => $errors
  ]); ?>

  <?php $this->partial('partials/field', [
      'name' => 'password',
      'label' => 'Password',
      'type' => 'password',
      'placeholder' => 'Masukkan password Anda',
      'extra' => 'autocomplete="current-password" required',
      'errors' => $errors
  ]); ?>

  <button class="btn btn-block" type="submit">Masuk &rarr;</button>
</form>
<p class="alt">Belum punya akun? <a href="<?= e(url('/registrasi')) ?>">Daftar Sekarang</a></p>
