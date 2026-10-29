-- =====================================================================
-- CLOUD MARKET - SKEMA DATABASE TERKINI (MULTI-ROLE ARCHITECTURE)
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. DROP TABEL LAMA & TABEL UTAMA (URUTAN AMAN)
DROP TABLE IF EXISTS detailtransaksi;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS pesan;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS seller_profiles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS _legacy_admin;
DROP TABLE IF EXISTS _legacy_penjual;
DROP TABLE IF EXISTS _legacy_pembeli;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS penjual;
DROP TABLE IF EXISTS pembeli;

-- 2. TABEL USERS (SINGLE-TABLE INHERITANCE UNTUK MULTI-ROLE)
CREATE TABLE users (
    idUser INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NULL,
    noHp VARCHAR(20) NULL,
    is_buyer TINYINT(1) NOT NULL DEFAULT 1,
    is_seller TINYINT(1) NOT NULL DEFAULT 0,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    aktif TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL SELLER_PROFILES (PROFIL TOKO & KYC VERIFIKASI)
CREATE TABLE seller_profiles (
    idUser INT PRIMARY KEY,
    namaToko VARCHAR(100) NOT NULL,
    deskripsiToko TEXT NULL,
    noKtp VARCHAR(30) NULL,
    fotoKtp VARCHAR(255) NULL,
    fotoSelfie VARCHAR(255) NULL,
    statusVerifikasi ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    tglVerifikasi DATETIME NULL,
    diverifikasiOleh INT NULL,
    alasanPenolakan TEXT NULL,
    namaBank VARCHAR(50) NULL,
    noRekening VARCHAR(50) NULL,
    atasNamaRekening VARCHAR(100) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_seller_user
        FOREIGN KEY (idUser) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_seller_verifier
        FOREIGN KEY (diverifikasiOleh) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL KATEGORI (DIKELOLA ADMINISTRATOR)
CREATE TABLE kategori (
    idKategori INT AUTO_INCREMENT PRIMARY KEY,
    idAdmin INT NOT NULL,
    namaKategori VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL,
    CONSTRAINT fk_kategori_admin
        FOREIGN KEY (idAdmin) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL BARANG (PRODUK DARI PENJUAL)
CREATE TABLE barang (
    idBarang INT AUTO_INCREMENT PRIMARY KEY,
    idPenjual INT NOT NULL,
    idKategori INT NOT NULL,
    namaBarang VARCHAR(150) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT NULL,
    gambar VARCHAR(255) NULL,
    CONSTRAINT fk_barang_seller
        FOREIGN KEY (idPenjual) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_barang_kategori
        FOREIGN KEY (idKategori) REFERENCES kategori(idKategori)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABEL TRANSAKSI (PESANAN PEMBELIAN)
CREATE TABLE transaksi (
    idTransaksi INT AUTO_INCREMENT PRIMARY KEY,
    idPembeli INT NOT NULL,
    idAdmin INT NOT NULL,
    tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    metodePembayaran VARCHAR(50) NOT NULL,
    buktiPembayaran VARCHAR(255) NULL,
    statusPembayaran ENUM(
        'Menunggu Pembayaran',
        'Menunggu Konfirmasi',
        'Sudah Dibayar',
        'Ditolak',
        'Dibatalkan'
    ) NOT NULL DEFAULT 'Menunggu Pembayaran',
    statusPengiriman ENUM(
        'Belum Dikirim',
        'Sedang Diproses',
        'Sudah Dikirim',
        'Diterima'
    ) NOT NULL DEFAULT 'Belum Dikirim',
    alamatPengiriman VARCHAR(255) NOT NULL,
    CONSTRAINT fk_transaksi_buyer
        FOREIGN KEY (idPembeli) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_transaksi_admin
        FOREIGN KEY (idAdmin) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TABEL DETAIL TRANSAKSI (KOMPOSIT 3NF)
CREATE TABLE detailtransaksi (
    idTransaksi INT NOT NULL,
    idBarang INT NOT NULL,
    jumlah INT NOT NULL,
    hargaSatuan DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (idTransaksi, idBarang),
    CONSTRAINT fk_detail_transaksi
        FOREIGN KEY (idTransaksi) REFERENCES transaksi(idTransaksi)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_barang
        FOREIGN KEY (idBarang) REFERENCES barang(idBarang)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. TABEL PESAN (CHAT & NEGO HARGA CLOUD MESSAGE)
CREATE TABLE pesan (
    idPesan INT AUTO_INCREMENT PRIMARY KEY,
    idPengirim INT NOT NULL,
    peranPengirim VARCHAR(20) NOT NULL,
    idPenerima INT NOT NULL,
    peranPenerima VARCHAR(20) NOT NULL,
    idBarang INT NULL,
    isiPesan TEXT NOT NULL,
    hargaNego DECIMAL(12,2) NULL,
    statusNego VARCHAR(20) NULL,
    waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    dibaca TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT fk_pesan_sender
        FOREIGN KEY (idPengirim) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pesan_receiver
        FOREIGN KEY (idPenerima) REFERENCES users(idUser)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_pesan_barang
        FOREIGN KEY (idBarang) REFERENCES barang(idBarang)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. TABEL KODEPOS (JIKA BELUM ADA)
CREATE TABLE IF NOT EXISTS kodepos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provinsi VARCHAR(100) NOT NULL,
    kabupaten VARCHAR(100) NOT NULL,
    kecamatan VARCHAR(100) NOT NULL,
    desa VARCHAR(100) NOT NULL,
    kode_pos VARCHAR(10) NOT NULL,
    INDEX idx_kp_kodepos (kode_pos),
    INDEX idx_kp_wilayah (provinsi, kabupaten, kecamatan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

