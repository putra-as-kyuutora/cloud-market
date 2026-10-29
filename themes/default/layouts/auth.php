<?php
/** LAYOUT HALAMAN LOGIN/REGISTRASI. Variabel: $content, $flashSuccess, $flashError */
$appName = \App\Core\Config::get('app_name');
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($this->title ?: 'Masuk') ?> - <?= e($appName) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/modal_alamat.css')) ?>">
</head>
<body class="auth-body">
  <div class="auth-wrap">
    <a class="auth-brand" href="<?= e(url('/')) ?>">
      <svg viewBox="0 0 198 130" width="86" height="56" fill="none" stroke="#fff" stroke-width="9" stroke-linecap="round" stroke-linejoin="round"><path d="M50 108h92a32 32 0 0 0 6-63A44 44 0 0 0 64 40a34 34 0 0 0-14 68z"/></svg>
      <span><?= e($appName) ?></span>
    </a>
    <div class="auth-card">
      <?php $this->partial('partials/flash', compact('flashSuccess', 'flashError')); ?>
      <?= $content ?>
    </div>
  </div>
</body>
<?php $this->partial('partials/modal_alamat'); ?>
<script src="<?= e(asset('js/modal_alamat.js')) ?>"></script>
</html>
