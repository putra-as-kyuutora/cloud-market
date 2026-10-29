# Flowchart - LihatLaporanTransaksiController (Admin)

```mermaid
flowchart TD
    Start([Mulai]) --> CekAdmin{Cek user<br/>adalah Admin?}
    
    CekAdmin -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekAdmin -->|Ya| Action{Action?}
    
    Action -->|View Laporan| AdaFilter{Ada filter<br/>tanggal/status?}
    
    AdaFilter -->|Tidak| QueryAll[Query semua transaksi:<br/>SELECT t.*, u.nama as pembeli,<br/>dt.*, b.nama as barang<br/>FROM Transaksi t<br/>JOIN users u ON t.id_pembeli = u.id<br/>JOIN DetailTransaksi dt ON t.id = dt.id_transaksi<br/>JOIN Barang b ON dt.id_barang = b.id<br/>ORDER BY t.created_at DESC]
    QueryAll --> ReturnAll[Return tabel transaksi<br/>dengan form filter<br/>dan tombol Export]
    ReturnAll --> End2([Selesai])
    
    AdaFilter -->|Ya| InputFilter[Terima input:<br/>tanggal_mulai optional,<br/>tanggal_akhir optional,<br/>status_filter optional]
    InputFilter --> BuildQuery[Build query:<br/>Base SELECT seperti di atas]
    BuildQuery --> AdaTanggal{Ada filter<br/>tanggal?}
    
    AdaTanggal -->|Ya| AddTanggal[Tambah WHERE:<br/>t.created_at BETWEEN<br/>tanggal_mulai AND tanggal_akhir]
    AddTanggal --> AdaStatus{Ada filter<br/>status?}
    
    AdaTanggal -->|Tidak| AdaStatus
    
    AdaStatus -->|Ya| AddStatus[Tambah WHERE:<br/>t.statusPembayaran = status_filter]
    AddStatus --> ExecQuery[Execute query]
    
    AdaStatus -->|Tidak| ExecQuery
    
    ExecQuery --> AdaHasil{Ada hasil?}
    
    AdaHasil -->|Tidak| EmptyResult[Return empty:<br/>Tidak ada transaksi<br/>dalam periode/status ini]
    EmptyResult --> End3([Selesai])
    
    AdaHasil -->|Ya| HitungTotal[Hitung total summary:<br/>- Total transaksi<br/>- Total pendapatan<br/>- Avg transaksi]
    HitungTotal --> ReturnFiltered[Return tabel dengan:<br/>- Data transaksi filtered<br/>- Summary statistics<br/>- Form filter aktif<br/>- Tombol Export]
    ReturnFiltered --> End4([Selesai])
    
    Action -->|Detail Transaksi| InputDetail[Terima input:<br/>id_transaksi]
    InputDetail --> QueryDetail[Query detail lengkap:<br/>- Data transaksi<br/>- Data pembeli<br/>- Data penjual<br/>- Detail item<br/>- Bukti pembayaran<br/>- Timeline status]
    QueryDetail --> TransaksiExist{Transaksi<br/>ditemukan?}
    
    TransaksiExist -->|Tidak| Error404[Return error 404:<br/>Transaksi tidak ditemukan]
    Error404 --> End5([Selesai])
    
    TransaksiExist -->|Ya| ReturnDetail[Return view detail dengan:<br/>- Semua info transaksi<br/>- Timeline visual<br/>- Bukti pembayaran<br/>- Detail items]
    ReturnDetail --> End6([Selesai])
    
    Action -->|Export| InputExport[Terima input:<br/>filter sama seperti view]
    InputExport --> QueryExport[Query transaksi<br/>sesuai filter]
    QueryExport --> GenerateFile[Generate file Excel/CSV:<br/>- Header kolom<br/>- Data transaksi row by row<br/>- Format tanggal & currency<br/>- Total summary di bawah<br/>- Metadata filter, tanggal export]
    GenerateFile --> SetHeaders[Set HTTP headers:<br/>Content-Type: application/vnd.ms-excel<br/>Content-Disposition: attachment;<br/>filename=laporan-tranggal.xlsx]
    SetHeaders --> SendFile[Kirim file ke browser<br/>untuk download]
    SendFile --> End7([Selesai])
```

## Penjelasan Algoritma

**Input:**
- action (string: view/detail/export)
- tanggal_mulai (date, optional untuk filter)
- tanggal_akhir (date, optional untuk filter)
- status_filter (string, optional: Menunggu Pembayaran/Sudah Dibayar/Selesai/Dibatalkan)
- id_transaksi (integer, untuk detail)

**Proses:**
1. **Cek Admin**
   - Validasi user adalah admin

2. **View Laporan**
   - Query transaksi dengan JOIN:
     - users (untuk nama pembeli)
     - DetailTransaksi (untuk item)
     - Barang (untuk nama barang)
   - Jika ada filter:
     - Filter by rentang tanggal
     - Filter by status pembayaran
   - Hitung summary statistics
   - Tampilkan tabel dengan form filter

3. **Detail Transaksi**
   - Query detail lengkap satu transaksi
   - Tampilkan dengan timeline visual

4. **Export Laporan**
   - Query transaksi sesuai filter yang sama
   - Generate file Excel/CSV dengan:
     - Header kolom
     - Data transaksi
     - Format tanggal dan currency
     - Total summary
     - Metadata (filter, tanggal export)
   - Set headers untuk download
   - Kirim file ke browser

**Output:**
- **Success:** Tabel laporan atau file download
- **Error:** Tampilkan pesan error
