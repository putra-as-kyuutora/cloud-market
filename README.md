# Cloud Market - Sistem Penjualan Barang Bekas (PHP MVC)

PHP 8 + MySQL, tanpa framework/Composer. Arsitektur **Model - View - Controller** mengikuti laporan Tahap 1
(Entity / Boundary / Control), dengan satu tujuan utama: **UI bisa diganti tanpa menyentuh logika**.

## Instalasi (XAMPP)
1. Salin folder ini ke `htdocs/cloud-market`.
2. Buka phpMyAdmin/SQLyog. Database `cloud_market` Anda sudah ada. Jika kosong, jalankan `database/CREATE_TABEL.sql`.
3. Jalankan `database/seed.sql` (admin awal + 7 kategori). **Login admin: `admin` / `admin123`**, segera ganti di menu Profil.
4. (Disarankan) jalankan `database/migrasi_opsional.sql` (lihat bagian "Catatan database").
5. Sesuaikan `config/config.php` (user/password MySQL).
6. Buka **http://localhost/cloud-market/public/**
   (atau arahkan DocumentRoot Apache ke folder `public`, atau jalankan `php -S localhost:8000 -t public`).
   Pastikan `mod_rewrite` aktif (default di XAMPP) dan folder `public/uploads/*` dapat ditulis.

## Struktur
```
public/            satu-satunya folder yang diakses web (index.php = front controller)
  assets/<tema>/   CSS/JS/gambar milik tiap tema
  uploads/         foto barang & bukti bayar
config/            config.php (database, tema, metode bayar) dan menu.php (menu per peran)
app/
  routes.php       PETA SEMUA URL -> Controller (satu file)
  Core/            Router, View, Auth, Session, Database, Upload, helpers (jarang disentuh)
  Models/          Entity (Admin, Penjual, Pembeli, Barang, Kategori, Transaksi, DetailTransaksi)  = M
  Controllers/     15 controller use case dari laporan + beberapa pendukung                       = C
themes/
  default/         seluruh tampilan (.php berisi HTML)                                            = V
  gelap/           contoh tema: hanya CSS, semua halaman diwarisi dari default
docs/KONTRAK_VIEW.md   daftar tiap view: variabel masuk & field form
tests/e2e_test.py      uji alur lengkap (butuh Python + requests, DB baru)
```
Alur: `GET /barang/5` -> `routes.php` -> `LihatInformasiBarangController` -> `Barang` (Model) -> `View::render('barang/detail', [...])` -> `themes/<tema>/barang/detail.php`.

## Mengganti UI (inti permintaan)
* **Ganti warna/gaya**: ubah token di `:root` pada `public/assets/default/css/app.css`.
* **Ganti seluruh tampilan**: buat `themes/tema-baru/`, set `'theme' => 'tema-baru'` di `config/config.php`.
  File view yang tidak Anda buat otomatis diambil dari `default`, jadi Anda bisa mengganti halaman satu per satu.
* **Kontrak**: controller tidak pernah menulis HTML. Ia hanya mengirim variabel dan membaca field form yang tercantum di
  `docs/KONTRAK_VIEW.md`. Selama itu dipenuhi, HTML/CSS/JS di view bebas diubah (Bootstrap, Tailwind, dll.).
* **Menu** ada di `config/menu.php`, bukan di HTML.
* Dua aturan di template: bungkus data dengan `e()` dan beri `csrf_field()` pada setiap form POST.

## Menambah fitur baru (contoh: halaman "Wishlist")
1. Tabel baru di DB, lalu `app/Models/Wishlist.php` (extends `Model`).
2. `app/Controllers/WishlistController.php` (extends `Controller`, panggil `$this->view('wishlist/index', [...])`).
3. Daftarkan di `app/routes.php` dan (opsional) di `config/menu.php`.
4. Buat `themes/default/wishlist/index.php`.

Menambah **metode pembayaran** cukup menambah satu entri di `config/config.php` -> `pembayaran`.

## Keamanan yang sudah ada
Prepared statement (PDO) di semua query; password `password_hash` (bcrypt); CSRF token di semua POST;
escape output lewat `e()`; validasi upload dengan MIME asli (finfo) + nama file acak; pengecekan kepemilikan
(penjual hanya bisa ubah barangnya, pembeli hanya melihat pesanannya); akun yang dinonaktifkan langsung terblokir
walau sesi masih terbuka; stok dikunci `SELECT ... FOR UPDATE` dalam transaksi DB (aman dari beli bersamaan).

## Catatan database (perbedaan CREATE TABLE vs laporan)
Aplikasi berjalan di atas skema Anda tanpa perubahan. Beberapa hal yang perlu Anda ketahui:
| Temuan | Dampak | Penanganan |
|---|---|---|
| `Pembeli` tidak punya kolom `aktif` (laporan menyebutnya ada) | Admin tidak bisa menonaktifkan pembeli | `migrasi_opsional.sql`; tanpa itu muncul pesan jelas, tidak error |
| `Kategori` tidak punya `deskripsi` (UI Edit Kategori punya) | field deskripsi tidak muncul | `migrasi_opsional.sql` (otomatis aktif bila kolom ada) |
| `Penjual/Pembeli/Kategori/Transaksi` punya `idAdmin NOT NULL` | harus ada minimal 1 admin | diisi otomatis dengan admin pertama (`seed.sql`) |
| `Transaksi.metodePembayaran NOT NULL` | metode harus dipilih saat checkout | metode dipilih di halaman checkout |
| Tidak ada kolom email, ID KTP, createdAt, kondisi barang | field tsb pada UI/use case belum didukung | tidak dipakai; "Bekas" ditampilkan statis |
| `Transaksi` tidak punya `idPenjual` | satu transaksi = barang satu penjual (tanpa keranjang) | penjual dicari lewat `DetailTransaksi -> Barang`; jika kelak ada keranjang multi-toko, pecah transaksi per toko |
| `DetailTransaksi.idBarang` `ON DELETE RESTRICT` | barang yang pernah dipesan tidak bisa dihapus | ditangkap, penjual diberi saran set stok 0 |

## Belum dikerjakan (ada di UI referensi, tidak ada di use case)
Nego/chat, keranjang, voucher, wallet, rating penjual. Struktur MVC-nya siap untuk ditambahkan.
