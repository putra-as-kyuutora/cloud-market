<?php
/**
 * PARTIAL: MODAL ALAMAT & WILAYAH
 * Popup untuk melengkapi alamat secara rapi dengan pilihan wilayah bertingkat
 * dan auto-fill terpadu melalui field Kode Pos.
 */
?>
<!-- MODAL POPUP ALAMAT -->
<div id="modalAlamatBackdrop" class="modal-alamat-backdrop" aria-hidden="true" role="dialog" data-base-url="<?= e(url('/')) ?>">
  <div class="modal-alamat-dialog">
    <!-- Header -->
    <div class="modal-alamat-header">
      <div class="modal-alamat-title-wrap">
        <div class="modal-alamat-icon">
          <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        </div>
        <div>
          <h3 class="modal-alamat-title">Atur Alamat</h3>
          <p class="modal-alamat-sub">Lengkapi data wilayah dan detail alamat pengiriman Anda.</p>
        </div>
      </div>
      <button type="button" class="modal-alamat-close" data-close-modal aria-label="Tutup">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <!-- Body -->
    <div class="modal-alamat-body">
      <div class="modal-alamat-grid">

        <!-- Provinsi -->
        <div class="modal-field">
          <label for="modalProvinsi">Provinsi <span class="req">*</span></label>
          <select id="modalProvinsi">
            <option value="">— Pilih Provinsi —</option>
          </select>
        </div>

        <!-- Kota / Kabupaten -->
        <div class="modal-field">
          <label for="modalKabupaten">Kota / Kabupaten <span class="req">*</span></label>
          <select id="modalKabupaten" disabled>
            <option value="">— Pilih Kota / Kab —</option>
          </select>
        </div>

        <!-- Kecamatan -->
        <div class="modal-field">
          <label for="modalKecamatan">Kecamatan <span class="req">*</span></label>
          <select id="modalKecamatan" disabled>
            <option value="">— Pilih Kecamatan —</option>
          </select>
        </div>

        <!-- Kelurahan / Desa -->
        <div class="modal-field">
          <label for="modalDesa">Kelurahan / Desa</label>
          <select id="modalDesa" disabled>
            <option value="">— Pilih Kelurahan / Desa —</option>
          </select>
        </div>

        <!-- Kode Pos (terpadu: auto-fill saat diketik 5 digit, atau terisi otomatis dari kelurahan) -->
        <div class="modal-field modal-field-kodepos">
          <label for="modalKodePos">
            <span class="label-with-info">
              Kode Pos
              <span class="kodepos-info-container">
                <button type="button" id="kodeposInfoBtn" class="kodepos-info-btn" aria-label="Informasi Auto-Fill Kode Pos" title="Info auto-fill wilayah">
                  <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                  </svg>
                </button>
                <span id="kodeposTooltip" class="kodepos-tooltip" role="tooltip">
                  Ketik 5 digit kode pos untuk mengisi Provinsi, Kota/Kabupaten, Kecamatan, dan Kelurahan secara otomatis.
                </span>
              </span>
            </span>
            <span id="kodeposStatusMsg" class="kodepos-status-badge"></span>
          </label>
          <div class="kodepos-input-wrap">
            <input type="text" id="modalKodePos" placeholder="Contoh: 12150" maxlength="5" autocomplete="postal-code" inputmode="numeric">
          </div>
        </div>

        <!-- Catatan untuk Kurir (opsional) -->
        <div class="modal-field">
          <label for="modalCatatan">Catatan Kurir <span class="opt">(Opsional)</span></label>
          <input type="text" id="modalCatatan" placeholder="Contoh: Rumah pagar hitam, dekat masjid">
        </div>

        <!-- Alamat Jalan & Detail Rumah (Full Width) -->
        <div class="modal-field modal-col-full">
          <label for="modalJalan">Alamat Jalan &amp; Detail Rumah <span class="req">*</span></label>
          <textarea id="modalJalan" rows="3" placeholder="Nama jalan, nomor bangunan, RT/RW, blok, atau patokan lokasi"></textarea>
        </div>

      </div>
    </div>

    <!-- Footer -->
    <div class="modal-alamat-footer">
      <button type="button" class="modal-btn-batal" data-close-modal>Batal</button>
      <button type="button" id="modalBtnSimpan" class="modal-btn-simpan">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        Gunakan Alamat Ini
      </button>
    </div>
  </div>
</div>
