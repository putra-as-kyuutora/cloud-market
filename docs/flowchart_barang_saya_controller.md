# Flowchart - BarangSayaController

```mermaid
flowchart TD
    Start([Mulai]) --> CekPenjual{Cek user<br/>adalah Penjual<br/>approved?}
    
    CekPenjual -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekPenjual -->|Ya| QueryBarang[Query barang:<br/>WHERE id_penjual = user_id<br/>ORDER BY created_at DESC]
    QueryBarang --> AdaBarang{Ada barang?}
    
    AdaBarang -->|Tidak| EmptyState[Return empty state:<br/>Belum ada barang.<br/>Tambahkan barang pertama]
    EmptyState --> End2([Selesai])
    
    AdaBarang -->|Ya| ReturnList[Return list barang dengan:<br/>- Foto thumbnail<br/>- Nama, Harga, Stok<br/>- Status aktif/habis<br/>- Tombol Edit & Hapus]
    ReturnList --> End3([Selesai])
```

## Penjelasan Algoritma

**Input:**
- (tidak ada input, hanya cek session)

**Proses:**
1. **Cek Status Penjual**
   - Validasi user adalah penjual approved
   - Cek is_seller = 1

2. **Query Barang**
   - Ambil semua barang milik penjual yang login
   - ORDER BY created_at DESC (terbaru dulu)

3. **Tampilkan List**
   - Jika tidak ada → empty state
   - Jika ada → tampilkan dengan tombol aksi

**Output:**
- **Success:** Tampilkan list barang dengan tombol Edit & Hapus
- **Empty:** Tampilkan empty state dengan link tambah barang
- **Error:** Error 403 jika bukan penjual approved
