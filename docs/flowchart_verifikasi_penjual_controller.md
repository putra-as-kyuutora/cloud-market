# Flowchart - VerifikasiPenjualController (Admin)

```mermaid
flowchart TD
    Start([Mulai]) --> CekAdmin{Cek user<br/>adalah Admin?}
    
    CekAdmin -->|Tidak| ErrorForbidden[Return error 403:<br/>Akses ditolak]
    ErrorForbidden --> End1([Selesai])
    
    CekAdmin -->|Ya| Action{Action?}
    
    Action -->|List| QueryPending[Query seller_profiles<br/>WHERE status = pending]
    QueryPending --> ReturnList[Return list pengajuan]
    ReturnList --> End2([Selesai])
    
    Action -->|Detail| QueryDetail[Query detail pengajuan<br/>by ID]
    QueryDetail --> ReturnDetail[Return detail:<br/>Info, KTP, Selfie]
    ReturnDetail --> End3([Selesai])
    
    Action -->|Approve| BeginTrans[BEGIN TRANSACTION]
    BeginTrans --> UpdateSeller[UPDATE seller_profiles<br/>SET status = approved<br/>verifiedBy = admin_id]
    UpdateSeller --> UpdateUser[UPDATE users<br/>SET is_seller = 1]
    UpdateUser --> Commit[COMMIT]
    Commit --> SuccessApprove[Return success:<br/>Notifikasi approved]
    SuccessApprove --> End4([Selesai])
    
    Action -->|Reject| ValidasiAlasan{Validasi alasan<br/>min 10 karakter?}
    
    ValidasiAlasan -->|Tidak Valid| ErrorAlasan[Return error:<br/>Alasan harus jelas]
    ErrorAlasan --> End5([Selesai])
    
    ValidasiAlasan -->|Valid| UpdateReject[UPDATE seller_profiles<br/>SET status = rejected<br/>alasanPenolakan = alasan<br/>rejectedBy = admin_id]
    UpdateReject --> SuccessReject[Return success:<br/>Notifikasi rejected]
    SuccessReject --> End6([Selesai])
```

## Penjelasan Algoritma

**Input:**
- action (string: list/detail/approve/reject)
- id_pengajuan (integer, untuk detail/approve/reject)
- alasan_penolakan (string, untuk reject)

**Proses:**
1. **Cek Admin**
   - Validasi user adalah admin

2. **List Pengajuan**
   - Query semua pengajuan dengan status 'pending'

3. **Detail Pengajuan**
   - Query detail pengajuan tertentu dengan foto KTP dan Selfie

4. **Approve**
   - BEGIN TRANSACTION
   - Update status seller_profiles menjadi 'approved'
   - Update users set is_seller = 1
   - COMMIT

5. **Reject**
   - Validasi alasan minimal 10 karakter
   - Update status menjadi 'rejected' dengan alasan

**Output:**
- **Success:** Notifikasi sesuai action
- **Error:** Tampilkan pesan error
