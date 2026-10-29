# 🛠️ Database Reset Tool - Cloud Market

Direktori ini berisi utilitas terpisah untuk melakukan **reset dan inisialisasi ulang basis data** sistem Cloud Market secara aman, cepat, dan otomatis.

---

## 📁 Struktur File dalam Folder `database/reset/`

| Nama File | Fungsi |
|---|---|
| **`schema.sql`** | Struktur tabel lengkap DDL (`DROP` & `CREATE TABLE`) untuk seluruh entitas aktif: `users`, `seller_profiles`, `kategori`, `barang`, `transaksi`, `detailtransaksi`, `pesan`, dan `kodepos`. |
| **`seed.sql`** | Data awal bersih (DML `INSERT`) mencakup admin default, kategori resmi, toko penjual terverifikasi, barang sampel, dan contoh transaksi. |
| **`reset.php`** | Mesin eksekusi PHP utama yang dapat dipanggil dari terminal (CLI) maupun browser (Web GUI). |
| **`reset.bat`** | File batch Windows (shortcut 1-klik tanpa perlu mengetik perintah terminal). |
| **`README.md`** | Dokumentasi panduan penggunaan dan informasi akun demo default. |

---

## 🚀 3 Cara Menjalankan Reset Database

Pilih salah satu cara yang paling mudah:

### 1. Cara 1: Lewat Terminal / Command Prompt (CLI)
Buka terminal / PowerShell di root proyek dan jalankan:
```bash
php database/reset/reset.php
```

### 2. Cara 2: Lewat Windows Batch (Klik 2x)
Cukup buka folder `database/reset/` di File Explorer Windows Anda, lalu **klik ganda (double-click)** pada file:
```text
reset.bat
```
Ketik `Y` saat konfirmasi diminta di terminal.

### 3. Cara 3: Lewat Web Browser
Buka peramban (Chrome / Edge / Firefox) dan akses alamat:
```text
http://localhost/cloud-market/database/reset/reset.php
```
Klik tombol **"Ya, Reset Database Sekarang"**.

---

## 🔑 Kredensial Akun Default Setelah Reset

Setelah database direset, Anda dapat langsung login menggunakan akun-akun berikut:

| Peran | Username | Password | Deskripsi / Toko |
|---|---|---|---|
| **Administrator** | `admin` | `admin123` | Akses penuh dashboard `/admin`, kelola kategori, kelola akun, dan verifikasi KYC. |
| **Penjual (Seller)** | `penjual` | `penjual123` | Toko: **Berkah Second Store** (Status: Terverifikasi / Approved). Siap tambah/edit produk & konfirmasi pembayaran. |
| **Pembeli (Buyer)** | `pembeli` | `pembeli123` | Akun pembeli aktif untuk checkout, tawar-menawar harga (nego), dan riwayat pesanan. |
| **Penjual 2** | `putra` | `penjual123` | Toko: **Mewing Vintage Store** (Status: Terverifikasi). Untuk simulasi jual-beli antar penjual. |

---

## 🛡️ Catatan Keamanan Data Kode Pos
Tabel `kodepos` yang memuat data 83.000+ wilayah Indonesia **tetap dipertahankan** secara aman (`CREATE TABLE IF NOT EXISTS`) agar tidak memperlambat proses reset dan fitur deteksi alamat instan tetap berfungsi normal.

