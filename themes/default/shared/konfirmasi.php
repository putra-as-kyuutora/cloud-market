<?php
/** Dialog konfirmasi generik (logout, hapus barang, hapus kategori).
 *  Variabel: $judul, $pesan, $aksi (URL POST), $batal (URL), $labelYa, $bahaya (bool) */
$this->title = $judul;
?>
<div class="dialog">
  <h2><?= e($judul) ?></h2>
  <p><?= e($pesan) ?></p>
  <form method="post" action="<?= e($aksi) ?>" class="row-btn">
    <?= csrf_field() ?>
    <a class="btn btn-ghost" href="<?= e($batal) ?>">Batal</a>
    <button class="btn <?= $bahaya ? 'btn-danger' : '' ?>" type="submit"><?= e($labelYa) ?></button>
  </form>
</div>
