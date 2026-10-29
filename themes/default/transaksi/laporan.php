<?php
/**
 * LAPORAN TRANSAKSI - Tampilan Terpadu (Admin & Penjual).
 * Variabel: $filter, $rows, $totalPendapatan, $semuaStatus, $daftarToko, $adalahAdmin
 */
$this->title = 'Laporan Transaksi';
?>

<div class="seller-card">
  <div class="seller-card-head">
    <div>
      <h1 class="seller-card-title">Laporan Transaksi</h1>
      <p class="seller-card-sub">
        <?= $adalahAdmin ? 'Rekapitulasi riwayat pesanan dan total perputaran transaksi platform Cloud Market' : 'Rekapitulasi riwayat pesanan dan total pendapatan toko Anda' ?>
      </p>
    </div>
  </div>

  <!-- Form Filter Laporan -->
  <form class="seller-laporan-filter" method="get" action="<?= e(url('/laporan')) ?>">
    <div class="filter-input-wrap">
      <input type="search" name="q" placeholder="Cari transaksi, pembeli, produk..." value="<?= e($filter['q'] ?? '') ?>">
    </div>

    <!-- Filter Rentang Tanggal -->
    <div class="filter-date-wrap">
      <input type="date" name="dari" value="<?= e($filter['dari'] ?? '') ?>" title="Dari tanggal">
      <span>s/d</span>
      <input type="date" name="sampai" value="<?= e($filter['sampai'] ?? '') ?>" title="Sampai tanggal">
    </div>

    <!-- Filter Toko (Khusus Admin) -->
    <?php if ($adalahAdmin && !empty($daftarToko)): ?>
      <select name="toko">
        <option value="">Semua Toko</option>
        <?php foreach ($daftarToko as $tk): ?>
          <option value="<?= (int)$tk['idPenjual'] ?>" <?= (int)($filter['toko'] ?? 0) === (int)$tk['idPenjual'] ? 'selected' : '' ?>>
            <?= e($tk['namaToko'] ?: $tk['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    <?php endif; ?>

    <!-- Filter Status Pembayaran -->
    <select name="status">
      <option value="">Semua Status</option>
      <?php foreach ($semuaStatus as $s): ?>
        <option value="<?= e($s) ?>" <?= ($filter['status'] ?? '') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>

    <button class="btn btn-sm" type="submit">Filter</button>
    <?php if (!empty($filter['q']) || !empty($filter['dari']) || !empty($filter['sampai']) || !empty($filter['status']) || !empty($filter['toko'])): ?>
      <a href="<?= e(url('/laporan')) ?>" class="btn btn-sm btn-ghost">Reset</a>
    <?php endif; ?>
  </form>
  
  <?php if (empty($rows)): ?>
    <div class="seller-kosong">
      <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35">
        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
      </svg>
      <p>Tidak ada transaksi yang cocok dengan filter yang dipilih.</p>
      <?php if (!empty($filter['q']) || !empty($filter['status'])): ?>
        <a href="<?= e(url('/laporan')) ?>" class="btn btn-sm btn-ghost">Tampilkan Semua</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="table-wrap seller-table-wrap">
      <table class="seller-table">
        <thead>
          <tr>
            <th style="width:105px">ID Transaksi</th>
            <th>Pembeli</th>
            <th>Produk</th>
            <?php if ($adalahAdmin): ?>
              <th>Toko / Penjual</th>
            <?php endif; ?>
            <th>Total Harga</th>
            <th>Tanggal</th>
            <th>Metode</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td>
                <strong style="font-family:monospace;font-size:0.95rem">#CM-<?= str_pad((string)$r['idTransaksi'], 3, '0', STR_PAD_LEFT) ?></strong>
              </td>
              <td><strong><?= e($r['namaPembeli']) ?></strong></td>
              <td><?= e($r['barang']) ?></td>
              <?php if ($adalahAdmin): ?>
                <td>
                  <span class="seller-badge-kat" style="color:var(--pnj-text);background:#F3F4F6">
                    <?= e($r['namaToko'] ?? ($r['namaPenjual'] ?? 'Toko')) ?>
                  </span>
                </td>
              <?php endif; ?>
              <td><strong class="seller-prod-price"><?= e(rupiah($r['total'])) ?></strong></td>
              <td><?= e(tanggal($r['tanggal'])) ?></td>
              <td><span class="seller-badge-metode"><?= e($r['metodePembayaran']) ?></span></td>
              <td><?php $this->partial('partials/status_badge', ['s' => $r['statusPembayaran']]); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?= $adalahAdmin ? '4' : '3' ?>">
              <strong>Total Pendapatan Terverifikasi (Sudah Dibayar)</strong>
            </td>
            <td colspan="4">
              <strong style="color:var(--ok);font-size:1.15rem"><?= e(rupiah($totalPendapatan)) ?></strong>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  <?php endif; ?>
</div>
