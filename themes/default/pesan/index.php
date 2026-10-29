<?php
/**
 * CLOUD MESSAGE - NEGO (Alab/nego-chat.html)
 * Variabel: $user, $peran, $percakapan, $partnerAktifId, $partnerAktifPeran,
 *           $partnerData, $histori, $barangNego, $hargaNegoTerakhir, $statusNegoTerakhir,
 *           $isNegoMode, $tawaranParam
 */
$this->title = 'Cloud Message';

$partnerNama = '';
if (!empty($partnerData)) {
    $partnerNama = $partnerData['nama'] ?? '';
    if (!empty($partnerData['namaToko'])) {
        $partnerNama = $partnerData['namaToko'] . ' (' . $partnerData['nama'] . ')';
    }
}
$partnerInisial = $partnerNama !== '' ? mb_strtoupper(mb_substr($partnerNama, 0, 1)) : '';
?>

<div class="chat-frame-container">
  <div class="chat-frame <?= $partnerAktifId ? 'has-active-chat' : 'no-active-chat' ?>">

    <!-- ===== HEADER CHAT ===== -->
    <div class="chat-header">
      <div class="chat-header-brand">
        <svg viewBox="0 0 198 130" width="28" height="18" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
          <path d="M50 108h92a32 32 0 0 0 6-63A44 44 0 0 0 64 40a34 34 0 0 0-14 68z"/>
        </svg>
        <span>Cloud Message</span>
      </div>

      <div class="chat-header-sep"></div>

      <div class="chat-header-user">
        <?php if ($partnerAktifId && $partnerData): ?>
          <a href="<?= e(url('/pesan')) ?>" class="chat-mobile-back-btn" title="Kembali ke daftar percakapan" aria-label="Kembali ke daftar percakapan">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
          </a>
          <div class="chat-header-avatar"><?= e($partnerInisial) ?></div>
          <div class="chat-header-info">
            <div class="chat-header-name"><?= e($partnerNama) ?></div>
            <div class="chat-header-role"><?= e(ucfirst($partnerAktifPeran)) ?></div>
          </div>
        <?php else: ?>
          <div class="chat-header-name placeholder-muted">Pilih percakapan dari daftar obrolan</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ===== BODY CHAT: 2 KOLOM ===== -->
    <div class="chat-body">

      <!-- Kolom Kiri: Daftar Percakapan -->
      <aside class="chat-sidebar">
        <div class="chat-sidebar-title">Daftar Obrolan</div>
        <div class="chat-contact-list">
          <?php if (empty($percakapan)): ?>
            <div class="chat-empty-state">
              <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.35"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <p>Belum ada obrolan aktif.<br>Mulai obrolan dari tombol <strong>Nego</strong> atau <strong>Kirim Pesan</strong> pada halaman produk.</p>
            </div>
          <?php else: ?>
            <?php foreach ($percakapan as $c): ?>
              <?php
                $isAktif = ((int)$c['partnerId'] === (int)$partnerAktifId && $c['partnerPeran'] === $partnerAktifPeran);
                $init = mb_strtoupper(mb_substr($c['partnerNama'], 0, 1));
              ?>
              <a href="<?= e(url('/pesan', ['partner' => $c['partnerId'], 'peran_partner' => $c['partnerPeran']])) ?>"
                 class="chat-row <?= $isAktif ? 'active' : '' ?>">
                <div class="chat-avatar-list"><?= e($init) ?></div>
                <div class="chat-row-content">
                  <div class="chat-row-nama"><?= e($c['partnerNama']) ?></div>
                  <div class="chat-row-sub">
                    <span class="chat-row-prev"><?= e(mb_strimwidth($c['pesanTerakhir'] ?: 'Memulai percakapan', 0, 24, '...')) ?></span>
                    <span class="chat-row-dot"></span>
                    <span class="chat-row-time"><?= e(date('H.i', strtotime($c['waktu']))) ?></span>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </aside>

      <!-- Kolom Kanan: Area Pesan & Nego -->
      <main class="chat-main">

        <?php if (!$partnerAktifId || !$partnerData): ?>
          <!-- EMPTY STATE: KETIKA BELUM ADA PERCAKAPAN DIPILIH -->
          <div class="chat-no-selection">
            <div class="chat-no-selection-card">
              <div class="chat-no-selection-icon">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
              </div>
              <h3 class="chat-no-selection-title">Pilih Obrolan</h3>
              <p class="chat-no-selection-desc">
                Pilih salah satu kontak di sisi kiri untuk membaca dan membalas pesan, atau kunjungi katalog produk untuk memulai tawar-menawar harga.
              </p>
              <a href="<?= e(url('/barang')) ?>" class="chat-no-selection-btn">Lihat Katalog Produk</a>
            </div>
          </div>

          <div class="chat-input-bar chat-input-bar-disabled">
            <span class="chat-disabled-hint">Pilih percakapan terlebih dahulu untuk mengirim pesan</span>
          </div>

        <?php else: ?>

          <div class="chat-messages-area" id="msgScrollArea">

            <!-- Label Hari -->
            <div class="chat-today-badge"><span>Today</span></div>

            <!-- KARTU PRODUK NEGO (Hanya jika obrolan ini terkait barang) -->
            <?php if ($barangNego): ?>
              <div class="chat-kartu-nego">
                <div class="chat-nego-top">
                  <div class="chat-nego-foto">
                    <?php if (!empty($barangNego['gambar'])): ?>
                      <img src="<?= e(upload_url($barangNego['gambar'])) ?>" alt="<?= e($barangNego['namaBarang']) ?>">
                    <?php else: ?>
                      <div class="chat-nego-foto-demo"></div>
                    <?php endif; ?>
                  </div>
                  <div class="chat-nego-prod-info">
                    <a href="<?= e(url('/barang/' . $barangNego['idBarang'])) ?>" class="chat-nego-nama" title="Lihat detail barang">
                      <?= e($barangNego['namaBarang']) ?>
                    </a>
                    <div class="chat-nego-harga-asli">Harga Asli: <?= e(rupiah($barangNego['harga'])) ?></div>
                  </div>
                </div>

                <!-- Baris Pemisah & Status Nego -->
                <div class="chat-nego-bottom">
                  <div class="chat-nego-badge">NEGO</div>
                  <div class="chat-nego-sep-v"></div>
                  <div class="chat-nego-harga-tawar">
                    <?php if ($hargaNegoTerakhir): ?>
                      <span class="nego-nominal"><?= e(rupiah($hargaNegoTerakhir)) ?></span>
                      <?php if ($statusNegoTerakhir === 'diterima'): ?>
                        <span class="badge-nego-status diterima">Disetujui</span>
                      <?php elseif ($statusNegoTerakhir === 'ditolak'): ?>
                        <span class="badge-nego-status ditolak">Ditolak</span>
                      <?php elseif ($statusNegoTerakhir === 'pending'): ?>
                        <span class="badge-nego-status pending">Menunggu Keputusan</span>
                      <?php endif; ?>
                    <?php else: ?>
                      <span class="nego-empty-hint">Belum ada penawaran</span>
                    <?php endif; ?>
                  </div>

                  <?php if ($peran === 'pembeli'): ?>
                    <a class="chat-nego-btn-beli <?= ($statusNegoTerakhir === 'diterima') ? 'btn-nego-acc' : '' ?>"
                       href="<?= e(url('/beli/' . $barangNego['idBarang'])) ?>">
                      <?= ($statusNegoTerakhir === 'diterima') ? 'Beli (Harga Disetujui)' : 'Beli Produk' ?>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- HISTORI PESAN -->
            <?php if (empty($histori)): ?>
              <div class="chat-empty-convo">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <p>Belum ada pesan dalam percakapan ini.<br>Ketik pesan atau tawar harga di bawah untuk memulai obrolan.</p>
              </div>
            <?php else: ?>
              <?php foreach ($histori as $h): ?>
                <?php
                  $isMe = ((int)$h['idPengirim'] === (int)auth()['id'] && $h['peranPengirim'] === $peran);
                  $jam = date('H.i', strtotime($h['waktu']));
                ?>
                <div class="chat-bubble-wrap <?= $isMe ? 'sent' : 'received' ?>">
                  <div class="chat-bubble">
                    <?php if (!$isMe): ?>
                      <svg class="chat-bubble-tail" viewBox="0 0 48 60"><path d="M0 0 C0 30 14 52 48 60 H0 Z" fill="#C7C7C7"/></svg>
                    <?php endif; ?>

                    <div class="chat-bubble-text">
                      <?= nl2br(e($h['isiPesan'])) ?>

                      <?php if (!empty($h['hargaNego'])): ?>
                        <div class="chat-bubble-nego-tag">
                          <div class="nego-tag-head">
                            <span class="nego-tag-label">Penawaran Nego:</span>
                            <span class="nego-tag-price"><?= e(rupiah($h['hargaNego'])) ?></span>
                          </div>

                          <div class="nego-tag-action-row">
                            <?php if ($h['statusNego'] === 'pending'): ?>
                              <span class="badge-nego-status pending">Menunggu Keputusan</span>

                              <?php if ($peran === 'penjual' && !$isMe): ?>
                                <div class="nego-action-btns">
                                  <form method="post" action="<?= e(url('/pesan/nego-status')) ?>" style="display:inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="idPesan" value="<?= (int)$h['idPesan'] ?>">
                                    <input type="hidden" name="status" value="diterima">
                                    <input type="hidden" name="partner" value="<?= (int)$partnerAktifId ?>">
                                    <input type="hidden" name="peran_partner" value="<?= e($partnerAktifPeran) ?>">
                                    <button type="submit" class="btn-nego-action terima">✓ Terima</button>
                                  </form>
                                  <form method="post" action="<?= e(url('/pesan/nego-status')) ?>" style="display:inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="idPesan" value="<?= (int)$h['idPesan'] ?>">
                                    <input type="hidden" name="status" value="ditolak">
                                    <input type="hidden" name="partner" value="<?= (int)$partnerAktifId ?>">
                                    <input type="hidden" name="peran_partner" value="<?= e($partnerAktifPeran) ?>">
                                    <button type="submit" class="btn-nego-action tolak">✕ Tolak</button>
                                  </form>
                                </div>
                              <?php endif; ?>

                            <?php elseif ($h['statusNego'] === 'diterima'): ?>
                              <span class="badge-nego-status diterima">✓ Penawaran Disetujui</span>
                              <?php if ($peran === 'pembeli' && !empty($h['idBarang'])): ?>
                                <a href="<?= e(url('/beli/' . $h['idBarang'])) ?>" class="btn-nego-beli-direct">Beli Sekarang</a>
                              <?php endif; ?>

                            <?php elseif ($h['statusNego'] === 'ditolak'): ?>
                              <span class="badge-nego-status ditolak">✕ Penawaran Ditolak</span>
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>

                    <div class="chat-bubble-meta">
                      <span class="chat-bubble-jam"><?= e($jam) ?></span>
                      <span class="chat-bubble-centang">
                        <svg viewBox="0 0 30 22"><path d="M3 12 L9 18 L20 5"/><path d="M12 12 L18 18 L28 6"/></svg>
                      </span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>

          </div>

          <!-- FORM INPUT PESAN & NEGO -->
          <form class="chat-input-bar" id="chatInputForm" method="post" action="<?= e(url('/pesan/kirim')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="idPenerima" value="<?= (int)$partnerAktifId ?>">
            <input type="hidden" name="peranPenerima" value="<?= e($partnerAktifPeran) ?>">
            <input type="hidden" name="idBarang" value="<?= $barangNego ? (int)$barangNego['idBarang'] : '' ?>">

            <!-- Tombol toggle Nego jika obrolan terkait barang & user adalah pembeli -->
            <?php if ($barangNego && $peran === 'pembeli'): ?>
              <button type="button" class="chat-nego-toggle-btn <?= $isNegoMode ? 'aktif' : '' ?>" id="negoToggle" title="Tawar Harga Produk">
                NEGO
              </button>
              <input type="number" step="1000" min="1000" name="hargaNego" id="inputNegoHarga"
                     class="chat-nego-input-field <?= $isNegoMode ? 'aktif' : '' ?>"
                     placeholder="Harga tawar (Rp)..."
                     value="<?= ($isNegoMode && !empty($tawaranParam)) ? (int)$tawaranParam : '' ?>">
            <?php endif; ?>

            <!-- Input teks pesan utama -->
            <input class="chat-input-text" id="pesanInput" name="isiPesan" type="text"
                   placeholder="<?= ($barangNego && $peran === 'pembeli') ? 'Ketik pesan atau klik NEGO untuk menawar...' : 'Ketik pesan...' ?>"
                   autocomplete="off">

            <!-- Tombol Kirim -->
            <button type="submit" class="chat-input-send" title="Kirim Pesan">
              <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
              </svg>
            </button>
          </form>

        <?php endif; ?>

      </main>

    </div>

  </div>
</div>

<script>
(function() {
  // Auto-scroll chat ke posisi terbawah
  var area = document.getElementById('msgScrollArea');
  if (area) {
    area.scrollTop = area.scrollHeight;
  }

  // Toggle input nego harga
  var toggle = document.getElementById('negoToggle');
  var negoIn = document.getElementById('inputNegoHarga');
  if (toggle && negoIn) {
    toggle.addEventListener('click', function() {
      negoIn.classList.toggle('aktif');
      toggle.classList.toggle('aktif');
      if (negoIn.classList.contains('aktif')) {
        negoIn.focus();
      } else {
        negoIn.value = '';
      }
    });
  }

  // Validasi form: jangan kirim form kosong
  var form = document.getElementById('chatInputForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      var msg = document.getElementById('pesanInput');
      var ngo = document.getElementById('inputNegoHarga');
      var msgVal = msg ? msg.value.trim() : '';
      var ngoVal = ngo ? ngo.value.trim() : '';

      if (msgVal === '' && ngoVal === '') {
        e.preventDefault();
        if (msg) msg.focus();
      }
    });
  }
})();
</script>
