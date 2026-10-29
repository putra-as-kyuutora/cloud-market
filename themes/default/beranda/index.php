<?php
/**
 * BERANDA - Full-frame design.
 * Variabel: $kategori [idKategori, namaKategori], $barang [array produk terbaru], $menu, $user
 */
$this->title = 'Beranda';
$appName = \App\Core\Config::get('app_name');

// Ikon SVG per nama kategori (fallback ke ikon grid)
$ikonKategori = function(string $nama): string {
    $n = mb_strtolower($nama);
    if (str_contains($n,'fashion') || str_contains($n,'baju') || str_contains($n,'pakaian'))
        return '<path d="M22 8L8 16l6 10 8-4v34h20V22l8 4 6-10-14-8c-2 5-6 7-10 7s-8-2-10-7z"/>';
    if (str_contains($n,'gadget') || str_contains($n,'hp') || str_contains($n,'ponsel') || str_contains($n,'elektronik'))
        return '<rect x="18" y="6" width="28" height="52" rx="6"/><path d="M28 50h8"/>';
    if (str_contains($n,'hobby') || str_contains($n,'hobi') || str_contains($n,'game'))
        return '<rect x="6" y="18" width="52" height="30" rx="14"/><path d="M20 26v14M13 33h14M42 30h.1M48 36h.1"/>';
    if (str_contains($n,'olahraga') || str_contains($n,'sport') || str_contains($n,'gym'))
        return '<circle cx="32" cy="32" r="24"/><path d="M8 32h48M32 8c-12 12-12 36 0 48M32 8c12 12 12 36 0 48"/>';
    if (str_contains($n,'rumah') || str_contains($n,'furniture') || str_contains($n,'perabot'))
        return '<path d="M6 30L32 8l26 22M12 26v30h40V26M26 56V38h12v18"/>';
    if (str_contains($n,'tulis') || str_contains($n,'buku') || str_contains($n,'alat'))
        return '<path d="M10 54l4-12L44 12l8 8-30 30zM38 18l8 8M10 54l12-4"/>';
    if (str_contains($n,'kendaraan') || str_contains($n,'motor') || str_contains($n,'mobil'))
        return '<path d="M8 38l6-16h36l6 16v12H8zM8 38h48"/><circle cx="19" cy="44" r="2"/><circle cx="45" cy="44" r="2"/><path d="M12 50v6M52 50v6"/>';
    // default: grid
    return '<rect x="10" y="10" width="18" height="18" rx="3"/><rect x="36" y="10" width="18" height="18" rx="3"/><rect x="10" y="36" width="18" height="18" rx="3"/><rect x="36" y="36" width="18" height="18" rx="3"/>';
};

// Posisi X kategori (8 slot, mulai dari 160, langkah 214)
$katSlot = [160, 374, 588, 802, 1016, 1230, 1444, 1658];
// Baris 1 produk: top 953, 4 kolom; Baris 2: top 1607
$produkSlot = [
    ['left' => 58,   'top' => 953],
    ['left' => 554,  'top' => 953],
    ['left' => 1034, 'top' => 953],
    ['left' => 1514, 'top' => 953],
    ['left' => 51,   'top' => 1607],
    ['left' => 547,  'top' => 1607],
    ['left' => 1027, 'top' => 1607],
    ['left' => 1507, 'top' => 1607],
];
?>

