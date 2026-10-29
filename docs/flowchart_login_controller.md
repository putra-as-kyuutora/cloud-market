# Flowchart - LoginController

```mermaid
flowchart TD
    Start([Mulai]) --> Input[Terima input:<br/>username/email, password]
    Input --> ValidasiInput{Validasi format<br/>data input}
    
    ValidasiInput -->|Tidak Valid| ErrorValidasi[Return error:<br/>Data tidak valid]
    ErrorValidasi --> End1([Selesai])
    
    ValidasiInput -->|Valid| QueryUser[Query database:<br/>Cek kredensial user]
    QueryUser --> UserExist{User<br/>ditemukan?}
    
    UserExist -->|Tidak| ErrorUser[Return error:<br/>Akun tidak ditemukan]
    ErrorUser --> End2([Selesai])
    
    UserExist -->|Ya| VerifyPassword{Verifikasi<br/>password<br/>dengan bcrypt}
    
    VerifyPassword -->|Salah| ErrorPassword[Return error:<br/>Password salah]
    ErrorPassword --> End3([Selesai])
    
    VerifyPassword -->|Benar| CekStatus{Cek status<br/>akun aktif?}
    
    CekStatus -->|Nonaktif| ErrorStatus[Return error:<br/>Akun dinonaktifkan]
    ErrorStatus --> End4([Selesai])
    
    CekStatus -->|Aktif| CreateSession[Buat session user:<br/>- User ID<br/>- Role flags<br/>- Session token]
    CreateSession --> Success[Return success:<br/>Redirect ke dashboard<br/>sesuai role]
    Success --> End5([Selesai])
```

## Penjelasan Algoritma

**Input:**
- username_or_email (string)
- password (string)

**Proses:**
1. **Validasi Format Data**
   - Cek username/email tidak kosong
   - Cek password tidak kosong

2. **Query User**
   - Cari user berdasarkan username atau email

3. **Verifikasi Password**
   - Gunakan bcrypt verify untuk cek password

4. **Cek Status Akun**
   - Cek is_active = 1

5. **Buat Session**
   - Simpan user_id, role, dan token ke session

**Output:**
- **Success:** Redirect ke dashboard sesuai role (admin/penjual/pembeli)
- **Error:** Tampilkan pesan error sesuai kondisi
