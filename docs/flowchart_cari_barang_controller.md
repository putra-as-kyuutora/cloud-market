# Flowchart - CariBarangController

```mermaid
flowchart TD
    Start([Mulai]) --> Input[Terima input:<br/>keyword pencarian optional,<br/>filter kategori optional,<br/>filter harga optional,<br/>sort by optional]
    Input --> BuildQuery[Build query:<br/>SELECT * FROM Barang<br/>WHERE stok > 0]
    BuildQuery --> AdaKeyword{Ada keyword<br/>pencarian?}
    
    AdaKeyword -->|Ya| AddKeyword[Tambah WHERE:<br/>nama LIKE '%keyword%'<br/>OR deskripsi LIKE '%keyword%']
    AddKeyword --> AdaKategori{Ada filter<br/>kategori?}
    
    AdaKeyword -->|Tidak| AdaKategori
    
    AdaKategori -->|Ya| AddKategori[Tambah WHERE:<br/>kategori_id = filter]
    AddKategori --> AdaHarga{Ada filter<br/>harga?}
    
    AdaKategori -->|Tidak| AdaHarga
    
    AdaHarga -->|Ya| AddHarga[Tambah WHERE:<br/>harga BETWEEN min AND max]
    AddHarga --> AdaSort{Ada sorting?}
    
    AdaHarga -->|Tidak| AdaSort
    
    AdaSort -->|Ya| AddSort[Tambah ORDER BY:<br/>- harga ASC/DESC<br/>- created_at DESC<br/>- nama ASC]
    AddSort --> ExecQuery[Execute query]
    
    AdaSort -->|Tidak| DefaultSort[Tambah ORDER BY:<br/>created_at DESC default]
    DefaultSort --> ExecQuery
    
    ExecQuery --> AdaHasil{Ada hasil?}
    
    AdaHasil -->|Tidak| EmptyResult[Return empty:<br/>Tidak ada barang<br/>yang sesuai]
    EmptyResult --> End1([Selesai])
    
    AdaHasil -->|Ya| ReturnResult[Return grid barang:<br/>- Foto, Nama, Harga<br/>- Rating, Toko<br/>- Tombol lihat detail]
    ReturnResult --> End2([Selesai])
```

## Penjelasan Algoritma

**Input:**
- keyword (string, optional)
- kategori_id (integer, optional)
- harga_min (decimal, optional)
- harga_max (decimal, optional)
- sort_by (string: harga_asc/harga_desc/terbaru/nama)

**Proses:**
1. **Build Query**
   - Base query: SELECT barang WHERE stok > 0

2. **Filter Keyword**
   - Jika ada keyword → cari di nama atau deskripsi

3. **Filter Kategori**
   - Jika ada kategori_id → filter by kategori

4. **Filter Harga**
   - Jika ada range harga → filter BETWEEN min AND max

5. **Sorting**
   - Default: created_at DESC (terbaru)
   - Optional: harga ASC/DESC, nama ASC

6. **Execute & Return**
   - Jika ada hasil → tampilkan grid
   - Jika kosong → tampilkan empty state

**Output:**
- **Success:** Grid barang hasil pencarian
- **Empty:** Pesan "Tidak ada barang yang sesuai"
