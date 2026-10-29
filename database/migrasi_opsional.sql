-- MIGRASI OPSIONAL - aplikasi tetap jalan tanpa ini, tetapi fitur berikut baru aktif setelah dijalankan.
USE cloud_market;

-- (Disarankan) agar admin bisa menonaktifkan akun Pembeli (UC Kelola Akun Pengguna).
-- Laporan menyebut kolom ini sudah ditambahkan, tetapi CREATE TABLE Pembeli belum memilikinya.
ALTER TABLE Pembeli ADD COLUMN aktif TINYINT(1) NOT NULL DEFAULT 1;

-- (Opsional) deskripsi kategori, sesuai UI Tambah/Edit Kategori.
ALTER TABLE Kategori ADD COLUMN deskripsi TEXT NULL;
