# Flowchart - KonfirmasiPembayaranController (Penjual)

```mermaid
flowchart TD
    Start([Mulai]) --> CekPenjual{Cek user<br/>adalah Penjual?}
    
    CekPenjual -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekPenjual -->|Ya| Action{Action?}
    
    Action -->|List| QueryPesanan[Query pesanan:<br/>WHERE id_barang IN<br/>SELECT id FROM Barang<br/>WHERE id_penjual = user_id<br/>AND status = Menunggu Konfirmasi]
    QueryPesanan --> ReturnList[Return list pesanan<br/>dengan preview bukti]
    ReturnList --> End2([Selesai])
    
    Action -->|Detail| QueryDetail[Query detail pesanan<br/>dengan bukti pembayaran]
    QueryDetail --> ReturnDetail[Return detail dengan<br/>preview bukti besar]
    ReturnDetail --> End3([Selesai])
    
    Action -->|Approve| ValidasiOwnership{Validasi pesanan<br/>berisi barang<br/>milik penjual?}
    
    ValidasiOwnership -->|Tidak| ErrorOwnership[Return error 403:<br/>Akses ditolak]
    ErrorOwnership --> End4([Selesai])
    
    ValidasiOwnership -->|Ya| UpdateApprove[UPDATE Transaksi:<br/>SET statusPembayaran = Sudah Dibayar<br/>SET statusPengiriman = Belum Dikirim<br/>SET tgl_konfirmasi = NOW]
    UpdateApprove --> SuccessApprove[Return success:<br/>Pembayaran dikonfirmasi.<br/>Segera kirim barang]
    SuccessApprove --> End5([Selesai])
    
    Action -->|Reject| InputAlasan[Terima input:<br/>alasan_penolakan]
    InputAlasan --> ValidasiAlasan{Validasi alasan<br/>min 10 karakter?}
    
    ValidasiAlasan -->|Tidak Valid| ErrorAlasan[Return error:<br/>Alasan terlalu singkat]
    ErrorAlasan --> End6([Selesai])
    
    ValidasiAlasan -->|Valid| UpdateReject[UPDATE Transaksi:<br/>SET statusPembayaran = Ditolak<br/>SET alasan_penolakan = alasan<br/>SET tgl_ditolak = NOW]
    UpdateReject --> SuccessReject[Return success:<br/>Pembayaran ditolak]
    SuccessReject --> End7([Selesai])
```

## Penjelasan Algoritma

**Input:**
- action (string: list/detail/approve/reject)
- id_transaksi (integer, untuk detail/approve/reject)
- alasan_penolakan (string, untuk reject)

**Proses:**
1. **Cek Penjual**
   - Validasi user adalah penjual

2. **List Pesanan**
   - Query pesanan yang berisi barang milik penjual
   - Filter status = 'Menunggu Konfirmasi'

3. **Detail Pesanan**
   - Query detail dengan bukti pembayaran

4. **Approve**
   - Validasi ownership
   - Update status = 'Sudah Dibayar'
   - Update statusPengiriman = 'Belum Dikirim'

5. **Reject**
   - Validasi alasan minimal 10 karakter
   - Update status = 'Ditolak'
   - Simpan alasan

**Output:**
- **Success:** Notifikasi sesuai action
- **Error:** Tampilkan pesan error
