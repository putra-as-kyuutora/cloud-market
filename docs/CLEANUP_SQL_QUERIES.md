# Panduan Cleanup SQL Queries dari Diagram

Untuk membersihkan semua query SQL dari diagram aktivitas, ganti pattern berikut:

## Pattern yang perlu diganti:

1. **SELECT statements**
   - Ganti: `SELECT ... FROM ... WHERE ...;`
   - Dengan: `Query data dari database;` atau `Ambil data dari database;`

2. **UPDATE statements**
   - Ganti: `UPDATE ... SET ... WHERE ...;`
   - Dengan: `Update data di database;` atau `Perbarui data;`

3. **INSERT statements**
   - Ganti: `INSERT INTO ... VALUES ...;`
   - Dengan: `Simpan data ke database;` atau `Insert data baru;`

4. **DELETE statements**
   - Ganti: `DELETE FROM ... WHERE ...;`
   - Dengan: `Hapus data dari database;`

## Contoh Before/After:

### Before:
```
:SELECT * FROM users WHERE idUser = :id;
:UPDATE users SET nama = :nama WHERE idUser = :id;
:INSERT INTO Kategori (namaKategori) VALUES (:nama);
:DELETE FROM Barang WHERE idBarang = :id;
```

### After:
```
:Ambil data user dari database;
:Update data user di database;
:Simpan kategori baru ke database;
:Hapus barang dari database;
```

## File yang sudah di-cleanup:
- activity_diagram_login.puml ✅
- activity_diagram_registrasi.puml ✅
- activity_diagram_cari_barang.puml ✅
- activity_diagram_lihat_detail_barang.puml ✅
- activity_diagram_edit_data_pribadi.puml ✅
- activity_diagram_edit_barang.puml ✅
- activity_diagram_batalkan_pesanan.puml ✅
- activity_diagram_upload_bukti_bayar.puml ✅

## File yang masih perlu cleanup:
Cek dengan command:
```powershell
Get-ChildItem "*.puml" | Select-String -Pattern "SELECT|UPDATE|INSERT|DELETE" | Select-Object Filename -Unique
```
