Listed directory cloud-market
Listed directory app
Listed directory config
Listed directory database
Listed directory public
Listed directory themes
Viewed README.md:1-72
Listed directory Controllers
Listed directory Core
Listed directory Models
Listed directory default
Listed directory gelap
Listed directory admin
Listed directory auth
Listed directory barang
Listed directory transaksi
Listed directory layouts
Listed directory partials
Listed directory assets
Viewed routes.php:1-74
Viewed config.php:1-51
Viewed menu.php:1-34
Viewed Router.php:1-80
Viewed View.php:1-75
Viewed Controller.php:1-44
Viewed Auth.php:1-52
Viewed Database.php:1-56
Viewed helpers.php:1-61
Viewed Model.php:1-35
Viewed Akun.php:1-80
Viewed Barang.php:1-95
Viewed Transaksi.php:1-125
Viewed CREATE_TABEL.sql:1-153
Viewed index.php:1-35
Listed directory shared
Listed directory beranda
Listed directory profil
Listed directory default
Listed directory css
Listed directory docs
Listed directory errors

Sudah dipahami secara menyeluruh. Berikut ringkasan lengkap struktur proyek **Cloud Market**:

---

## 🧠 Pemahaman Penuh: Cloud Market

### 📌 Overview
**Cloud Market** adalah sistem penjualan barang bekas berbasis **PHP 8 + MySQL**, tanpa framework/Composer, menggunakan arsitektur **MVC murni** (Model–View–Controller). Filosofi utamanya: **UI bisa diganti sepenuhnya tanpa menyentuh logika bisnis**.

---

### 🗂️ Struktur Direktori

```
cloud-market/
├── public/                     ← Satu-satunya folder yang diakses web
│   ├── index.php               ← Front Controller (pintu masuk tunggal)
│   ├── .htaccess               ← Redirect semua request ke index.php
│   ├── assets/
│   │   ├── default/css/app.css ← CSS utama (satu file, token di :root)
│   │   └── gelap/              ← Aset tema gelap (override CSS saja)
│   └── uploads/                ← Foto barang & bukti bayar
│
├── config/
│   ├── config.php              ← DB, tema aktif, upload, metode bayar
│   └── menu.php                ← Menu per peran (tamu/pembeli/penjual/admin)
│
├── app/
│   ├── routes.php              ← Peta SEMUA URL → Controller (satu file)
│   ├── Core/                   ← Framework mini (jarang disentuh)
│   │   ├── Router.php          ← Regex matching, middleware, dispatch
│   │   ├── Controller.php      ← Base controller (view/redirect/flash/error)
│   │   ├── View.php            ← Rendering + fallback tema + layout wrapping
│   │   ├── Auth.php            ← Session user, cek peran, cek aktif
│   │   ├── Database.php        ← PDO singleton + transaction + hasColumn()
│   │   ├── Session.php         ← Flash, CSRF token
│   │   ├── Request.php         ← Path, basePath
│   │   ├── Upload.php          ← Validasi MIME asli (finfo), nama file acak
│   │   ├── Config.php          ← Wrapper array config
│   │   └── helpers.php         ← e(), url(), asset(), rupiah(), csrf_field(), dll
│   │
│   ├── Models/
│   │   ├── Model.php           ← Base: pdo(), run(), all(), one(), scalar()
│   │   ├── Akun.php            ← Abstract; factory forPeran(), validateLogin()
│   │   ├── Admin.php           ← table=Admin, pk=idAdmin
│   │   ├── Penjual.php         ← table=Penjual, pk=idPenjual
│   │   ├── Pembeli.php         ← table=Pembeli, pk=idPembeli
│   │   ├── Barang.php          ← search() dgn filter/paginasi, kurangiStok() (FOR UPDATE)
│   │   ├── Kategori.php        ← CRUD kategori
│   │   ├── Transaksi.php       ← Status bayar+kirim, getLaporan(), constants
│   │   └── DetailTransaksi.php ← Baris per barang dalam satu transaksi
│   │
│   └── Controllers/ (19 file)
│       ├── BerandaController           → GET /
│       ├── RegistrasiController        → GET|POST /registrasi
│       ├── LoginController             → GET|POST /login
│       ├── LogoutController            → GET|POST /logout
│       ├── CariBarangController        → GET /barang
│       ├── LihatInformasiBarangController → GET /barang/{id}
│       ├── EditDataPribadiController   → GET|POST /profil
│       ├── BeliBarangController        → GET|POST /beli/{id}       [pembeli]
│       ├── PembayaranController        → GET|POST /pesanan/{id}/bayar [pembeli]
│       ├── PesananController           → pesanan saya + masuk + kirim + diterima
│       ├── KonfirmasiPembayaranController → konfirmasi/tolak bayar  [penjual]
│       ├── BarangSayaController        → GET /penjual/barang        [penjual]
│       ├── TambahBarangController      → GET|POST /penjual/barang/tambah
│       ├── EditBarangController        → GET|POST /penjual/barang/{id}/edit
│       ├── HapusBarangController       → GET|POST /penjual/barang/{id}/hapus
│       ├── DashboardAdminController    → GET /admin                 [admin]
│       ├── KelolaAkunPenggunaController → akun + ubahStatus         [admin]
│       ├── KelolaKategoriController    → CRUD kategori              [admin]
│       └── LihatLaporanTransaksiController → GET /laporan           [admin|penjual]
│
├── themes/
│   ├── default/                ← Tema utama (semua view ada di sini)
│   │   ├── layouts/main.php    ← Layout utama (navbar, flash, content slot)
│   │   ├── layouts/auth.php    ← Layout halaman login/registrasi
│   │   ├── partials/           ← Komponen kecil: produk_kartu, flash, paginasi, dll
│   │   ├── auth/               ← login.php, registrasi.php
│   │   ├── beranda/index.php
│   │   ├── barang/             ← cari.php, detail.php, form.php, saya.php
│   │   ├── transaksi/          ← checkout, pembayaran, konfirmasi, laporan, status, dll
│   │   ├── admin/              ← dashboard, akun, kategori, kategori_form
│   │   ├── profil/edit.php
│   │   ├── shared/konfirmasi.php
│   │   └── errors/error.php
│   └── gelap/                  ← Tema gelap (hanya CSS override, view fallback ke default)
│
├── database/
│   ├── CREATE_TABEL.sql        ← Skema 7 tabel
│   ├── seed.sql                ← Admin awal + 7 kategori
│   └── migrasi_opsional.sql    ← Tambah kolom aktif (Pembeli) + deskripsi (Kategori)
│
├── docs/KONTRAK_VIEW.md        ← Kontrak variabel: controller ↔ view
└── tests/e2e_test.py           ← End-to-end test (Python + requests)
```

