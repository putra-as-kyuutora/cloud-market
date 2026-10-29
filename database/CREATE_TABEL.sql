CREATE DATABASE cloud_market;
USE cloud_market;


-- 1. TABEL ADMIN
CREATE TABLE Admin (
    idAdmin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL
);


-- 2. TABEL PENJUAL
CREATE TABLE Penjual (
    idPenjual INT AUTO_INCREMENT PRIMARY KEY,
    idAdmin INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    noHp VARCHAR(20) NOT NULL,
    namaToko VARCHAR(100) NOT NULL,
    deskripsiToko TEXT,
    verified TINYINT(1) NOT NULL DEFAULT 0,
    aktif TINYINT(1) NOT NULL DEFAULT 1,

    CONSTRAINT fk_penjual_admin
        FOREIGN KEY (idAdmin)
        REFERENCES Admin(idAdmin)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- 3. TABEL PEMBELI
CREATE TABLE Pembeli (
    idPembeli INT AUTO_INCREMENT PRIMARY KEY,
    idAdmin INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    noHp VARCHAR(20) NOT NULL,

    CONSTRAINT fk_pembeli_admin
        FOREIGN KEY (idAdmin)
        REFERENCES Admin(idAdmin)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- 4. TABEL KATEGORI
CREATE TABLE Kategori (
    idKategori INT AUTO_INCREMENT PRIMARY KEY,
    idAdmin INT NOT NULL,
    namaKategori VARCHAR(100) NOT NULL,

    CONSTRAINT fk_kategori_admin
        FOREIGN KEY (idAdmin)
        REFERENCES Admin(idAdmin)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- 5. TABEL BARANG
CREATE TABLE Barang (
    idBarang INT AUTO_INCREMENT PRIMARY KEY,
    idPenjual INT NOT NULL,
    idKategori INT NOT NULL,
    namaBarang VARCHAR(150) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT,
    gambar VARCHAR(255),

    CONSTRAINT fk_barang_penjual
        FOREIGN KEY (idPenjual)
        REFERENCES Penjual(idPenjual)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_barang_kategori
        FOREIGN KEY (idKategori)
        REFERENCES Kategori(idKategori)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- 6. TABEL TRANSAKSI
CREATE TABLE Transaksi (
    idTransaksi INT AUTO_INCREMENT PRIMARY KEY,
    idPembeli INT NOT NULL,
    idAdmin INT NOT NULL,
    tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    metodePembayaran VARCHAR(50) NOT NULL,
    buktiPembayaran VARCHAR(255),

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

    CONSTRAINT fk_transaksi_pembeli
        FOREIGN KEY (idPembeli)
        REFERENCES Pembeli(idPembeli)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_transaksi_admin
        FOREIGN KEY (idAdmin)
        REFERENCES Admin(idAdmin)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- 7. TABEL DETAIL TRANSAKSI
CREATE TABLE DetailTransaksi (
    idTransaksi INT NOT NULL,
    idBarang INT NOT NULL,
    jumlah INT NOT NULL,
    hargaSatuan DECIMAL(12,2) NOT NULL,

    PRIMARY KEY (idTransaksi, idBarang),

    CONSTRAINT fk_detail_transaksi
        FOREIGN KEY (idTransaksi)
        REFERENCES Transaksi(idTransaksi)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_detail_barang
        FOREIGN KEY (idBarang)
        REFERENCES Barang(idBarang)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);