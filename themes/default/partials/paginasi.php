<?php
/** Variabel: $hasil (page, pages), $filter (array query yang dipertahankan), $path */
if ($hasil['pages'] <= 1) return;
$q = fn($p) => url($path, array_merge($filter, ['page' => $p]));
?>
<nav class="paginasi">
  <?php if ($hasil['page'] > 1): ?><a class="btn btn-sm btn-ghost" href="<?= e($q($hasil['page'] - 1)) ?>">&laquo; Sebelumnya</a><?php endif; ?>
  <span>Halaman <?= (int)$hasil['page'] ?> dari <?= (int)$hasil['pages'] ?></span>
  <?php if ($hasil['page'] < $hasil['pages']): ?><a class="btn btn-sm" href="<?= e($q($hasil['page'] + 1)) ?>">Lihat Lebih Banyak &raquo;</a><?php endif; ?>
</nav>
