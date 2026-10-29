# Kontrak View - Cloud Market

Dokumen ini dibuat otomatis dari komentar di atas setiap file `themes/default/**.php`.

**Aturan emas mengganti UI:** controller hanya mengirim *variabel* dan menerima *field form* yang tercantum di bawah. Selama sebuah template memakai variabel & nama field yang sama, bentuknya boleh diubah sebebas apa pun (HTML/CSS/framework).

## Cara membuat tema baru

1. Salin `themes/default` menjadi `themes/namabaru` (atau buat folder kosong: file yang tidak ada otomatis diambil dari `default`).
2. Taruh CSS/JS/gambar di `public/assets/namabaru/`.
3. Ubah `config/config.php` menjadi `'theme' => 'namabaru'`.

## Fungsi bantu di template

| Fungsi | Kegunaan |
|---|---|
| `e($x)` | escape HTML (wajib untuk semua data) |
| `url('/path', ['q'=>1])` | URL halaman |
| `asset('css/app.css')` | URL aset tema aktif |
| `upload_url($path)` | URL foto barang / bukti bayar |
| `rupiah($n)` / `tanggal($dt)` | format angka & waktu |
| `csrf_field()` | WAJIB di setiap `<form method="post">` |
| `old('field')` | isian lama saat validasi gagal |
| `peran()` / `auth()` | peran (`tamu/pembeli/penjual/admin`) & data user login |
| `status_class($status)` | kelas CSS lencana status |
| `$this->partial('partials/x', [..])` | sertakan potongan view |
| `$this->title`, `$this->bodyClass` | judul halaman / kelas `<body>` |

## Variabel yang selalu ada
`$errors` (pesan error per-field; kunci `umum` untuk pesan umum), `$old` (isian lama), `$user`, `$menu` (dari `config/menu.php`), `$flashSuccess`, `$flashError`.

## Daftar view

### `admin/akun`
```
KELOLA AKUN PENGGUNA (admin)
Variabel: $pengguna [peran,id,kode,username,nama,noHp,toko,aktif], $q, $bisaNonaktifPembeli (bool)
FORM cari : GET /admin/akun?q=
FORM aksi : POST /admin/akun/{peran}/{id}/status | field: _csrf, aksi (aktifkan|nonaktifkan), q
```

### `admin/dashboard`
```
DASHBOARD ADMIN. Variabel: $totalTransaksi, $produkAktif, $totalPengguna, $transaksiBaru [idTransaksi,tanggal,namaPembeli,statusPembayaran],
 $barangBaru [namaBarang,namaToko], $penggunaBaru (penjual), $pembeliBaru
```

### `admin/kategori`
```
KELOLA KATEGORI (admin). Variabel: $kategori [idKategori,namaKategori,jumlahBarang,(deskripsi)]
```

### `admin/kategori_form`
```
FORM TAMBAH / EDIT KATEGORI
Variabel: $kategori (null = tambah), $aksi, $punyaDeskripsi (bool), $jumlahBarang (edit), $errors, $old
FORM: POST $aksi | field: _csrf, namaKategori, deskripsi (jika $punyaDeskripsi)
```

### `auth/login`
```
HALAMAN LOGIN
Variabel: $peranAwal ('admin'|'penjual'|'pembeli'), $errors (['umum'=>..,'peran'=>..]), $old
FORM: POST /login  | field: _csrf (csrf_field()), peran, username, password
```

### `auth/registrasi`
```
HALAMAN REGISTRASI (Pembeli / Penjual)
Variabel: $peranAwal, $errors (per field + 'umum'), $old
FORM: POST /registrasi | field: _csrf, peran, nama, username, noHp, alamat, password,
                         (khusus penjual) namaToko, deskripsiToko
```

### `barang/cari`
```
CARI BARANG
Variabel: $filter [q,kategori,min,max,urut], $hasil [items,total,page,pages], $kategori
FORM: GET /barang | field: q, kategori, min, max, urut(terbaru|termurah|termahal), page
```

### `barang/detail`
```
DETAIL BARANG
Variabel: $barang [idBarang,namaBarang,harga,stok,deskripsi,gambar,namaKategori,namaToko,verified,namaPenjual,idPenjual]
Aksi: link ke GET /beli/{idBarang} (hanya pembeli)
```

### `barang/form`
```
FORM TAMBAH / EDIT BARANG (satu template untuk dua fungsi)
Variabel: $barang (null = tambah | array = edit), $kategori, $aksi (URL POST), $errors, $old
FORM: POST $aksi, enctype multipart | field: _csrf, namaBarang, idKategori, harga, stok, deskripsi, gambar (file)
```

