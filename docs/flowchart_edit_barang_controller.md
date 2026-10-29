# Flowchart - EditBarangController

```mermaid
flowchart TD
    Start([Mulai]) --> Action{Action?}
    
    Action -->|Show Form| QueryBarang[Query barang by ID<br/>dan cek ownership]
    QueryBarang --> BarangExist{Barang exist<br/>dan milik<br/>penjual?}
    
    BarangExist -->|Tidak| Error404[Return error 404:<br/>Barang tidak ditemukan]
    Error404 --> End1([Selesai])
    
    BarangExist -->|Ya| ReturnForm[Return form<br/>dengan data pre-filled]
    ReturnForm --> End2([Selesai])
    
    Action -->|Update| Input[Terima input data baru:<br/>nama, harga, stok,<br/>kategori_id, deskripsi,<br/>foto_baru optional]
    Input --> ValidasiOwnership{Validasi<br/>barang milik<br/>penjual?}
    
    ValidasiOwnership -->|Tidak| Error403[Return error 403:<br/>Akses ditolak]
    Error403 --> End3([Selesai])
    
    ValidasiOwnership -->|Ya| ValidasiInput{Validasi<br/>data input}
    
    ValidasiInput -->|Tidak Valid| ErrorValidasi[Return error:<br/>Data tidak valid]
    ErrorValidasi --> End4([Selesai])
    
    ValidasiInput -->|Valid| GantiFoto{User ganti<br/>foto?}
    
    GantiFoto -->|Ya| ValidasiFile{Validasi file<br/>foto format<br/>dan ukuran?}
    
    ValidasiFile -->|Tidak Valid| ErrorFile[Return error:<br/>File tidak valid]
    ErrorFile --> End5([Selesai])
    
    ValidasiFile -->|Valid| HapusFotoLama[Hapus foto lama<br/>dari /uploads/barang/]
    HapusFotoLama --> UploadFotoBaru[Upload foto baru ke<br/>/uploads/barang/]
    UploadFotoBaru --> UpdateWithFoto[UPDATE Barang<br/>SET data + foto baru]
    UpdateWithFoto --> Success1[Return success:<br/>Barang berhasil diperbarui]
    Success1 --> End6([Selesai])
    
    GantiFoto -->|Tidak| UpdateNoFoto[UPDATE Barang<br/>SET data tanpa ubah foto]
    UpdateNoFoto --> Success2[Return success:<br/>Barang berhasil diperbarui]
    Success2 --> End7([Selesai])
```

## Penjelasan Algoritma

**Input:**
- id_barang (integer)
- nama_barang (string, optional)
- harga (decimal, optional)
- stok (integer, optional)
- kategori_id (integer, optional)
- deskripsi (text, optional)
- foto_baru (file, optional)

**Proses:**
1. **Show Form (GET)**
   - Query barang by ID
   - Validasi ownership (barang milik penjual yang login)
   - Tampilkan form pre-filled

2. **Update (POST)**
   - Validasi ownership
   - Validasi data input
   - Jika ganti foto:
     - Validasi file
     - Hapus foto lama
     - Upload foto baru
   - Update data ke database

**Output:**
- **Success:** Notifikasi "Barang berhasil diperbarui"
- **Error:** Tampilkan pesan error
