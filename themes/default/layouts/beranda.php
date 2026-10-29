<?php
/**
 * LAYOUT BERANDA - Full-frame design, tidak pakai topbar standar.
 * Variabel: $content, $flashSuccess, $flashError, $menu, $user
 */
$appName = \App\Core\Config::get('app_name');
?><?php echo '<!DOCTYPE html>'; ?>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($this->title ? $this->title . ' - ' : '') . e($appName) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;800;900&family=Onest:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/beranda.css')) ?>">
</head>
<body class="beranda-body">
  <?php $this->partial('partials/_header', compact('menu', 'user', 'appName')); ?>
  <?php $this->partial('partials/flash', compact('flashSuccess', 'flashError')); ?>
  <?= $content ?>
</body>
</html>

