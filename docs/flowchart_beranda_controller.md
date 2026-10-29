# Flowchart - BerandaController

```mermaid
flowchart TD
    Start([Mulai]) --> QueryBarang[Query 8 barang terbaru:<br/>SELECT * FROM Barang<br/>WHERE stok > 0<br/>ORDER BY created_at DESC<br/>LIMIT 8]
    QueryBarang --> QueryKategori[Query semua kategori:<br/>SELECT * FROM Kategori<br/>ORDER BY nama ASC]
    QueryKategori --> CekLogin{User sudah<br/>login?}
    
    CekLogin -->|Tidak| ReturnGuest[Return view beranda dengan:<br/>- Hero banner<br/>- Form pencarian<br/>- Grid kategori<br/>- Grid barang terbaru<br/>- Navbar guest Login/Registrasi]
    ReturnGuest --> End1([Selesai])
    
    CekLogin -->|Ya| CekRole{Role user?}
    
    CekRole -->|Pembeli| ReturnPembeli[Return view dengan navbar:<br/>- Cari Barang<br/>- Pesanan Saya<br/>- Pesan<br/>- Profil]
    ReturnPembeli --> End2([Selesai])
    
    CekRole -->|Penjual| ReturnPenjual[Return view dengan navbar:<br/>- Dashboard Penjual<br/>- Barang Saya<br/>- Pesanan Masuk<br/>- Pesan<br/>- Profil]
    ReturnPenjual --> End3([Selesai])
    
    CekRole -->|Admin| ReturnAdmin[Return view dengan navbar:<br/>- Dashboard Admin<br/>- Kelola Pengguna<br/>- Kelola Kategori<br/>- Laporan<br/>- Profil]
    ReturnAdmin --> End4([Selesai])
```

## Penjelasan Algoritma

**Input:**
- (tidak ada input, hanya load data)

**Proses:**
1. **Query Barang Terbaru**
   - SELECT 8 barang dengan stok > 0
   - ORDER BY created_at DESC
   - Ambil foto, nama, harga

2. **Query Kategori**
   - SELECT semua kategori aktif
   - ORDER BY nama ASC

3. **Cek Status Login**
   - Jika tidak login → navbar guest
   - Jika login → navbar sesuai role

4. **Render View**
   - Hero banner
   - Form pencarian quick
   - Grid kategori (clickable)
   - Grid barang terbaru
   - Call to action buttons

**Output:**
- **Halaman beranda** dengan konten sesuai status login dan role user
