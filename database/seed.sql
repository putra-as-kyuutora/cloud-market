-- Data awal. Jalankan SETELAH tabel dibuat (CREATE_TABEL.sql).
USE cloud_market;

-- Admin awal. Username: admin | Password: admin123  (SEGERA GANTI lewat menu Profil!)
INSERT INTO Admin (username, password, nama) VALUES
 ('admin', '$2y$10$nRpz0ql52bCZ59AFCvyhP.ydFCqc9w90bpssta/H7SNk9sVZSvTv6', 'Administrator');

-- Kategori sesuai desain UI
INSERT INTO Kategori (idAdmin, namaKategori)
SELECT MIN(idAdmin), n FROM Admin, (
  SELECT 'Fashion' n UNION SELECT 'Hobi' UNION SELECT 'Gadget' UNION SELECT 'Olahraga'
  UNION SELECT 'Rumah Tangga' UNION SELECT 'Alat Tulis' UNION SELECT 'Kendaraan'
) x GROUP BY n;
