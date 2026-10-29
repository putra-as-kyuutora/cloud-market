# Flowchart - PesananController

```mermaid
flowchart TD
    Start([Mulai]) --> Action{Action?}
    
    Action -->|List Pembeli| QueryPembeli[Query pesanan:<br/>WHERE id_pembeli = user_id<br/>ORDER BY created_at DESC]
    QueryPembeli --> AdaPesanan1{Ada pesanan?}
    
    AdaPesanan1 -->|Tidak| EmptyPembeli[Return empty state:<br/>Belum ada pesanan]
    EmptyPembeli --> End1([Selesai])
    
    AdaPesanan1 -->|Ya| ReturnListPembeli[Return list pesanan<br/>dengan filter tabs]
    ReturnListPembeli --> End2([Selesai])
    
    Action -->|List Penjual| QueryPenjual[Query pesanan:<br/>WHERE id_barang IN<br/>SELECT id FROM Barang<br/>WHERE id_penjual = user_id]
    QueryPenjual --> AdaPesanan2{Ada pesanan?}
    
    AdaPesanan2 -->|Tidak| EmptyPenjual[Return empty state:<br/>Belum ada pesanan masuk]
    EmptyPenjual --> End3([Selesai])
    
    AdaPesanan2 -->|Ya| ReturnListPenjual[Return list pesanan masuk<br/>dengan filter tabs]
    ReturnListPenjual --> End4([Selesai])
    
    Action -->|Detail| QueryDetail[Query detail transaksi<br/>dengan item dan info penjual]
    QueryDetail --> ValidasiOwnership{Transaksi milik<br/>pembeli atau<br/>penjual?}
    
    ValidasiOwnership -->|Tidak| Error403[Return error 403:<br/>Akses ditolak]
    Error403 --> End5([Selesai])
    
    ValidasiOwnership -->|Ya| ReturnDetail[Return detail lengkap<br/>dengan tombol aksi<br/>sesuai status]
    ReturnDetail --> End6([Selesai])
    
    Action -->|Update Status| UpdateStatus[UPDATE statusPengiriman<br/>dengan nomor resi]
    UpdateStatus --> SuccessUpdate[Return success:<br/>Status diupdate]
    SuccessUpdate --> End7([Selesai])
    
    Action -->|Konfirmasi Terima| UpdateKonfirmasi[UPDATE:<br/>statusPembayaran = Selesai<br/>statusPengiriman = Diterima]
    UpdateKonfirmasi --> SuccessKonfirmasi[Return success:<br/>Pesanan diselesaikan]
    SuccessKonfirmasi --> End8([Selesai])
    
    Action -->|Batalkan| BeginTrans[BEGIN TRANSACTION]
    BeginTrans --> QueryItems[Query semua item pesanan]
    QueryItems --> LoopItems[Loop: Kembalikan stok<br/>untuk setiap item]
    LoopItems --> UpdateBatal[UPDATE Transaksi:<br/>SET statusPembayaran = Dibatalkan]
    UpdateBatal --> CommitTrans[COMMIT]
    CommitTrans --> SuccessBatal[Return success:<br/>Pesanan dibatalkan]
    SuccessBatal --> End9([Selesai])
```

## Penjelasan Algoritma

**Input:**
- action (string: list_pembeli/list_penjual/detail/update_status/konfirmasi_terima/batalkan)
- id_transaksi (integer, untuk detail dan aksi lain)
- nomor_resi (string, optional untuk update_status)

**Proses:**
1. **List Pesanan Pembeli**
   - Query pesanan milik pembeli
   - Tampilkan dengan filter tabs

2. **List Pesanan Penjual**
   - Query pesanan yang berisi barang milik penjual
   - Tampilkan dengan filter tabs

3. **Detail Pesanan**
   - Query detail lengkap
   - Validasi ownership
   - Tampilkan dengan tombol aksi sesuai status

4. **Update Status Pengiriman**
   - Update statusPengiriman = 'Dikirim'
   - Simpan nomor resi

5. **Konfirmasi Terima**
   - Update statusPembayaran = 'Selesai'
   - Update statusPengiriman = 'Diterima'

6. **Batalkan Pesanan**
   - BEGIN TRANSACTION
   - Kembalikan stok semua item
   - Update status = 'Dibatalkan'
   - COMMIT

**Output:**
- **Success:** Data atau notifikasi sesuai action
- **Error:** Tampilkan pesan error
