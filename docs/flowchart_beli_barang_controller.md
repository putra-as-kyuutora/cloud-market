# Flowchart - BeliBarangController (Checkout)

```mermaid
flowchart TD
    Start([Mulai]) --> Input[Terima input:<br/>id_barang, jumlah,<br/>metode_pembayaran]
    Input --> QueryBarang[Query barang by ID]
    QueryBarang --> BarangExist{Barang<br/>ditemukan?}
    
    BarangExist -->|Tidak| Error404[Return error 404:<br/>Barang tidak ditemukan]
    Error404 --> End1([Selesai])
    
    BarangExist -->|Ya| CekStok{Stok tersedia?}
    
    CekStok -->|Tidak| ErrorStok[Return error:<br/>Stok tidak tersedia]
    ErrorStok --> End2([Selesai])
    
    CekStok -->|Ya| ValidasiJumlah{Jumlah <= stok?}
    
    ValidasiJumlah -->|Tidak| ErrorJumlah[Return error:<br/>Jumlah melebihi stok]
    ErrorJumlah --> End3([Selesai])
    
    ValidasiJumlah -->|Ya| BeginTrans[BEGIN TRANSACTION]
    BeginTrans --> CekStokLagi[Re-check stok barang<br/>untuk race condition]
    CekStokLagi --> StokCukup{Stok masih<br/>cukup?}
    
    StokCukup -->|Tidak| Rollback[ROLLBACK]
    Rollback --> ErrorStokHabis[Return error:<br/>Stok habis]
    ErrorStokHabis --> End4([Selesai])
    
    StokCukup -->|Ya| HitungTotal[Hitung total bayar:<br/>harga x jumlah]
    HitungTotal --> KurangiStok[UPDATE Barang<br/>SET stok = stok - jumlah]
    KurangiStok --> InsertTransaksi[INSERT Transaksi:<br/>- id_pembeli<br/>- total_bayar<br/>- metode_pembayaran<br/>- status = Menunggu Pembayaran<br/>- statusPengiriman = Belum Bayar]
    InsertTransaksi --> InsertDetail[INSERT DetailTransaksi:<br/>- id_transaksi<br/>- id_barang, jumlah<br/>- harga_satuan]
    InsertDetail --> Commit[COMMIT]
    Commit --> Success[Return success:<br/>Redirect ke halaman<br/>pembayaran]
    Success --> End5([Selesai])
```

## Penjelasan Algoritma

**Input:**
- id_barang (integer)
- jumlah (integer)
- metode_pembayaran (string)

**Proses:**
1. **Query Barang**
   - Cari barang by ID
   - Cek keberadaan

2. **Validasi Stok**
   - Cek stok > 0
   - Cek jumlah <= stok

3. **BEGIN TRANSACTION**
   - Re-check stok (handle race condition)
   - Hitung total bayar
   - Kurangi stok barang
   - INSERT transaksi dengan status 'Menunggu Pembayaran'
   - INSERT detail transaksi
   - COMMIT

4. **ROLLBACK jika stok habis**
   - Untuk mencegah overselling

**Output:**
- **Success:** Redirect ke halaman pembayaran
- **Error:** Tampilkan pesan error
