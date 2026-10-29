/**
 * CLOUD MARKET - Modal Alamat & Auto-Fill Terpadu (Vanilla JS)
 * - Cascading dropdowns: Provinsi -> Kab/Kota -> Kecamatan -> Kelurahan
 * - Auto-fill terpadu via field Kode Pos (saat 5 digit diketik)
 * - Auto-set Kode Pos saat Kelurahan dipilih
 */
(function () {
  'use strict';

  let activeTargetInputId = null;
  let provincesLoaded = false;

  const backdrop = document.getElementById('modalAlamatBackdrop');
  if (!backdrop) return;

  const closeBtns    = backdrop.querySelectorAll('[data-close-modal]');
  const selProvinsi  = document.getElementById('modalProvinsi');
  const selKabupaten = document.getElementById('modalKabupaten');
  const selKecamatan = document.getElementById('modalKecamatan');
  const selDesa      = document.getElementById('modalDesa');
  const inputKodepos = document.getElementById('modalKodePos');
  const kodeposBadge = document.getElementById('kodeposStatusMsg');
  const txtJalan     = document.getElementById('modalJalan');
  const txtCatatan   = document.getElementById('modalCatatan');
  const btnSimpan    = document.getElementById('modalBtnSimpan');

  // Base URL dari attribute data-base-url modal
  const rawBaseUrl = backdrop.dataset.baseUrl || '';
  const baseUrl = rawBaseUrl.replace(/\/$/, '');

  function apiUrl(endpoint) {
    return baseUrl + endpoint;
  }

  // ---- Open / Close ----
  window.openModalAlamat = function (targetId) {
    activeTargetInputId = targetId;
    backdrop.classList.add('active');
    backdrop.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    if (!provincesLoaded) {
      loadProvinces();
    }
  };

  const kodeposInfoBtn = document.getElementById('kodeposInfoBtn');
  const kodeposTooltip = document.getElementById('kodeposTooltip');

  if (kodeposInfoBtn && kodeposTooltip) {
    kodeposInfoBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      const isShown = kodeposTooltip.classList.toggle('show');
      kodeposInfoBtn.classList.toggle('active', isShown);
    });

    document.addEventListener('click', function (e) {
      if (!kodeposInfoBtn.contains(e.target) && !kodeposTooltip.contains(e.target)) {
        kodeposTooltip.classList.remove('show');
        kodeposInfoBtn.classList.remove('active');
      }
    });
  }

  window.closeModalAlamat = function () {
    backdrop.classList.remove('active');
    backdrop.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (kodeposTooltip) {
      kodeposTooltip.classList.remove('show');
      if (kodeposInfoBtn) kodeposInfoBtn.classList.remove('active');
    }
  };

  closeBtns.forEach(b => b.addEventListener('click', window.closeModalAlamat));
  backdrop.addEventListener('click', e => { if (e.target === backdrop) window.closeModalAlamat(); });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && backdrop.classList.contains('active')) {
      window.closeModalAlamat();
    }
  });

  // ---- Status Helper ----
  function setStatus(msg, type) {
    if (!kodeposBadge) return;
    kodeposBadge.textContent = msg;
    kodeposBadge.className = 'kodepos-status-badge ' + (type || '');
  }

  function clearStatus() {
    if (!kodeposBadge) return;
    kodeposBadge.textContent = '';
    kodeposBadge.className = 'kodepos-status-badge';
  }

  // ---- Dropdown Loaders ----
  function loadProvinces(selectVal) {
    selProvinsi.innerHTML = '<option value="">— Memuat provinsi… —</option>';
    selProvinsi.disabled = true;
    return fetch(apiUrl('/api/wilayah/provinsi'))
      .then(r => r.json())
      .then(res => {
        selProvinsi.innerHTML = '<option value="">— Pilih Provinsi —</option>';
        if (res.sukses) {
          res.data.forEach(p => selProvinsi.appendChild(new Option(p, p)));
        }
        provincesLoaded = true;
        selProvinsi.disabled = false;
        if (selectVal) selProvinsi.value = selectVal;
      })
      .catch(() => {
        selProvinsi.innerHTML = '<option value="">Gagal memuat provinsi</option>';
      });
  }

  function loadKabupaten(provinsi, selectVal) {
    selKabupaten.innerHTML = '<option value="">— Pilih Kota / Kab —</option>';
    selKabupaten.disabled = true;
    resetKecamatan();
    if (!provinsi) return Promise.resolve();

    return fetch(apiUrl('/api/wilayah/kabupaten?provinsi=' + encodeURIComponent(provinsi)))
      .then(r => r.json())
      .then(res => {
        if (res.sukses) {
          res.data.forEach(k => selKabupaten.appendChild(new Option(k, k)));
        }
        selKabupaten.disabled = false;
        if (selectVal) selKabupaten.value = selectVal;
      });
  }

  function loadKecamatan(kabupaten, selectVal) {
    selKecamatan.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
    selKecamatan.disabled = true;
    resetDesa();
    if (!kabupaten) return Promise.resolve();

    return fetch(apiUrl('/api/wilayah/kecamatan?kabupaten=' + encodeURIComponent(kabupaten)))
      .then(r => r.json())
      .then(res => {
        if (res.sukses) {
          res.data.forEach(k => selKecamatan.appendChild(new Option(k, k)));
        }
        selKecamatan.disabled = false;
        if (selectVal) selKecamatan.value = selectVal;
      });
  }

  function loadDesa(kecamatan, kabupaten, selectVal) {
    selDesa.innerHTML = '<option value="">— Pilih Kelurahan / Desa —</option>';
    selDesa.disabled = true;
    if (!kecamatan) return Promise.resolve();

    let url = apiUrl('/api/wilayah/desa?kecamatan=' + encodeURIComponent(kecamatan));
    if (kabupaten) url += '&kabupaten=' + encodeURIComponent(kabupaten);

    return fetch(url)
      .then(r => r.json())
      .then(res => {
        if (res.sukses) {
          res.data.forEach(d => {
            const opt = new Option(d.desa + (d.kode_pos ? ' (' + d.kode_pos + ')' : ''), d.desa);
            opt.dataset.kp = d.kode_pos;
            selDesa.appendChild(opt);
          });
        }
        selDesa.disabled = false;
        if (selectVal) selDesa.value = selectVal;
      });
  }

  function resetKecamatan() {
    selKecamatan.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
    selKecamatan.disabled = true;
    resetDesa();
  }

  function resetDesa() {
    selDesa.innerHTML = '<option value="">— Pilih Kelurahan / Desa —</option>';
    selDesa.disabled = true;
  }

  // ---- Cascading Change Listeners ----
  selProvinsi.addEventListener('change', function () {
    loadKabupaten(this.value);
  });

  selKabupaten.addEventListener('change', function () {
    loadKecamatan(this.value);
  });

  selKecamatan.addEventListener('change', function () {
    loadDesa(this.value, selKabupaten.value);
  });

  // Saat kelurahan dipilih -> isi field Kode Pos otomatis
  selDesa.addEventListener('change', function () {
    const opt = selDesa.options[selDesa.selectedIndex];
    if (opt && opt.dataset.kp) {
      inputKodepos.value = opt.dataset.kp;
      clearStatus();
    }
  });

  // ---- Kode Pos Auto-Fill (saat user mengetik 5 digit angka) ----
  let kodeposTimer = null;

  inputKodepos.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 5);
    clearTimeout(kodeposTimer);

    if (this.value.length < 5) {
      clearStatus();
      return;
    }

    setStatus('Mencari…', 'loading');
    kodeposTimer = setTimeout(() => autoFillKodePos(this.value), 350);
  });

  async function autoFillKodePos(kode) {
    try {
      const res = await fetch(apiUrl('/api/wilayah/kodepos?kode=' + encodeURIComponent(kode))).then(r => r.json());
      if (!res.sukses) {
        setStatus('Tidak ditemukan', 'err');
        return;
      }

      setStatus('✓ ' + res.kecamatan + ', ' + res.kabupaten, 'ok');

      if (!provincesLoaded) {
        await loadProvinces(res.provinsi);
      } else {
        selProvinsi.value = res.provinsi;
      }

      await loadKabupaten(res.provinsi, res.kabupaten);
      await loadKecamatan(res.kabupaten, res.kecamatan);
      await loadDesa(res.kecamatan, res.kabupaten, res.desa_list && res.desa_list[0] ? res.desa_list[0] : '');

      txtJalan.focus();
    } catch (err) {
      setStatus('Gagal terhubung', 'err');
      console.error(err);
    }
  }

  // ---- Simpan & Terapkan ke Form ----
  btnSimpan.addEventListener('click', function () {
    const jalan   = txtJalan.value.trim();
    const prov    = selProvinsi.value.trim();
    const kab     = selKabupaten.value.trim();
    const kec     = selKecamatan.value.trim();
    const desa    = selDesa.value.trim();
    const kp      = inputKodepos.value.trim();
    const catatan = txtCatatan.value.trim();

    if (!prov || !kab || !kec) {
      alert('Silakan pilih Provinsi, Kota/Kabupaten, dan Kecamatan.');
      return;
    }
    if (!jalan) {
      alert('Silakan lengkapi Alamat Jalan & Detail Rumah.');
      txtJalan.focus();
      return;
    }

    // Format alamat lengkap
    let full = jalan;
    if (desa) full += ', Kel. ' + desa;
    full += ', Kec. ' + kec + ', ' + kab + ', ' + prov;
    if (kp) full += ' ' + kp;
    if (catatan) full += ' (Catatan: ' + catatan + ')';

    // Update target input tersembunyi
    if (activeTargetInputId) {
      const target = document.getElementById(activeTargetInputId);
      if (target) {
        target.value = full;
        target.dispatchEvent(new Event('change', { bubbles: true }));
      }

      // Update control tampilan di form
      const container = document.querySelector('[data-address-target="' + activeTargetInputId + '"]');
      if (container) {
        updateAddressControlDisplay(container, full);
      }
    }

    window.closeModalAlamat();
  });

  function updateAddressControlDisplay(container, fullAddress) {
    const tid = activeTargetInputId;
    container.innerHTML = `
      <div class="address-input-control" onclick="window.openModalAlamat('${tid}')">
        <div class="address-input-content">
          <div class="address-input-text">${esc(fullAddress)}</div>
        </div>
        <button type="button" class="address-input-btn" aria-label="Ubah Alamat">
          <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
          <span>Ubah</span>
        </button>
      </div>
    `;
  }

  function esc(s) {
    return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
})();
