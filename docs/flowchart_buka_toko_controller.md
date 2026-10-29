# Flowchart - BukaTokoController (Daftar Penjual KYC)

```mermaid
flowchart TD
    Start([Mulai]) --> Input[Terima input:<br/>nama_toko, deskripsi,<br/>NIK, foto_ktp, foto_selfie]
    Input --> ValidasiInput{Validasi<br/>data input}
    
    ValidasiInput -->|Tidak Valid| ErrorValidasi[Return error:<br/>Data tidak valid]
    ErrorValidasi --> End1([Selesai])
    
    ValidasiInput -->|Valid| ValidasiNIK{Validasi NIK<br/>16 digit angka?}
    
    ValidasiNIK -->|Tidak Valid| ErrorNIK[Return error:<br/>NIK harus 16 digit angka]
    ErrorNIK --> End2([Selesai])
    
    ValidasiNIK -->|Valid| ValidasiFile{Validasi file<br/>foto format<br/>dan ukuran?}
    
    ValidasiFile -->|Tidak Valid| ErrorFile[Return error:<br/>File tidak valid]
    ErrorFile --> End3([Selesai])
    
    ValidasiFile -->|Valid| UploadFile[Upload file ke<br/>/uploads/kyc/<br/>dengan nama random]
    UploadFile --> SaveDB[INSERT ke seller_profiles:<br/>- user_id<br/>- nama_toko, NIK<br/>- path foto<br/>- status = pending]
    SaveDB --> Success[Return success:<br/>Notifikasi menunggu<br/>verifikasi admin]
    Success --> End4([Selesai])
```

## Penjelasan Algoritma

**Input:**
- nama_toko (string)
- deskripsi_toko (string)
- nik (string, 16 digit)
- foto_ktp (file)
- foto_selfie (file)

**Proses:**
1. **Validasi Data Input**
   - Cek semua field tidak kosong
   - Cek nama toko dan deskripsi

2. **Validasi NIK**
   - Cek panjang = 16 karakter
   - Cek hanya berisi angka

3. **Validasi File**
   - Cek format: JPG, PNG
   - Cek ukuran maksimal 2MB
   - Cek MIME type

4. **Upload File**
   - Simpan file ke folder /uploads/kyc/
   - Generate nama file random untuk keamanan

5. **Simpan ke Database**
   - INSERT record ke tabel seller_profiles
   - Set status = 'pending'

**Output:**
- **Success:** Notifikasi "Menunggu verifikasi admin"
- **Error:** Tampilkan pesan error sesuai kondisi
