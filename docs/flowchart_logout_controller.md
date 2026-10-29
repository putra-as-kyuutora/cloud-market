# Flowchart - LogoutController

```mermaid
flowchart TD
    Start([Mulai]) --> CekSession{Cek user<br/>sudah login?}
    
    CekSession -->|Tidak| ErrorNotLogin[Return error:<br/>User tidak login]
    ErrorNotLogin --> End1([Selesai])
    
    CekSession -->|Ya| DestroySession[Hapus semua session data:<br/>- session_destroy<br/>- Hapus user_id<br/>- Hapus role flags]
    DestroySession --> Success[Return success:<br/>Redirect ke halaman<br/>login atau beranda]
    Success --> End2([Selesai])
```

## Penjelasan Algoritma

**Input:**
- (tidak ada input eksplisit, hanya cek session)

**Proses:**
1. **Cek Session**
   - Cek apakah user sudah login dengan cek session user_id

2. **Destroy Session**
   - Hapus semua data session
   - Panggil session_destroy()

**Output:**
- **Success:** Redirect ke halaman login atau beranda
- **Error:** User tidak login (jarang terjadi)
