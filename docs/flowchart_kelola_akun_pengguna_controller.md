# Flowchart - KelolaAkunPenggunaController (Admin)

```mermaid
flowchart TD
    Start([Mulai]) --> CekAdmin{Cek user<br/>adalah Admin?}
    
    CekAdmin -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekAdmin -->|Ya| Action{Action?}
    
    Action -->|List| AdaFilter{Ada filter<br/>pencarian?}
    
    AdaFilter -->|Tidak| QueryAll[Query semua pengguna:<br/>SELECT * FROM users<br/>WHERE is_admin = 0<br/>ORDER BY created_at DESC]
    QueryAll --> ReturnAll[Return list semua pengguna]
    ReturnAll --> End2([Selesai])
    
    AdaFilter -->|Ya| InputFilter[Terima input:<br/>keyword, role_filter]
    InputFilter --> BuildQuery[Build query dengan filter:<br/>WHERE is_admin = 0<br/>AND nama/email LIKE %keyword%]
    BuildQuery --> AdaRoleFilter{Ada filter<br/>role?}
    
    AdaRoleFilter -->|Ya| AddRoleFilter[Tambah WHERE:<br/>AND is_seller = 1<br/>atau kondisi role lain]
    AddRoleFilter --> ExecQuery[Execute query]
    
    AdaRoleFilter -->|Tidak| ExecQuery
    
    ExecQuery --> AdaHasil{Ada hasil?}
    
    AdaHasil -->|Tidak| EmptyResult[Return empty:<br/>Tidak ada pengguna<br/>yang sesuai]
    EmptyResult --> End3([Selesai])
    
    AdaHasil -->|Ya| ReturnFiltered[Return list pengguna<br/>hasil filter]
    ReturnFiltered --> End4([Selesai])
    
    Action -->|Detail| InputDetail[Terima input:<br/>id_user]
    InputDetail --> QueryUser[Query user by ID<br/>dengan detail lengkap]
    QueryUser --> UserExist{User<br/>ditemukan?}
    
    UserExist -->|Tidak| Error404[Return error 404:<br/>User tidak ditemukan]
    Error404 --> End5([Selesai])
    
    UserExist -->|Ya| ReturnDetail[Return detail user:<br/>- Info pribadi<br/>- Status akun<br/>- Role<br/>- Riwayat transaksi<br/>- Tombol aksi]
    ReturnDetail --> End6([Selesai])
    
    Action -->|Nonaktifkan| InputNonaktif[Terima input:<br/>id_user]
    InputNonaktif --> ValidasiNonaktif{Validasi user<br/>bukan admin?}
    
    ValidasiNonaktif -->|Admin| ErrorNonaktif[Return error:<br/>Tidak dapat nonaktifkan admin]
    ErrorNonaktif --> End7([Selesai])
    
    ValidasiNonaktif -->|Bukan Admin| UpdateNonaktif[UPDATE users:<br/>SET is_active = 0<br/>WHERE id = id_user]
    UpdateNonaktif --> SuccessNonaktif[Return success:<br/>Akun berhasil dinonaktifkan]
    SuccessNonaktif --> End8([Selesai])
    
    Action -->|Aktifkan| InputAktif[Terima input:<br/>id_user]
    InputAktif --> UpdateAktif[UPDATE users:<br/>SET is_active = 1<br/>WHERE id = id_user]
    UpdateAktif --> SuccessAktif[Return success:<br/>Akun berhasil diaktifkan]
    SuccessAktif --> End9([Selesai])
```

## Penjelasan Algoritma

**Input:**
- action (string: list/detail/nonaktifkan/aktifkan)
- id_user (integer, untuk detail/nonaktifkan/aktifkan)
- keyword (string, optional untuk filter)
- role_filter (string, optional: pembeli/penjual/semua)

**Proses:**
1. **Cek Admin**
   - Validasi user adalah admin

2. **List Pengguna**
   - Jika tidak ada filter → query semua
   - Jika ada filter:
     - Filter by keyword (nama/email)
     - Filter by role (is_seller)
   - Exclude admin dari list

3. **Detail User**
   - Query user by ID
   - Tampilkan detail lengkap dengan riwayat

4. **Nonaktifkan Akun**
   - Validasi user bukan admin
   - UPDATE is_active = 0
   - User tidak bisa login

5. **Aktifkan Akun**
   - UPDATE is_active = 1
   - User bisa login kembali

**Output:**
- **Success:** Data atau notifikasi sesuai action
- **Error:** Tampilkan pesan error
