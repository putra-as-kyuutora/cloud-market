# Flowchart - RegistrasiController

```mermaid
flowchart TD
    Start([Mulai]) --> Input[Terima input:<br/>username, email, password,<br/>nama lengkap, alamat]
    Input --> ValidasiInput{Validasi format<br/>data input}
    
    ValidasiInput -->|Tidak Valid| ErrorValidasi[Return error:<br/>Data tidak valid]
    ErrorValidasi --> End1([Selesai])
    
    ValidasiInput -->|Valid| CekUsername[Query database:<br/>Cek username exist]
    CekUsername --> UsernameExist{Username<br/>sudah ada?}
    
    UsernameExist -->|Ya| ErrorUsername[Return error:<br/>Username sudah digunakan]
    ErrorUsername --> End2([Selesai])
    
    UsernameExist -->|Tidak| CekEmail[Query database:<br/>Cek email exist]
    CekEmail --> EmailExist{Email<br/>sudah ada?}
    
    EmailExist -->|Ya| ErrorEmail[Return error:<br/>Email sudah digunakan]
    ErrorEmail --> End3([Selesai])
    
    EmailExist -->|Tidak| HashPassword[Hash password<br/>dengan bcrypt]
    HashPassword --> SaveDB[INSERT data user<br/>ke database]
    SaveDB --> Success[Return success:<br/>Redirect ke login<br/>dengan notifikasi]
    Success --> End4([Selesai])
```

## Penjelasan Algoritma

**Input:**
- username (string)
- email (string)
- password (string)
- nama_lengkap (string)
- alamat (string)

**Proses:**
1. **Validasi Format Data**
   - Cek semua field tidak kosong
   - Cek format email valid
   - Cek password minimal 6 karakter
   - Cek username hanya alphanumeric

2. **Cek Username**
   - Query ke database untuk cek username sudah ada atau belum
   - Jika sudah ada → return error

3. **Cek Email**
   - Query ke database untuk cek email sudah ada atau belum
   - Jika sudah ada → return error

4. **Hash Password**
   - Gunakan bcrypt untuk hash password

5. **Simpan ke Database**
   - INSERT record baru ke tabel users

**Output:**
- **Success:** Redirect ke halaman login dengan notifikasi "Registrasi berhasil"
- **Error:** Tampilkan pesan error sesuai kondisi
