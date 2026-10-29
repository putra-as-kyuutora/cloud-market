# Flowchart - KelolaKategoriController (Admin)

```mermaid
flowchart TD
    Start([Mulai]) --> CekAdmin{Cek user<br/>adalah Admin?}
    
    CekAdmin -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekAdmin -->|Ya| Action{Action?}
    
    Action -->|List| QueryKategori[Query semua kategori<br/>dengan jumlah barang:<br/>SELECT k.*, COUNT b.id<br/>FROM Kategori k<br/>LEFT JOIN Barang b<br/>ON k.id = b.kategori_id<br/>GROUP BY k.id]
    QueryKategori --> ReturnList[Return list kategori dengan:<br/>- Nama kategori<br/>- Jumlah barang<br/>- Tombol Edit & Hapus]
    ReturnList --> End2([Selesai])
    
    Action -->|Tambah| InputTambah[Terima input:<br/>nama_kategori]
    InputTambah --> ValidasiNama1{Validasi nama:<br/>- Tidak kosong<br/>- Max 100 karakter<br/>- Tidak duplikat}
    
    ValidasiNama1 -->|Tidak Valid| ErrorValidasi1[Return error:<br/>Sesuai kondisi]
    ErrorValidasi1 --> End3([Selesai])
    
    ValidasiNama1 -->|Valid| InsertKategori[INSERT Kategori:<br/>- nama<br/>- created_at = NOW]
    InsertKategori --> SuccessTambah[Return success:<br/>Kategori berhasil ditambahkan]
    SuccessTambah --> End4([Selesai])
    
    Action -->|Edit| InputEdit[Terima input:<br/>id_kategori, nama_baru]
    InputEdit --> QueryKategoriEdit[Query kategori by ID]
    QueryKategoriEdit --> KategoriExist{Kategori<br/>exist?}
    
    KategoriExist -->|Tidak| Error404[Return error 404:<br/>Kategori tidak ditemukan]
    Error404 --> End5([Selesai])
    
    KategoriExist -->|Ya| ValidasiNama2{Validasi nama:<br/>- Tidak kosong<br/>- Max 100 karakter<br/>- Tidak duplikat<br/>dengan kategori lain}
    
    ValidasiNama2 -->|Tidak Valid| ErrorValidasi2[Return error:<br/>Sesuai kondisi]
    ErrorValidasi2 --> End6([Selesai])
    
    ValidasiNama2 -->|Valid| UpdateKategori[UPDATE Kategori:<br/>SET nama = nama_baru<br/>WHERE id = id_kategori]
    UpdateKategori --> SuccessEdit[Return success:<br/>Kategori berhasil diperbarui]
    SuccessEdit --> End7([Selesai])
    
    Action -->|Hapus| InputHapus[Terima input:<br/>id_kategori]
    InputHapus --> CekBarang[Query jumlah barang<br/>dalam kategori:<br/>SELECT COUNT<br/>FROM Barang<br/>WHERE kategori_id = id]
    CekBarang --> AdaBarang{Ada barang<br/>dalam kategori?}
    
    AdaBarang -->|Ya| ErrorBarang[Return error:<br/>Tidak dapat hapus.<br/>Masih ada X barang]
    ErrorBarang --> End8([Selesai])
    
    AdaBarang -->|Tidak| ReCheckBarang[Re-check jumlah barang<br/>untuk race condition]
    ReCheckBarang --> MasihKosong{Masih tidak<br/>ada barang?}
    
    MasihKosong -->|Tidak| ErrorBarangBaru[Return error:<br/>Ada barang baru masuk]
    ErrorBarangBaru --> End9([Selesai])
    
    MasihKosong -->|Ya| DeleteKategori[DELETE FROM Kategori<br/>WHERE id = id_kategori]
    DeleteKategori --> SuccessHapus[Return success:<br/>Kategori berhasil dihapus]
    SuccessHapus --> End10([Selesai])
```

## Penjelasan Algoritma

**Input:**
- action (string: list/tambah/edit/hapus)
- id_kategori (integer, untuk edit/hapus)
- nama_kategori (string, untuk tambah/edit)

**Proses:**
1. **Cek Admin**
   - Validasi user adalah admin

2. **List Kategori**
   - Query semua kategori dengan COUNT barang
   - Tampilkan dengan tombol Edit & Hapus

3. **Tambah Kategori**
   - Validasi nama:
     - Tidak kosong
     - Maksimal 100 karakter
     - Tidak duplikat
   - INSERT ke database

4. **Edit Kategori**
   - Query kategori by ID
   - Validasi nama (tidak duplikat dengan kategori lain)
   - UPDATE database

5. **Hapus Kategori**
   - Cek jumlah barang dalam kategori
   - Jika ada barang → tidak bisa hapus
   - Re-check untuk handle race condition
   - DELETE dari database

**Output:**
- **Success:** Notifikasi sesuai action
- **Error:** Tampilkan pesan error
