<?php
/**
 * FORM BUKA TOKO & VERIFIKASI IDENTITAS KTP
 * Variabel: $user, $sellerProfile, $errors, $old
 */
$this->title = 'Buka Toko - Verifikasi KTP';
?>

<div class="container" style="max-width: 760px; margin: 2rem auto 4rem;">
  <div class="seller-card" style="background:#fff; border-radius: 24px; padding: 2.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.06);">

    <div style="text-align: center; margin-bottom: 2rem;">
      <div style="width: 56px; height: 56px; background: rgba(63,105,255,0.1); color: #3F69FF; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
      </div>
      <h1 style="font-size: 1.85rem; font-weight: 800; color: #111; margin-bottom: 0.5rem;">Buka Toko &amp; Mulai Berjualan</h1>
      <p style="color: var(--muted); font-size: 0.95rem; max-width: 520px; margin: auto; line-height: 1.5;">
        Daftarkan toko Anda untuk menjangkau ribuan pembeli. Demi keamanan bersama, calon penjual diwajibkan melakukan verifikasi KTP.
      </p>
    </div>

    <?php if (!empty($sellerProfile) && $sellerProfile['statusVerifikasi'] === 'rejected'): ?>
      <div class="alert alert-bad" style="margin-bottom: 2rem; border-radius: 14px;">
        <strong>Pengajuan sebelumnya ditolak:</strong><br>
        <?= e($sellerProfile['alasanPenolakan'] ?: 'Foto KTP / data belum sesuai ketentuan.') ?><br>
        <span style="font-size: 0.88rem; opacity: 0.85;">Silakan perbaiki data dan unggah kembali berkas KTP Anda di bawah ini.</span>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('/penjual/daftar')) ?>" enctype="multipart/form-data" novalidate>
      <?= csrf_field() ?>

      <!-- BAGIAN 1: INFORMASI TOKO -->
      <div style="margin-bottom: 2.25rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #111; border-bottom: 2px solid #F1F1F1; padding-bottom: 0.65rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
          <span style="width: 24px; height: 24px; border-radius: 50%; background: #111; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">1</span>
          Profil Toko
        </h3>

        <?php $this->partial('partials/field', [
            'name'        => 'namaToko',
            'label'       => 'Nama Toko',
            'placeholder' => 'Contoh: Toko Elektronik Berkah',
            'value'       => old('namaToko', $sellerProfile['namaToko'] ?? ''),
            'errors'      => $errors,
            'extra'       => 'required'
        ]); ?>

        <?php $this->partial('partials/field', [
            'name'        => 'deskripsiToko',
            'label'       => 'Deskripsi Toko (Opsional)',
            'type'        => 'textarea',
            'placeholder' => 'Ceritakan tentang produk apa saja yang Anda jual dan komitmen layanan toko Anda...',
            'value'       => old('deskripsiToko', $sellerProfile['deskripsiToko'] ?? ''),
            'errors'      => $errors
        ]); ?>
      </div>

      <!-- BAGIAN 2: VERIFIKASI KTP (KYC) -->
      <div style="margin-bottom: 2.25rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #111; border-bottom: 2px solid #F1F1F1; padding-bottom: 0.65rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
          <span style="width: 24px; height: 24px; border-radius: 50%; background: #111; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">2</span>
          Verifikasi Identitas (KTP)
        </h3>

        <?php $this->partial('partials/field', [
            'name'        => 'noKtp',
            'label'       => 'Nomor Induk Kependudukan (NIK)',
            'placeholder' => '16 digit NIK sesuai e-KTP',
            'value'       => old('noKtp', $sellerProfile['noKtp'] ?? ''),
            'errors'      => $errors,
            'extra'       => 'maxlength="16" pattern="[0-9]{16}" required'
        ]); ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-top: 1rem;">
          <!-- Foto KTP -->
          <div class="field <?= isset($errors['fotoKtp']) ? 'has-err' : '' ?>">
            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 0.4rem; display: block;">Foto e-KTP Asli <span style="color:var(--bad)">*</span></label>
            <div style="border: 2px dashed rgba(0,0,0,0.15); border-radius: 14px; padding: 1.25rem; text-align: center; background: #FAFAFA;">
              <?php if (!empty($sellerProfile['fotoKtp'])): ?>
                <div style="margin-bottom: 0.75rem;">
                  <img src="<?= e(upload_url($sellerProfile['fotoKtp'])) ?>" alt="Foto KTP" style="max-height: 90px; border-radius: 8px;">
                </div>
              <?php endif; ?>
              <input type="file" name="fotoKtp" accept="image/jpeg,image/png,image/webp" style="font-size: 0.85rem; width: 100%;">
              <small style="color: var(--muted); display: block; margin-top: 0.35rem;">Pastikan foto KTP jelas &amp; tidak buram (JPG/PNG, max 2MB).</small>
            </div>
            <?php if (isset($errors['fotoKtp'])): ?><div class="err"><?= e($errors['fotoKtp']) ?></div><?php endif; ?>
          </div>

          <!-- Selfie KTP -->
          <div class="field <?= isset($errors['fotoSelfie']) ? 'has-err' : '' ?>">
            <label style="font-weight: 600; font-size: 0.9rem; margin-bottom: 0.4rem; display: block;">Foto Selfie Pegang KTP <span style="color:var(--bad)">*</span></label>
            <div style="border: 2px dashed rgba(0,0,0,0.15); border-radius: 14px; padding: 1.25rem; text-align: center; background: #FAFAFA;">
              <?php if (!empty($sellerProfile['fotoSelfie'])): ?>
                <div style="margin-bottom: 0.75rem;">
                  <img src="<?= e(upload_url($sellerProfile['fotoSelfie'])) ?>" alt="Foto Selfie" style="max-height: 90px; border-radius: 8px;">
                </div>
              <?php endif; ?>
              <input type="file" name="fotoSelfie" accept="image/jpeg,image/png,image/webp" style="font-size: 0.85rem; width: 100%;">
              <small style="color: var(--muted); display: block; margin-top: 0.35rem;">Pegang KTP di samping wajah, pastikan wajah &amp; KTP terlihat utuh.</small>
            </div>
            <?php if (isset($errors['fotoSelfie'])): ?><div class="err"><?= e($errors['fotoSelfie']) ?></div><?php endif; ?>
          </div>
        </div>
      </div>

      <!-- BAGIAN 3: REKENING PENCAIRAN -->
      <div style="margin-bottom: 2.5rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #111; border-bottom: 2px solid #F1F1F1; padding-bottom: 0.65rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
          <span style="width: 24px; height: 24px; border-radius: 50%; background: #111; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">3</span>
          Informasi Rekening Bank (Pencairan Saldo)
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <?php $this->partial('partials/field', [
              'name'        => 'namaBank',
              'label'       => 'Nama Bank',
              'placeholder' => 'BCA / Mandiri / BRI / BNI',
              'value'       => old('namaBank', $sellerProfile['namaBank'] ?? ''),
              'errors'      => $errors
          ]); ?>

          <?php $this->partial('partials/field', [
              'name'        => 'noRekening',
              'label'       => 'Nomor Rekening',
              'placeholder' => '1234567890',
              'value'       => old('noRekening', $sellerProfile['noRekening'] ?? ''),
              'errors'      => $errors
          ]); ?>
        </div>

        <?php $this->partial('partials/field', [
            'name'        => 'atasNamaRekening',
            'label'       => 'Nama Pemilik Rekening (Sesuai Buku Tabungan / KTP)',
            'placeholder' => 'Nama lengkap pemilik rekening',
            'value'       => old('atasNamaRekening', $sellerProfile['atasNamaRekening'] ?? $user['nama']),
            'errors'      => $errors
        ]); ?>
      </div>

      <div style="display: flex; gap: 1rem; align-items: center; justify-content: flex-end; border-top: 1px solid rgba(0,0,0,0.08); padding-top: 1.5rem;">
        <a href="<?= e(url('/')) ?>" class="btn btn-ghost">Batal</a>
        <button type="submit" class="btn" style="padding: 0.75rem 2rem; font-size: 1rem; font-weight: 700;">
          Kirim Pengajuan Toko &rarr;
        </button>
      </div>

    </form>
  </div>
</div>
