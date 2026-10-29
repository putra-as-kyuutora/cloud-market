# Flowchart - PembayaranController (Upload Bukti Bayar)

```mermaid
flowchart TD
    Start([Mulai]) --> Input[Terima input:<br/>id_transaksi, foto_bukti]
    Input --> QueryTransaksi[Query transaksi by ID<br/>dan cek ownership]
    QueryTransaksi --> TransaksiExist{Transaksi exist<br/>dan milik<br/>pembeli?}
    
    TransaksiExist -->|Tidak| Error404[Return error 404:<br/>Transaksi tidak ditemukan]
    Error404 --> End1([Selesai])
    
    TransaksiExist -->|Ya| CekStatus{Status valid<br/>untuk upload?<br/>Menunggu Pembayaran<br/>atau Ditolak}
    
    CekStatus -->|Tidak| ErrorStatus[Return error:<br/>Tidak dapat upload.<br/>Status: status]
    ErrorStatus --> End2([Selesai])
    
    CekStatus -->|Ya| ValidasiFile{Validasi file<br/>foto format<br/>dan ukuran?}
    
    ValidasiFile -->|Tidak Valid| ErrorFile[Return error:<br/>Format harus JPG/PNG<br/>maksimal 2MB]
    ErrorFile --> End3([Selesai])
    
    ValidasiFile -->|Valid| UploadFile[Upload file ke<br/>/uploads/bukti/<br/>dengan nama random]
    UploadFile --> UpdateTransaksi[UPDATE Transaksi:<br/>SET statusPembayaran = Menunggu Konfirmasi<br/>SET bukti_bayar = path<br/>SET tgl_upload = NOW]
    UpdateTransaksi --> Success[Return success:<br/>Bukti berhasil dikirim.<br/>Menunggu konfirmasi penjual]
    Success --> End4([Selesai])
```

## Penjelasan Algoritma

**Input:**
- id_transaksi (integer)
- foto_bukti (file)

**Proses:**
1. **Query Transaksi**
   - Cari transaksi by ID
   - Validasi ownership (milik pembeli yang login)

2. **Cek Status**
   - Status harus 'Menunggu Pembayaran' atau 'Ditolak'
   - Jika status lain → tidak bisa upload

3. **Validasi File**
   - Format: JPG, PNG
   - Ukuran maksimal 2MB
   - MIME type valid

4. **Upload & Update**
   - Simpan file ke /uploads/bukti/
   - Update status menjadi 'Menunggu Konfirmasi'
   - Simpan path bukti dan tanggal upload

**Output:**
- **Success:** Notifikasi "Bukti berhasil dikirim"
- **Error:** Tampilkan pesan error