<div class="b-viewport" id="b-viewport">
  <div class="b-frame" id="b-frame">


    <!-- ===== CAROUSEL HERO ===== -->
    <section class="b-abs b-carsl"></section>
    <div class="b-abs b-line"></div>

    <h1 class="b-abs b-disp b-judul">Bekas bukan berarti<br>tidak berkualitas</h1>

    <a class="b-btn b-btn-belanja" href="<?= e(url('/barang')) ?>">Mulai Belanja
      <svg viewBox="0 0 32 32" aria-hidden="true">
        <path d="M3 5h5l3 15h14l3-11H9"/><circle cx="13" cy="26" r="2"/><circle cx="23" cy="26" r="2"/>
      </svg>
    </a>
    <?php if (peran() === 'tamu'): ?>
      <a class="b-btn b-btn-jualan" href="<?= e(url('/registrasi', ['peran' => 'penjual'])) ?>">Mulai Jualan
        <svg viewBox="0 0 32 32" aria-hidden="true">
          <path d="M3 11l2-6h22l2 6M5 11v16h22V11M3 11a4 4 0 0 0 8 0 4 4 0 0 0 7 0 4 4 0 0 0 7 0 4 4 0 0 0 4 0"/>
        </svg>
      </a>
    <?php elseif (peran() === 'pembeli'): ?>
      <a class="b-btn b-btn-jualan" href="<?= e(url('/pesanan')) ?>">Pesanan Saya
        <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M4 6h24v20H4zM10 6V4a2 2 0 0 1 12 0v2"/></svg>
      </a>
    <?php endif; ?>

    <p class="b-abs b-sub">Jual barang yang udah gak dipakai</p>

    <!-- Promo Cards - Tetap 3 kartu terlihat seperti awal -->
    <div class="abs cc c-diskon b-abs b-cc b-c-diskon is-left" id="b-card-diskon"><div class="foto"></div><div class="pill">Diskon</div><div class="cap">Banyak Diskon</div></div>
    <div class="abs cc c-nego b-abs b-cc b-c-nego is-center" id="b-card-nego"><div class="foto"></div><div class="pill">Bisa Nego</div><div class="cap">Nego Sampai Jadi</div></div>
    <div class="abs cc c-murah b-abs b-cc b-c-murah is-right" id="b-card-murah"><div class="foto"></div><div class="pill">Harga Murah</div><div class="cap">Banyak Harga Murah</div></div>

    <!-- Jaminan -->
    <div class="b-abs b-jaminan" style="left:472px"></div>
    <div class="b-abs b-jaminan-ikon" style="left:483px">
      <svg viewBox="0 0 24 24"><path d="M4 12l5 5L20 6"/></svg>
    </div>
    <div class="b-abs b-jaminan-teks" style="left:534px">Belanja Nyaman di <?= e($appName) ?></div>

    <div class="b-abs b-jaminan" style="left:1021px"></div>
    <div class="b-abs b-jaminan-ikon" style="left:1034px">
      <svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/></svg>
    </div>
    <div class="b-abs b-jaminan-teks" style="left:1086px">Belanja Aman di <?= e($appName) ?></div>

    <!-- ===== KATEGORI ===== -->
    <!-- Tombol Lihat Semua -->
    <a class="b-abs b-kat" style="left:<?= $katSlot[0] ?>px" href="<?= e(url('/barang')) ?>">
      <svg viewBox="0 0 64 64">
        <rect x="10" y="10" width="18" height="18" rx="3"/>
        <rect x="36" y="10" width="18" height="18" rx="3"/>
        <rect x="10" y="36" width="18" height="18" rx="3"/>
        <rect x="36" y="36" width="18" height="18" rx="3"/>
      </svg>
    </a>
    <div class="b-abs b-kat-nama" style="left:<?= $katSlot[0] - 39 ?>px">Lihat Semua</div>

    <?php foreach (array_slice($kategori, 0, 7) as $i => $k): ?>
      <?php $sx = $katSlot[$i + 1] ?? ($katSlot[count($katSlot)-1] + ($i)*214); ?>
      <a class="b-abs b-kat" style="left:<?= $sx ?>px"
         href="<?= e(url('/barang', ['kategori' => $k['idKategori']])) ?>">
        <svg viewBox="0 0 64 64"><?= $ikonKategori($k['namaKategori']) ?></svg>
      </a>
      <div class="b-abs b-kat-nama" style="left:<?= $sx - 39 ?>px"><?= e($k['namaKategori']) ?></div>
    <?php endforeach; ?>

    <!-- ===== PRODUK TERBARU ===== -->
    <?php foreach (array_slice($barang, 0, 8) as $i => $b): ?>
      <?php $slot = $produkSlot[$i] ?? ['left' => 58 + ($i % 4) * 496, 'top' => 953]; ?>
      <a class="b-abs b-produk" style="left:<?= $slot['left'] ?>px;top:<?= $slot['top'] ?>px"
         href="<?= e(url('/barang/' . $b['idBarang'])) ?>">
        <div class="gambar">
          <svg viewBox="0 0 64 64">
            <rect x="6" y="10" width="52" height="44" rx="6"/>
            <circle cx="22" cy="26" r="5"/>
            <path d="M6 46l16-14 12 10 8-6 16 12"/>
          </svg>
          <?php if ($b['gambar']): ?>
            <img src="<?= e(upload_url($b['gambar'])) ?>" alt="<?= e($b['namaBarang']) ?>" loading="lazy">
          <?php endif; ?>
          <?php if ((int)$b['stok'] < 1): ?>
            <span class="ribbon-habis">Habis</span>
          <?php endif; ?>
        </div>
        <div class="nama"><?= e($b['namaBarang']) ?></div>
        <div class="kondisi">Bekas &middot; <?= e($b['namaKategori']) ?></div>
        <div class="harga"><?= e(rupiah($b['harga'])) ?></div>
      </a>
    <?php endforeach; ?>

    <!-- Lihat lebih banyak -->
    <a class="b-abs b-lebih" style="left:761px;top:2261px" href="<?= e(url('/barang')) ?>">Lihat Lebih Banyak</a>

    <!-- Teks tengah -->
    <h2 class="b-abs b-aman" style="left:674px;top:2443px;width:799px">Aman dan terlindungi</h2>
    <p class="b-abs b-forum" style="left:608px;top:2527px;width:811px">
      Forum jual beli barang aman dan seru, tempat pembeli dan penjual berbagi bertransaksi barang bekas.
    </p>

    <!-- ===== BANNER CTA ===== -->
    <section class="b-abs b-banner" style="top:2716px">
      <p class="b-banner-teks b-disp"><span>Belanja di <?= e($appName) ?><br>Sekarang Juga!</span></p>
    </section>

    <!-- ===== FOOTER ===== -->
    <p class="b-abs b-brand-foot" style="left:52px;top:3230px"><?= e($appName) ?> Indonesia</p>
    <p class="b-abs b-tagline" style="left:57px;top:3353px;width:867px">
      Tempat untuk kebutuhan pembelian dan penjualan anda semua.
    </p>

    <a href="#" aria-label="Instagram">
      <svg class="b-abs b-sosial" style="left:1174px;top:3231px" viewBox="0 0 82 82">
        <rect x="10" y="10" width="62" height="62" rx="18"/>
        <circle cx="41" cy="41" r="14"/>
        <circle cx="58" cy="24" r="2"/>
      </svg>
    </a>
    <a href="#" aria-label="TikTok">
      <svg class="b-abs b-sosial" style="left:1345px;top:3231px" viewBox="0 0 82 82">
        <path d="M34 30v28a10 10 0 1 1-10-10M44 12c1 10 7 16 18 17"/>
        <path d="M44 12v44a10 10 0 1 1-10-10"/>
      </svg>
    </a>
    <a href="#" aria-label="Facebook">
      <svg class="b-abs b-sosial" style="left:1504px;top:3231px" viewBox="0 0 82 82">
        <circle cx="41" cy="41" r="31"/>
        <path d="M45 71V44h9M36 44h18M45 44v-9a6 6 0 0 1 6-6h4"/>
      </svg>
    </a>
    <a href="#" aria-label="LinkedIn">
      <svg class="b-abs b-sosial" style="left:1680px;top:3231px" viewBox="0 0 82 82">
        <rect x="10" y="10" width="62" height="62" rx="10"/>
        <path d="M25 36v22M25 26v.1M37 58V36M37 46c0-8 20-12 20 0v12"/>
      </svg>
    </a>

    <a class="b-abs b-tautan-foot" style="left:1163px;top:3390px" href="#">Privacy Policy</a>
    <a class="b-abs b-tautan-foot" style="left:1522px;top:3390px" href="#">Terms &amp; Conditions</a>

  </div><!-- /#b-frame -->