### `barang/saya`
```
PRODUK SAYA (penjual). Variabel: $barang [baris Barang milik penjual]
```

### `beranda/index`
```
BERANDA (pembeli / tamu)
Variabel: $kategori [idKategori, namaKategori], $barang [kartu produk terbaru]
```

### `errors/error`
```
Variabel: $kode (int), $pesan (string)
```

### `layouts/auth`
```
LAYOUT HALAMAN LOGIN/REGISTRASI. Variabel: $content, $flashSuccess, $flashError
```

### `layouts/main`
```
LAYOUT UTAMA (tema default)
Variabel: $content (HTML halaman), $menu (array menu per peran), $user (array|null),
          $flashSuccess, $flashError, $this->title
```

### `partials/field`
```
Satu baris input form. Variabel: $name, $label, $type(=text), $value(=old), $placeholder, $extra, $errors
```

### `partials/flash`
```
Variabel: $flashSuccess, $flashError (string|null)
```

### `partials/paginasi`
```
Variabel: $hasil (page, pages), $filter (array query yang dipertahankan), $path
```

### `partials/produk_kartu`
```
Kartu produk. Variabel: $b (baris Barang: idBarang,namaBarang,harga,stok,gambar,namaToko,namaKategori)
```

### `partials/status_badge`
```
Variabel: $s (teks status).
```

### `profil/edit`
```
KELOLA PROFIL (semua peran)
Variabel: $profil (baris akun: nama, username, [alamat, noHp, namaToko, deskripsiToko untuk penjual/pembeli]), $errors, $old
FORM: POST /profil | field: _csrf, nama, alamat, noHp, (penjual) namaToko, deskripsiToko, password_baru (opsional)
```

### `shared/konfirmasi`
```
Dialog konfirmasi generik (logout, hapus barang, hapus kategori).
 Variabel: $judul, $pesan, $aksi (URL POST), $batal (URL), $labelYa, $bahaya (bool)
```

### `transaksi/checkout`
```
CHECKOUT (Beli Barang)
Variabel: $barang, $pembeli [alamat, nama, noHp], $metode [namaMetode => ['butuh_bukti','instruksi']], $tersedia (bool), $errors, $old
FORM: POST /beli/{idBarang} | field: _csrf, jumlah, alamatPengiriman, metodePembayaran
```

### `transaksi/konfirmasi`
```
KONFIRMASI PEMBAYARAN (penjual)
Variabel: $daftar [idTransaksi,tanggal,namaPembeli,barang,total,metodePembayaran,statusPembayaran,buktiPembayaran],
          $statusDipilih, $semuaStatus
FORM filter: GET /penjual/konfirmasi?status=...
FORM aksi  : POST /penjual/konfirmasi/{id}/terima | /tolak  | field: _csrf
```

### `transaksi/laporan`
```
LAPORAN TRANSAKSI (admin & penjual)
Variabel: $filter [dari,sampai,status,q,idPenjual], $rows [idTransaksi,tanggal,namaPembeli,barang,total,metodePembayaran,statusPembayaran],
          $totalPendapatan (hanya status 'Sudah Dibayar'), $semuaStatus, $daftarToko [idPenjual,namaToko] (admin), $adalahAdmin (bool)
FORM: GET /laporan | field: q, dari, sampai, status, toko (admin)
```

### `transaksi/pembayaran`
```
PEMBAYARAN
Variabel: $transaksi [idTransaksi,metodePembayaran,statusPembayaran,statusPengiriman,total,buktiPembayaran,alamatPengiriman],
          $items [namaBarang,jumlah,hargaSatuan,subtotal,gambar], $instruksi (teks), $butuhBukti (bool), $bisaBayar (bool)
FORM bayar : POST /pesanan/{id}/bayar (multipart) | field: _csrf, bukti (file; jika $butuhBukti)
FORM batal : POST /pesanan/{id}/batal              | field: _csrf
```

### `transaksi/pesanan_masuk`
```
PESANAN MASUK (penjual)
Variabel: $pesanan [idTransaksi,tanggal,namaPembeli,barang,total,metodePembayaran,statusPembayaran,statusPengiriman]
FORM kirim: POST /penjual/pesanan/{id}/kirim | field: _csrf
```

### `transaksi/pesanan_saya`
```
PESANAN SAYA (pembeli). Variabel: $pesanan [idTransaksi,tanggal,barang,total,metodePembayaran,statusPembayaran,statusPengiriman]
```

### `transaksi/status`
```
STATUS TRANSAKSI
Variabel: $transaksi, $items, $kelompok ('menunggu' | 'berhasil' | 'batal')
FORM: POST /pesanan/{id}/diterima | field: _csrf (tampil jika statusPengiriman = 'Sudah Dikirim')
```
