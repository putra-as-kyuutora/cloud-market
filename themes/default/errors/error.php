<?php /** Variabel: $kode (int), $pesan (string) */ $this->title = 'Error ' . $kode; ?>
<div class="center-box">
  <h1 class="big"><?= (int)$kode ?></h1>
  <p><?= e($pesan) ?></p>
  <a class="btn" href="<?= e(url('/')) ?>">Kembali ke Beranda</a>
</div>
