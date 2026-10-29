# Flowchart - EditDataPribadiController

```mermaid
flowchart TD
    Start([Mulai]) --> CekLogin{Cek user<br/>sudah login?}
    
    CekLogin -->|Tidak| ErrorLogin[Return error:<br/>Redirect ke /login]
    ErrorLogin --> End1([Selesai])
    
    CekLogin -->|Ya| Action{Action?}
    
    Action -->|Show Form| QueryUser[Query data user<br/>dari database]
    QueryUser --> ReturnForm[Return form<br/>dengan data pre-filled]
    ReturnForm --> End2([Selesai])
    
    Action -->|Update| Input[Terima input data baru:<br/>nama, email, telepon,<br/>alamat, password baru optional]
    Input --> ValidasiInput{Validasi<br/>data input}
    
    ValidasiInput -->|Tidak Valid| ErrorValidasi[Return error:<br/>Data tidak valid]
    ErrorValidasi --> End3([Selesai])
    
    ValidasiInput -->|Valid| UbahPassword{User ubah<br/>password?}
    
    UbahPassword -->|Ya| ValidasiPasswordLama{Validasi<br/>password lama<br/>benar?}
    
    ValidasiPasswordLama -->|Salah| ErrorPasswordLama[Return error:<br/>Password lama salah]
    ErrorPasswordLama --> End4([Selesai])
    
    ValidasiPasswordLama -->|Benar| ValidasiPasswordBaru{Validasi<br/>password baru<br/>min 6 karakter<br/>dan match?}
    
    ValidasiPasswordBaru -->|Tidak Valid| ErrorPasswordBaru[Return error:<br/>Password tidak valid]
    ErrorPasswordBaru --> End5([Selesai])
    
    ValidasiPasswordBaru -->|Valid| HashPassword[Hash password baru<br/>dengan bcrypt]
    HashPassword --> UpdateWithPassword[UPDATE users<br/>SET data + password]
    UpdateWithPassword --> Success1[Return success:<br/>Profil berhasil diperbarui]
    Success1 --> End6([Selesai])
    
    UbahPassword -->|Tidak| UpdateNoPassword[UPDATE users<br/>SET data tanpa password]
    UpdateNoPassword --> Success2[Return success:<br/>Profil berhasil diperbarui]
    Success2 --> End7([Selesai])
```

## Penjelasan Algoritma

**Input:**
- nama_lengkap (string, optional)
- email (string, optional)
- telepon (string, optional)
- alamat (string, optional)
- password_lama (string, optional)
- password_baru (string, optional)
- password_konfirmasi (string, optional)

**Proses:**
1. **Cek Login**
   - Validasi user sudah login

2. **Show Form (GET)**
   - Query data user saat ini
   - Tampilkan form pre-filled

3. **Update (POST)**
   - Validasi data input
   - Jika ubah password:
     - Verifikasi password lama
     - Validasi password baru
     - Hash password baru
   - Update data ke database

**Output:**
- **Success:** Notifikasi "Profil berhasil diperbarui"
- **Error:** Tampilkan pesan error sesuai kondisi
