<?php
/**
 * DETAIL PENINJAUAN VERIFIKASI KTP PENJUAL (Admin)
 * Variabel: $p (array data profil penjual & user)
 */
$this->title = 'Tinjau Berkas: ' . $p['namaToko'];
?>

<div style="margin-bottom: 1.5rem;">
  <a href="<?= e(url('/admin/verifikasi-penjual')) ?>" style="color:var(--pnj-muted); text-decoration:none; font-weight:600; font-size:0.88rem; display:inline-flex; align-items:center; gap:0.35rem;">
    &larr; Kembali ke Daftar Verifikasi
  </a>
</div>

<div class="adm-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
  <div>
    <h1 style="font-size: 1.65rem; font-weight: 800; color: #111; margin:0;">
      Tinjau Pengajuan Toko: <?= e($p['namaToko']) ?>
    </h1>
    <p style="color: var(--pnj-muted); font-size: 0.9rem; margin-top: 0.25rem;">
      Diajukan oleh <strong><?= e($p['nama']) ?></strong> (@<?= e($p['username']) ?>) &middot; <?= e(date('d M Y, H:i', strtotime($p['created_at']))) ?>
    </p>
  </div>
  <div>
    <?php if ($p['statusVerifikasi'] === 'approved'): ?>
      <span class="badge" style="background:#d1fae5; color:#065f46; font-size:0.85rem; padding:6px 14px; border-radius:999px; font-weight:700;">
        STATUS: SUDAH DISETUJUI
      </span>
    <?php elseif ($p['statusVerifikasi'] === 'rejected'): ?>
      <span class="badge" style="background:#fee2e2; color:#991b1b; font-size:0.85rem; padding:6px 14px; border-radius:999px; font-weight:700;">
        STATUS: DITOLAK
      </span>
    <?php else: ?>
      <span class="badge" style="background:#fef3c7; color:#92400e; font-size:0.85rem; padding:6px 14px; border-radius:999px; font-weight:700;">
        STATUS: MENUNGGU TINJAUAN
      </span>
    <?php endif; ?>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.75rem; align-items:start;">

  <!-- KOLOM KIRI: DOKUMEN FOTO KTP & SELFIE -->
  <div>
    <div class="seller-card" style="background:#fff; border-radius:18px; padding:1.75rem; border:1px solid rgba(0,0,0,0.08); margin-bottom:1.5rem;">
      <h3 style="font-size:1.15rem; font-weight:700; color:#111; margin-bottom:1.25rem;">Berkas Identitas KTP</h3>

      <div style="margin-bottom: 1.5rem;">
        <span style="font-size:0.85rem; color:var(--pnj-muted); text-transform:uppercase; letter-spacing:0.04em; font-weight:700;">Nomor Induk Kependudukan (NIK):</span>
        <div style="font-size:1.35rem; font-weight:800; color:#111; letter-spacing:0.05em; margin-top:0.25rem;">
          <code><?= e($p['noKtp'] ?: 'Belum diisi') ?></code>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Foto KTP -->
        <div>
          <label style="font-weight:700; font-size:0.9rem; margin-bottom:0.5rem; display:block;">Foto e-KTP Depan</label>
          <div style="background:#FAFAFA; border:1px solid rgba(0,0,0,0.1); border-radius:12px; overflow:hidden; text-align:center;">
            <?php if (!empty($p['fotoKtp'])): ?>
              <a href="<?= e(upload_url($p['fotoKtp'])) ?>" target="_blank" title="Klik untuk memperbesar">
                <img src="<?= e(upload_url($p['fotoKtp'])) ?>" alt="Foto KTP" style="width:100%; height:200px; object-fit:cover; display:block;">
              </a>
              <div style="padding:0.4rem; font-size:0.78rem; color:var(--pnj-muted);">Klik gambar untuk perbesar ↗</div>
            <?php else: ?>
              <div style="height:200px; display:flex; align-items:center; justify-content:center; color:var(--pnj-muted);">
                Foto KTP tidak tersedia
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Foto Selfie -->
        <div>
          <label style="font-weight:700; font-size:0.9rem; margin-bottom:0.5rem; display:block;">Foto Selfie Pegang KTP</label>
          <div style="background:#FAFAFA; border:1px solid rgba(0,0,0,0.1); border-radius:12px; overflow:hidden; text-align:center;">
            <?php if (!empty($p['fotoSelfie'])): ?>
              <a href="<?= e(upload_url($p['fotoSelfie'])) ?>" target="_blank" title="Klik untuk memperbesar">
                <img src="<?= e(upload_url($p['fotoSelfie'])) ?>" alt="Foto Selfie KTP" style="width:100%; height:200px; object-fit:cover; display:block;">
              </a>
              <div style="padding:0.4rem; font-size:0.78rem; color:var(--pnj-muted);">Klik gambar untuk perbesar ↗</div>
            <?php else: ?>
              <div style="height:200px; display:flex; align-items:center; justify-content:center; color:var(--pnj-muted);">
                Foto Selfie tidak tersedia
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Toko & Rekening -->
    <div class="seller-card" style="background:#fff; border-radius:18px; padding:1.75rem; border:1px solid rgba(0,0,0,0.08);">
      <h3 style="font-size:1.15rem; font-weight:700; color:#111; margin-bottom:1.25rem;">Informasi Toko &amp; Rekening Pencairan</h3>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <span style="font-size:0.85rem; color:var(--pnj-muted);">Nama Toko:</span>
          <div style="font-weight:700; font-size:1.05rem;"><?= e($p['namaToko']) ?></div>
        </div>
        <div>
          <span style="font-size:0.85rem; color:var(--pnj-muted);">Nama Bank:</span>
          <div style="font-weight:700; font-size:1.05rem;"><?= e($p['namaBank'] ?: '-') ?></div>
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <span style="font-size:0.85rem; color:var(--pnj-muted);">No. Rekening:</span>
          <div style="font-weight:700; font-size:1.05rem;"><?= e($p['noRekening'] ?: '-') ?></div>
        </div>
        <div>
          <span style="font-size:0.85rem; color:var(--pnj-muted);">Atas Nama Rekening:</span>
          <div style="font-weight:700; font-size:1.05rem;"><?= e($p['atasNamaRekening'] ?: '-') ?></div>
        </div>
      </div>

      <div>
        <span style="font-size:0.85rem; color:var(--pnj-muted);">Deskripsi Toko:</span>
        <div style="font-size:0.95rem; line-height:1.5; color:#333; margin-top:0.25rem; background:#FAFAFA; padding:0.85rem; border-radius:8px;">
          <?= nl2br(e($p['deskripsiToko'] ?: 'Tidak ada deskripsi.')) ?>
        </div>
      </div>
    </div>
  </div>

  <!-- KOLOM KANAN: KEPUTUSAN ADMIN & DATA USER -->
  <div>
    <!-- Panel Aksi Admin -->
    <div class="seller-card" style="background:#fff; border-radius:18px; padding:1.75rem; border:1px solid rgba(0,0,0,0.08); margin-bottom:1.5rem;">
      <h3 style="font-size:1.15rem; font-weight:700; color:#111; margin-bottom:1.25rem;">Tindakan Verifikasi</h3>

      <?php if ($p['statusVerifikasi'] !== 'approved'): ?>
        <form method="post" action="<?= e(url('/admin/verifikasi-penjual/' . $p['idUser'] . '/setujui')) ?>" style="margin-bottom:1rem;" onsubmit="return confirm('Apakah Anda yakin data KTP sudah sesuai dan menyetujui toko ini?');">
          <?= csrf_field() ?>
          <button type="submit" class="btn" style="width:100%; background:#10B981; color:#fff; padding:0.75rem; font-weight:700; border-radius:10px;">
            ✓ Setujui Verifikasi Toko
          </button>
        </form>
      <?php endif; ?>

      <?php if ($p['statusVerifikasi'] !== 'rejected'): ?>
        <button type="button" class="btn btn-ghost" style="width:100%; color:#EF4444; border:1px solid #FED7D7; background:#FFF5F5; font-weight:700; border-radius:10px;"
                onclick="document.getElementById('formTolakBox').style.display='block'; this.style.display='none';">
          ✕ Tolak Pengajuan
        </button>

        <div id="formTolakBox" style="display:none; margin-top:1rem; border-top:1px solid rgba(0,0,0,0.08); padding-top:1rem;">
          <form method="post" action="<?= e(url('/admin/verifikasi-penjual/' . $p['idUser'] . '/tolak')) ?>" onsubmit="return confirm('Tolak pengajuan toko ini?');">
            <?= csrf_field() ?>
            <label style="font-weight:700; font-size:0.85rem; color:#C53030; display:block; margin-bottom:0.35rem;">Alasan Penolakan:</label>
            <textarea name="alasanPenolakan" rows="3" style="width:100%; border:1px solid rgba(0,0,0,0.15); border-radius:8px; padding:0.5rem; font-size:0.88rem; margin-bottom:0.75rem;" placeholder="Jelaskan alasan penolakan agar pemohon dapat memperbaikinya..."></textarea>
            <button type="submit" class="btn" style="width:100%; background:#EF4444; color:#fff; font-weight:700; padding:0.6rem; border-radius:8px;">
              Konfirmasi Tolak
            </button>
          </form>
        </div>
      <?php endif; ?>

      <?php if (!empty($p['alasanPenolakan'])): ?>
        <div style="margin-top:1rem; background:#FFF5F5; border:1px solid #FED7D7; border-radius:8px; padding:0.75rem; font-size:0.85rem; color:#9B2C2C;">
          <strong>Alasan Penolakan:</strong><br>
          <?= nl2br(e($p['alasanPenolakan'])) ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Data Pemohon -->
    <div class="seller-card" style="background:#fff; border-radius:18px; padding:1.5rem; border:1px solid rgba(0,0,0,0.08); font-size:0.88rem;">
      <h4 style="font-weight:700; margin-bottom:0.85rem; border-bottom:1px solid #f1f1f1; padding-bottom:0.5rem;">Data Akun Pemohon</h4>
      <div style="margin-bottom:0.5rem;"><span style="color:var(--pnj-muted)">Nama Lengkap:</span><br><strong><?= e($p['nama']) ?></strong></div>
      <div style="margin-bottom:0.5rem;"><span style="color:var(--pnj-muted)">Username:</span><br><code>@<?= e($p['username']) ?></code></div>
      <div style="margin-bottom:0.5rem;"><span style="color:var(--pnj-muted)">Email:</span><br><?= e($p['email'] ?: '-') ?></div>
      <div style="margin-bottom:0.5rem;"><span style="color:var(--pnj-muted)">Nomor HP / WA:</span><br><?= e($p['noHp'] ?: '-') ?></div>
      <div><span style="color:var(--pnj-muted)">Alamat Tempat Tinggal:</span><br><?= nl2br(e($p['alamat'] ?: '-')) ?></div>
    </div>
  </div>

</div>
