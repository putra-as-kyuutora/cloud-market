# Flowchart - DashboardAdminController

```mermaid
flowchart TD
    Start([Mulai]) --> CekAdmin{Cek user<br/>adalah Admin?}
    
    CekAdmin -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekAdmin -->|Ya| QueryStatPengguna[Query statistik pengguna:<br/>- Total pengguna<br/>SELECT COUNT FROM users<br/>- Total penjual aktif<br/>SELECT COUNT FROM users<br/>WHERE is_seller = 1]
    
    QueryStatPengguna --> QueryStatPenjualPending[Query penjual pending KYC:<br/>SELECT COUNT<br/>FROM seller_profiles<br/>WHERE status = pending]
    
    QueryStatPenjualPending --> QueryStatBarang[Query statistik barang:<br/>SELECT COUNT<br/>FROM Barang<br/>WHERE stok > 0]
    
    QueryStatBarang --> QueryStatTransaksi[Query statistik transaksi:<br/>- Transaksi Selesai<br/>SELECT COUNT<br/>WHERE statusPembayaran = Selesai<br/>- Transaksi Menunggu<br/>SELECT COUNT<br/>WHERE statusPembayaran<br/>IN Menunggu Pembayaran,<br/>Menunggu Konfirmasi]
    
    QueryStatTransaksi --> QueryStatKategori[Query jumlah kategori:<br/>SELECT COUNT<br/>FROM Kategori]
    
    QueryStatKategori --> QueryAktivitas[Query aktivitas terbaru:<br/>- 5 transaksi terbaru<br/>SELECT * FROM Transaksi<br/>ORDER BY created_at DESC<br/>LIMIT 5<br/>- 5 penjual baru daftar<br/>SELECT * FROM seller_profiles<br/>ORDER BY created_at DESC<br/>LIMIT 5]
    
    QueryAktivitas --> AggregateData[Agregasi semua data:<br/>- Stats cards<br/>- Chart data optional<br/>- Tabel aktivitas]
    
    AggregateData --> ReturnDashboard[Return view dashboard dengan:<br/>- Card Total Pengguna<br/>- Card Total Penjual Aktif<br/>- Card Penjual Pending KYC<br/>- Card Total Barang Aktif<br/>- Card Transaksi Selesai<br/>- Card Transaksi Menunggu<br/>- Card Total Kategori<br/>- Tabel transaksi terbaru<br/>- Tabel penjual baru<br/>- Quick actions menu]
    
    ReturnDashboard --> End2([Selesai])
```

## Penjelasan Algoritma

**Input:**
- (tidak ada input, hanya load data statistik)

**Proses:**
1. **Cek Admin**
   - Validasi user adalah admin

2. **Query Statistik Pengguna**
   - Total pengguna: COUNT dari tabel users
   - Total penjual aktif: COUNT WHERE is_seller = 1

3. **Query Penjual Pending KYC**
   - COUNT dari seller_profiles WHERE status = 'pending'

4. **Query Statistik Barang**
   - Total barang aktif: COUNT WHERE stok > 0

5. **Query Statistik Transaksi**
   - Transaksi selesai: COUNT WHERE statusPembayaran = 'Selesai'
   - Transaksi menunggu: COUNT WHERE status IN ('Menunggu Pembayaran', 'Menunggu Konfirmasi')

6. **Query Jumlah Kategori**
   - COUNT dari tabel Kategori

7. **Query Aktivitas Terbaru**
   - 5 transaksi terbaru (ORDER BY created_at DESC LIMIT 5)
   - 5 penjual baru daftar (ORDER BY created_at DESC LIMIT 5)

8. **Agregasi & Render**
   - Gabungkan semua data
   - Render view dashboard dengan stats cards dan tabel

**Output:**
- **Success:** Dashboard admin dengan semua statistik dan aktivitas terbaru
- **Error:** Error 403 jika bukan admin