</div><!-- /#b-viewport -->

<script>
(function () {
  // ===== SKALA FRAME =====
  var W = 1994, H = 3568;
  var frame = document.getElementById('b-frame');
  var vp    = document.getElementById('b-viewport');
  function fit() {
    var s = document.documentElement.clientWidth / W;
    frame.style.transform = 'scale(' + s + ')';
    vp.style.height = (H * s) + 'px';
  }
  window.addEventListener('resize', fit);
  fit();

  // ===== SARAN PENCARIAN =====
  <?php
  $namaBrg = array_map(fn($b) => $b['namaBarang'], $barang);
  $namaKat = array_map(fn($k) => $k['namaKategori'], $kategori);
  $saranList = array_merge($namaBrg, $namaKat);
  echo 'var DAFTAR = ' . json_encode(array_values($saranList), JSON_UNESCAPED_UNICODE) . ';';
  ?>
  var form = document.getElementById('b-form-cari');
  var q    = document.getElementById('b-q');
  var ul   = document.getElementById('b-saran');
  var aktif = -1;

  function render() {
    var kata = q.value.trim().toLowerCase();
    var hasil = DAFTAR.filter(function(d) { return kata && d.toLowerCase().indexOf(kata) !== -1; }).slice(0, 5);
    ul.innerHTML = '';
    aktif = -1;
    hasil.forEach(function(d) {
      var li = document.createElement('li');
      li.setAttribute('role', 'option');
      li.textContent = d;
      li.addEventListener('mousedown', function(e) { e.preventDefault(); pilih(d); });
      ul.appendChild(li);
    });
    ul.classList.toggle('buka', hasil.length > 0);
  }
  function pilih(teks) { q.value = teks; ul.classList.remove('buka'); form.submit(); }
  function sorot(i) {
    Array.from(ul.children).forEach(function(el, k) { el.classList.toggle('sorot', k === i); });
    aktif = i;
  }

  q.addEventListener('input',  function() { ul.classList.add('buka'); render(); });
  q.addEventListener('focus',  function() { render(); });
  q.addEventListener('blur',   function() { setTimeout(function() { ul.classList.remove('buka'); }, 120); });
  q.addEventListener('keydown', function(e) {
    var n = ul.children.length;
    if (e.key === 'ArrowDown' && n) { e.preventDefault(); sorot((aktif + 1) % n); }
    else if (e.key === 'ArrowUp' && n) { e.preventDefault(); sorot((aktif - 1 + n) % n); }
    else if (e.key === 'Enter' && aktif >= 0) { e.preventDefault(); pilih(ul.children[aktif].textContent); }
    else if (e.key === 'Escape') { ul.classList.remove('buka'); }
  });

  // ===== Animasi Promo Cards: carousel 3 kartu =====
  (function () {
    var cards = [
      document.getElementById('b-card-diskon'),
      document.getElementById('b-card-nego'),
      document.getElementById('b-card-murah')
    ];

    if (cards.some(function (card) { return !card; })) return;

    var positions = ['is-left', 'is-center', 'is-right'];
    // Posisi awal: Diskon kiri, Nego tengah, Harga Murah kanan.
    var currentPositions = [0, 1, 2];
    var timer = null;
    var interval = 4000;

    function updateCards() {
      cards.forEach(function (card, index) {
        positions.forEach(function (positionClass) {
          card.classList.remove(positionClass);
        });
        card.classList.add(positions[currentPositions[index]]);
      });
    }

    function rotateCards() {
      currentPositions = currentPositions.map(function (position) {
        return (position + 1) % 3;
      });
      updateCards();
    }

    // Pindahkan kartu yang disentuh ke tengah dengan pergeseran satu posisi,
    // supaya ketiga kartu tetap bergerak bersama dan transisinya tidak melompat.
    function bringToCenter(cardIndex) {
      var position = currentPositions[cardIndex];

      if (position === 1) return;

      var step = position === 0 ? 1 : 2;
      currentPositions = currentPositions.map(function (currentPosition) {
        return (currentPosition + step) % 3;
      });

      updateCards();
    }

    function stopAutoRotate() {
      if (timer !== null) {
        clearInterval(timer);
        timer = null;
      }
    }

    function startAutoRotate() {
      stopAutoRotate();
      timer = setInterval(rotateCards, interval);
    }

    cards.forEach(function (card, index) {
      card.addEventListener('mouseenter', function () {
        stopAutoRotate();
        bringToCenter(index);
      });

      card.addEventListener('focus', function () {
        stopAutoRotate();
        bringToCenter(index);
      });

      card.addEventListener('mouseleave', startAutoRotate);
      card.addEventListener('blur', startAutoRotate);

      card.addEventListener('click', function () {
        stopAutoRotate();
        bringToCenter(index);
        startAutoRotate();
      });

      // Agar kartu bisa diakses lewat keyboard.
      card.setAttribute('tabindex', '0');
      card.setAttribute('role', 'button');
      card.setAttribute('aria-label', 'Tampilkan kartu ' +
        (card.querySelector('.pill') ? card.querySelector('.pill').textContent.trim() : 'promo'));
    });

    updateCards();
    startAutoRotate();
  })();
})();
</script>
