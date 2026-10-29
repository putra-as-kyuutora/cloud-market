<?php
/**
 * LAYOUT UTAMA (tema default)
 * Variabel: $content (HTML halaman), $menu (array menu per peran), $user (array|null),
 *           $flashSuccess, $flashError, $this->title
 */
$appName = \App\Core\Config::get('app_name');
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($this->title ? $this->title . ' - ' : '') . e($appName) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/modal_alamat.css')) ?>">
</head>
<body class="<?= e($this->bodyClass) ?>">

<?php $this->partial('partials/_header', compact('menu', 'user', 'appName')); ?>

<main class="container page">
  <?php $this->partial('partials/flash', compact('flashSuccess', 'flashError')); ?>
  <?= $content ?>
</main>

<footer class="footer">
  <div class="container">
    <strong><?= e($appName) ?> Indonesia</strong> &mdash; Tempat untuk kebutuhan pembelian dan penjualan barang bekas Anda.
  </div>
</footer>

<?php $this->partial('partials/modal_alamat'); ?>
<script src="<?= e(asset('js/modal_alamat.js')) ?>"></script>
</body>
</html>

