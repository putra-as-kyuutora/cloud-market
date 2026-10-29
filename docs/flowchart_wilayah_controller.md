# Flowchart - WilayahController (API untuk Form Alamat)

```mermaid
flowchart TD
    Start([Mulai]) --> Action{Action?}
    
    Action -->|Get Provinsi| QueryProvinsi[Query daftar provinsi:<br/>SELECT DISTINCT provinsi<br/>FROM Kodepos<br/>ORDER BY provinsi ASC]
    QueryProvinsi --> ReturnProvinsi[Return JSON:<br/>array of provinsi]
    ReturnProvinsi --> End1([Selesai])
    
    Action -->|Get Kota| InputKota[Terima input:<br/>provinsi]
    InputKota --> ValidasiProvinsi{Provinsi<br/>valid?}
    
    ValidasiProvinsi -->|Tidak| ErrorProvinsi[Return error:<br/>Provinsi tidak valid]
    ErrorProvinsi --> End2([Selesai])
    
    ValidasiProvinsi -->|Ya| QueryKota[Query daftar kota:<br/>SELECT DISTINCT kota<br/>FROM Kodepos<br/>WHERE provinsi = input<br/>ORDER BY kota ASC]
    QueryKota --> ReturnKota[Return JSON:<br/>array of kota]
    ReturnKota --> End3([Selesai])
    
    Action -->|Get Kecamatan| InputKecamatan[Terima input:<br/>provinsi, kota]
    InputKecamatan --> ValidasiKota{Kota valid?}
    
    ValidasiKota -->|Tidak| ErrorKota[Return error:<br/>Kota tidak valid]
    ErrorKota --> End4([Selesai])
    
    ValidasiKota -->|Ya| QueryKecamatan[Query daftar kecamatan:<br/>SELECT DISTINCT kecamatan<br/>FROM Kodepos<br/>WHERE provinsi = p AND kota = k<br/>ORDER BY kecamatan ASC]
    QueryKecamatan --> ReturnKecamatan[Return JSON:<br/>array of kecamatan]
    ReturnKecamatan --> End5([Selesai])
    
    Action -->|Get Kelurahan| InputKelurahan[Terima input:<br/>provinsi, kota, kecamatan]
    InputKelurahan --> ValidasiKecamatan{Kecamatan<br/>valid?}
    
    ValidasiKecamatan -->|Tidak| ErrorKecamatan[Return error:<br/>Kecamatan tidak valid]
    ErrorKecamatan --> End6([Selesai])
    
    ValidasiKecamatan -->|Ya| QueryKelurahan[Query daftar kelurahan<br/>dan kode pos:<br/>SELECT kelurahan, kodepos<br/>FROM Kodepos<br/>WHERE provinsi = p<br/>AND kota = k<br/>AND kecamatan = kec<br/>ORDER BY kelurahan ASC]
    QueryKelurahan --> ReturnKelurahan[Return JSON:<br/>array of kelurahan, kodepos]
    ReturnKelurahan --> End7([Selesai])
    
    Action -->|Get Kodepos| InputKodepos[Terima input:<br/>provinsi, kota,<br/>kecamatan, kelurahan]
    InputKodepos --> QueryKodepos[Query kode pos:<br/>SELECT kodepos<br/>FROM Kodepos<br/>WHERE provinsi = p<br/>AND kota = k<br/>AND kecamatan = kec<br/>AND kelurahan = kel]
    QueryKodepos --> KodeposExist{Kode pos<br/>ditemukan?}
    
    KodeposExist -->|Tidak| ErrorKodepos[Return error:<br/>Kode pos tidak ditemukan]
    ErrorKodepos --> End8([Selesai])
    
    KodeposExist -->|Ya| ReturnKodepos[Return JSON:<br/>object with kodepos]
    ReturnKodepos --> End9([Selesai])
```

## Penjelasan Algoritma

**Input:**
- action (string: get_provinsi/get_kota/get_kecamatan/get_kelurahan/get_kodepos)
- provinsi (string, untuk get_kota dan seterusnya)
- kota (string, untuk get_kecamatan dan seterusnya)
- kecamatan (string, untuk get_kelurahan dan get_kodepos)
- kelurahan (string, untuk get_kodepos)

**Proses:**
1. **Get Provinsi**
   - Query DISTINCT provinsi dari tabel Kodepos
   - Return array provinsi (sorted)

2. **Get Kota**
   - Terima input provinsi
   - Query DISTINCT kota WHERE provinsi = input
   - Return array kota (sorted)

3. **Get Kecamatan**
   - Terima input provinsi dan kota
   - Query DISTINCT kecamatan WHERE provinsi dan kota match
   - Return array kecamatan (sorted)

4. **Get Kelurahan**
   - Terima input provinsi, kota, kecamatan
   - Query kelurahan dan kodepos WHERE semua match
   - Return array of {kelurahan, kodepos}

5. **Get Kodepos**
   - Terima input lengkap: provinsi, kota, kecamatan, kelurahan
   - Query kode pos yang tepat
   - Return object {kodepos}

**Output:**
- **Success:** JSON response dengan data wilayah
- **Error:** JSON error message

**Catatan:**
- Controller ini biasanya digunakan untuk AJAX request
- Untuk populate dropdown cascading di form alamat
- Data wilayah diambil dari tabel Kodepos (data Indonesia)
