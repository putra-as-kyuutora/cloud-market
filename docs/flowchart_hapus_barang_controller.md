# Flowchart - HapusBarangController

```mermaid
flowchart TD
    Start([Mulai]) --> QueryBarang[Query barang by ID<br/>dan cek ownership]
    QueryBarang --> BarangExist{Barang exist<br/>dan milik<br/>penjual?}
    
    BarangExist -->|Tidak| Error404[Return error 404:<br/>Barang tidak ditemukan]
    Error404 --> End1([Selesai])
    
    BarangExist -->|Ya| CekTransaksi[Query transaksi aktif<br/>untuk barang ini:<br/>WHERE id_barang = id<br/>AND status != Selesai<br/>AND status != Dibatalkan]
    CekTransaksi --> AdaTransaksi{Ada transaksi<br/>aktif?}
    
    AdaTransaksi -->|Ya| ErrorTransaksi[Return error:<br/>Tidak dapat hapus.<br/>Masih ada X pesanan aktif]
    ErrorTransaksi --> End2([Selesai])
    
    AdaTransaksi -->|Tidak| Action{Action?}
    
    Action -->|Show Konfirmasi| ReturnKonfirmasi[Return halaman konfirmasi:<br/>Yakin hapus barang?]
    ReturnKonfirmasi --> End3([Selesai])
    
    Action -->|Hapus| HapusFoto[Hapus foto barang<br/>dari /uploads/barang/]
    HapusFoto --> DeleteDB[DELETE FROM Barang<br/>WHERE id = id_barang]
    DeleteDB --> Success[Return success:<br/>Barang berhasil dihapus]
    Success --> End4([Selesai])
```

## Penjelasan Algoritma

**Input:**
- id_barang (integer)

**Proses:**
1. **Query Barang**
   - Cari barang by ID
   - Validasi ownership (milik penjual yang login)

2. **Cek Transaksi Aktif**
   - Query transaksi yang belum selesai/dibatalkan
   - Jika ada → tidak bisa hapus

3. **Show Konfirmasi (GET)**
   - Tampilkan halaman konfirmasi

4. **Hapus (POST)**
   - Hapus file foto dari server
   - DELETE record dari database

**Output:**
- **Success:** Notifikasi "Barang berhasil dihapus"
- **Error:** Tampilkan pesan error
