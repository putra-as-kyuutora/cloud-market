-- =====================================================================
-- CLOUD MARKET - SEED DATA AWAL BERSIH (INITIAL SEEDER)
-- Password untuk semua akun default:
--   admin   -> admin123
--   penjual -> penjual123
--   pembeli -> pembeli123
--   putra   -> penjual123
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. SEED USERS
-- Password bcrypt hashes:
-- admin123   : $2y$12$PGq2oQI1kC.bJZU6Di0kUeD6UEGTFedZMXz6KxJfq2EqkMVqyh5wW
-- penjual123 : $2y$12$kvR/l/gXx8TRwdrpAuyQ3uLtRHV9Ck4IxpU8mw3Atzr8zdsGfVrBa
-- pembeli123 : $2y$12$eR9sqeRwalCAbIlbJkjrxumYGpsENtlPewgCVCVBRpW8VVpTItjIS

INSERT INTO users (idUser, username, email, password, nama, alamat, noHp, is_buyer, is_seller, is_admin, aktif, created_at) VALUES
(1, 'admin', 'admin@cloudmarket.id', '$2y$12$PGq2oQI1kC.bJZU6Di0kUeD6UEGTFedZMXz6KxJfq2EqkMVqyh5wW', 'Administrator', 'Kantor Pusat Cloud Market, Jakarta', '08110000001', 0, 0, 1, 1, NOW()),
(2, 'penjual', 'penjual@cloudmarket.id', '$2y$12$kvR/l/gXx8TRwdrpAuyQ3uLtRHV9Ck4IxpU8mw3Atzr8zdsGfVrBa', 'Budi Santoso', 'Jl. Merdeka No. 45, Bandung', '081234567890', 1, 1, 0, 1, NOW()),
(3, 'pembeli', 'pembeli@cloudmarket.id', '$2y$12$eR9sqeRwalCAbIlbJkjrxumYGpsENtlPewgCVCVBRpW8VVpTItjIS', 'Siti Rahma', 'Jl. Sudirman No. 12, Surabaya', '085678901234', 1, 0, 0, 1, NOW()),
(4, 'putra', 'putra@cloudmarket.id', '$2y$12$kvR/l/gXx8TRwdrpAuyQ3uLtRHV9Ck4IxpU8mw3Atzr8zdsGfVrBa', 'Putra Pratama', 'Jl. Malioboro No. 88, Yogyakarta', '087890123456', 1, 1, 0, 1, NOW());

-- 2. SEED SELLER_PROFILES (PROFIL TOKO & VERIFIKASI)
INSERT INTO seller_profiles (idUser, namaToko, deskripsiToko, noKtp, statusVerifikasi, tglVerifikasi, diverifikasiOleh, namaBank, noRekening, atasNamaRekening, created_at) VALUES
(2, 'Berkah Second Store', 'Menjual aneka barang bekas berkualitas, original, dan bergaransi personal.', '3273012345670001', 'approved', NOW(), 1, 'BCA', '1234567890', 'Budi Santoso', NOW()),
(4, 'Mewing Vintage Store', 'Pusat barang hobi, gadget second mulus, dan barang koleksi antik.', '3471012345670002', 'approved', NOW(), 1, 'Mandiri', '9876543210', 'Putra Pratama', NOW());

-- 3. SEED KATEGORI
INSERT INTO kategori (idKategori, idAdmin, namaKategori, deskripsi) VALUES
(1, 1, 'Alat Tulis', 'Buku, alat tulis kantor, dan perlengkapan sekolah bekas layak pakai.'),
(2, 1, 'Fashion', 'Pakaian pria, wanita, sepatu, tas, dan aksesoris vintage & preloved.'),
(3, 1, 'Gadget', 'Handphone, laptop, tablet, jam tangan pintar, dan aksesoris elektronik.'),
(4, 1, 'Hobi', 'Action figure, mainan koleksi, alat musik, game console, dan kamera.'),
(5, 1, 'Kendaraan', 'Sepeda motor, sparepart, helm, dan aksesoris otomotif.'),
(6, 1, 'Olahraga', 'Sepeda, raket, jersey, dumbbell, dan perlengkapan olahraga luar ruangan.'),
(7, 1, 'Rumah Tangga', 'Perabotan rumah, dekorasi, alat masak, dan elektronik rumah tangga.');

-- 4. SEED BARANG (PRODUK CONTOH)
INSERT INTO barang (idBarang, idPenjual, idKategori, namaBarang, harga, stok, deskripsi, gambar) VALUES
(1, 2, 4, 'Figma Anime Action Figure Original', 450000.00, 3, 'Kondisi 95% mulus, box lengkap, barang koleksi pribadi terawat tanpa lecet.', NULL),
(2, 2, 2, 'Jaket Denim Vintage Levis Second', 275000.00, 5, 'Ukuran L, bahan tebal premium, warna masih pekat 90%, kancing komplit.', NULL),
(3, 4, 3, 'iPhone 11 128GB Black Mulus', 4850000.00, 2, 'Kondisi 92% pemakaian wajar, battery health 84%, fullset dus box original.', NULL),
(4, 4, 6, 'Sepeda Lipat Polygon Urbano 3', 2200000.00, 1, 'Jarang dipakai, rem pakem, lipatan kokoh, siap gowes santai.', NULL),
(5, 2, 7, 'Mesin Kopi Espresso Manual Portable', 350000.00, 4, 'Alat pembuat espresso manual praktis untuk pecinta kopi, ekstraksi mantap.', NULL);

-- 5. SEED TRANSAKSI SAMPEL
INSERT INTO transaksi (idTransaksi, idPembeli, idAdmin, tanggal, metodePembayaran, buktiPembayaran, statusPembayaran, statusPengiriman, alamatPengiriman) VALUES
(1, 3, 1, NOW() - INTERVAL 2 DAY, 'Transfer Bank', NULL, 'Sudah Dibayar', 'Sudah Dikirim', 'Jl. Sudirman No. 12, Surabaya, Jawa Timur 60271'),
(2, 2, 1, NOW() - INTERVAL 1 DAY, 'QRIS', NULL, 'Menunggu Konfirmasi', 'Sedang Diproses', 'Jl. Merdeka No. 45, Bandung, Jawa Barat 40115');

-- 6. SEED DETAIL TRANSAKSI
INSERT INTO detailtransaksi (idTransaksi, idBarang, jumlah, hargaSatuan) VALUES
(1, 1, 1, 450000.00),
(2, 3, 1, 4850000.00);

-- 7. SEED PESAN & NEGO (CLOUD MESSAGE)
INSERT INTO pesan (idPesan, idPengirim, peranPengirim, idPenerima, peranPenerima, idBarang, isiPesan, hargaNego, statusNego, waktu, dibaca) VALUES
(1, 3, 'pembeli', 2, 'penjual', 1, 'Halo kak, apakah Figma Action Figure masih ada? Bisa nego tipis kah?', 400000.00, 'pending', NOW() - INTERVAL 3 HOUR, 0);

SET FOREIGN_KEY_CHECKS = 1;