---

### 🔄 Alur Request

```
Browser → public/.htaccess → public/index.php (front controller)
  → autoload PSR-like (App\Core\... → app/Core/...)
  → Config::load() + session_start()
  → Router::dispatch(method, path)
    → regex match routes.php
    → cek CSRF (POST) + jalankan middleware (guest/auth/role)
    → new Controller()->method($params)
      → Model query (PDO prepared statement)
      → View::render('folder/file', $data)
        → cari file di themes/{aktif}/ → fallback themes/default/
        → wrap dengan layouts/main.php
```

---

### 🔐 Sistem Peran

| Peran | Home | Akses Utama |
|-------|------|-------------|
| `tamu` | `/` | Beranda, cari barang, lihat detail |
| `pembeli` | `/` | + beli, pesanan, bayar, profil |
| `penjual` | `/penjual/barang` | + kelola barang, pesanan masuk, konfirmasi bayar, laporan |
| `admin` | `/admin` | + dashboard, kelola akun, kelola kategori, laporan |

---

### 🗄️ Skema Database (7 Tabel)

```
Admin ←── Penjual ←── Barang ←── DetailTransaksi
  └─────── Pembeli ─────────────── Transaksi ──┘
  └─────── Kategori ──┘
```

---

### 🎨 Sistem Tema

- **Tema aktif** diset di `config/config.php` → `'theme' => 'default'`
- View dicari di `themes/{aktif}/` → fallback ke `themes/default/`
- Tema `gelap` hanya override CSS, semua HTML diwarisi dari `default`
- Asset dicari lewat helper `asset()` dengan logika fallback yang sama

---

### ⚙️ Key Design Decisions

1. **Tidak ada Composer** — autoload manual via `spl_autoload_register`
2. **CSRF wajib** di semua POST — dicek di Router sebelum middleware
3. **`SELECT ... FOR UPDATE`** di `kurangiStok()` — aman dari race condition
4. **`Database::hasColumn()`** — handle kolom opsional tanpa error
5. **Controller tidak pernah tulis HTML** — selalu delegasi ke `View::render()`
6. **Menu di config**, bukan di HTML — mudah tambah item tanpa sentuh template

Saya sudah **100% paham** struktur proyek ini. Siap membantu apa saja! 🚀