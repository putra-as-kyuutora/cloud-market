# Flowchart - TambahBarangController

```mermaid
flowchart TD
    Start([Mulai]) --> CekPenjual{Cek user<br/>adalah Penjual<br/>approved?}
    
    CekPenjual -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekPenjual -->|Ya| Input[Terima input:<br/>nama_barang, harga, stok,<br/>kategori_id, deskripsi,<br/>foto_barang]
    Input --> ValidasiInput{Validasi<br/>data input}
    
    ValidasiInput -->|Tidak Valid| ErrorValidasi[Return error:<br/>Field tidak valid]
    ErrorValidasi --> End2([Selesai])
    
    ValidasiInput -->|Valid| ValidasiFile{Validasi file<br/>foto format<br/>dan ukuran?}
    
    ValidasiFile -->|Tidak Valid| ErrorFile[Return error:<br/>Format/ukuran tidak valid]
    ErrorFile --> End3([Selesai])
    
    ValidasiFile -->|Valid| UploadFile[Upload file ke<br/>/uploads/barang/<br/>dengan nama random]
    UploadFile --> SaveDB[INSERT ke tabel Barang:<br/>- id_penjual<br/>- nama, harga, stok<br/>- kategori_id<br/>- deskripsi<br/>- path foto]
    SaveDB --> Success[Return success:<br/>Barang berhasil ditambahkan]
    Success --> End4([Selesai])
```

## Penjelasan Algoritma

**Input:**
- nama_barang (string)
- harga (decimal)
- stok (integer)
- kategori_id (integer)
- deskripsi (text)
- foto_barang (file)

**Proses:**
1. **Cek Status Penjual**
   - Validasi user adalah penjual approved

2. **Validasi Data Input**
   - Nama tidak kosong
   - Harga > 0
   - Stok >= 0
   - Kategori exist

3. **Validasi File Foto**
   - Format: JPG, PNG
   - Ukuran maksimal 2MB
   - MIME type valid

4. **Upload File**
   - Simpan ke /uploads/barang/
   - Generate nama random

5. **Simpan ke Database**
   - INSERT dengan id_penjual = user yang login

**Output:**
- **Success:** Notifikasi "Barang berhasil ditambahkan"
- **Error:** Tampilkan pesan error
