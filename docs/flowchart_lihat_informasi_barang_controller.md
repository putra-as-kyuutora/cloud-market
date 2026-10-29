# Flowchart - LihatInformasiBarangController (Detail Barang)

```mermaid
flowchart TD
    Start([Mulai]) --> Input[Terima input:<br/>id_barang]
    Input --> QueryBarang[Query barang by ID<br/>dengan JOIN kategori<br/>dan info penjual]
    QueryBarang --> BarangExist{Barang<br/>ditemukan?}
    
    BarangExist -->|Tidak| Error404[Return error 404:<br/>Barang tidak ditemukan]
    Error404 --> End1([Selesai])
    
    BarangExist -->|Ya| CekStok{Stok > 0?}
    
    CekStok -->|Tidak| ReturnStokHabis[Return detail barang dengan:<br/>- Semua info barang<br/>- Badge Stok Habis<br/>- Tombol Beli disabled<br/>- Tombol Nego disabled<br/>- Tombol Chat aktif]
    ReturnStokHabis --> End2([Selesai])
    
    CekStok -->|Ya| ReturnDetail[Return detail barang dengan:<br/>- Foto besar, Nama, Harga<br/>- Stok, Kategori, Deskripsi<br/>- Info penjual nama toko<br/>- Rating optional<br/>- Tombol Beli aktif<br/>- Tombol Nego Harga aktif<br/>- Tombol Chat Penjual aktif]
    ReturnDetail --> End3([Selesai])
```

## Penjelasan Algoritma

**Input:**
- id_barang (integer)

**Proses:**
1. **Query Barang**
   - SELECT barang dengan JOIN:
     - kategori (untuk nama kategori)
     - user/penjual (untuk info penjual)
   - WHERE id = id_barang

2. **Cek Keberadaan**
   - Jika tidak ditemukan → error 404

3. **Cek Stok**
   - Jika stok = 0:
     - Tampilkan dengan badge "Stok Habis"
     - Disable tombol Beli dan Nego
   - Jika stok > 0:
     - Semua tombol aktif

**Output:**
- **Success:** Halaman detail barang lengkap dengan tombol aksi
- **Stok Habis:** Detail dengan tombol Beli disabled
- **Error:** Error 404
