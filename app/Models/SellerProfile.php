<?php
namespace App\Models;

/**
 * Model Profil Penjual & Verifikasi KTP (KYC)
 */
class SellerProfile extends Model
{
    public function getByUserId(int $idUser): ?array
    {
        return $this->one("SELECT * FROM seller_profiles WHERE idUser = :id", [':id' => $idUser]);
    }

    /**
     * Daftarkan atau perbarui pengajuan data toko + KTP
     */
    public function ajukan(int $idUser, array $d): void
    {
        $existing = $this->getByUserId($idUser);

        $params = [
            ':id'           => $idUser,
            ':namaToko'     => $d['namaToko'],
            ':deskripsi'    => $d['deskripsiToko'] ?? '',
            ':noKtp'        => $d['noKtp'] ?? '',
            ':fotoKtp'      => $d['fotoKtp'] ?? ($existing['fotoKtp'] ?? null),
            ':fotoSelfie'   => $d['fotoSelfie'] ?? ($existing['fotoSelfie'] ?? null),
            ':namaBank'     => $d['namaBank'] ?? '',
            ':noRekening'   => $d['noRekening'] ?? '',
            ':atasNama'     => $d['atasNamaRekening'] ?? '',
        ];

        if ($existing) {
            $this->run(
                "UPDATE seller_profiles SET
                    namaToko = :namaToko,
                    deskripsiToko = :deskripsi,
                    noKtp = :noKtp,
                    fotoKtp = :fotoKtp,
                    fotoSelfie = :fotoSelfie,
                    namaBank = :namaBank,
                    noRekening = :noRekening,
                    atasNamaRekening = :atasNama,
                    statusVerifikasi = 'pending',
                    alasanPenolakan = NULL,
                    updated_at = NOW()
                 WHERE idUser = :id",
                $params
            );
        } else {
            $this->run(
                "INSERT INTO seller_profiles (idUser, namaToko, deskripsiToko, noKtp, fotoKtp, fotoSelfie, statusVerifikasi, namaBank, noRekening, atasNamaRekening, created_at)
                 VALUES (:id, :namaToko, :deskripsi, :noKtp, :fotoKtp, :fotoSelfie, 'pending', :namaBank, :noRekening, :atasNama, NOW())",
                $params
            );
        }

        // Tandai user sebagai seller (pending review)
        (new User())->setSeller($idUser, true);
    }

    /**
     * Ambil daftar calon penjual berdasarkan status verifikasi untuk Admin
     */
    public function getVerifikasiList(?string $status = null): array
    {
        $sql = "SELECT s.*, u.nama, u.username, u.email, u.noHp, u.alamat,
                       a.nama AS namaAdminVerifikator
                FROM seller_profiles s
                JOIN users u ON s.idUser = u.idUser
                LEFT JOIN users a ON s.diverifikasiOleh = a.idUser";

        $params = [];
        if ($status && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $sql .= " WHERE s.statusVerifikasi = :st";
            $params[':st'] = $status;
        }
        $sql .= " ORDER BY (s.statusVerifikasi = 'pending') DESC, s.created_at DESC";

        return $this->all($sql, $params);
    }

    /**
     * Ambil detail verifikasi satu penjual
     */
    public function getDetailVerifikasi(int $idUser): ?array
    {
        return $this->one(
            "SELECT s.*, u.nama, u.username, u.email, u.noHp, u.alamat,
                    a.nama AS namaAdminVerifikator
             FROM seller_profiles s
             JOIN users u ON s.idUser = u.idUser
             LEFT JOIN users a ON s.diverifikasiOleh = a.idUser
             WHERE s.idUser = :id",
            [':id' => $idUser]
        );
    }

    /**
     * Admin menyetujui pengajuan toko
     */
    public function setujui(int $idUser, int $adminId): void
    {
        $this->run(
            "UPDATE seller_profiles SET
                statusVerifikasi = 'approved',
                tglVerifikasi = NOW(),
                diverifikasiOleh = :adminId,
                alasanPenolakan = NULL
             WHERE idUser = :id",
            [':adminId' => $adminId, ':id' => $idUser]
        );
        (new User())->setSeller($idUser, true);
    }

    /**
     * Admin menolak pengajuan toko
     */
    public function tolak(int $idUser, int $adminId, string $alasan): void
    {
        $this->run(
            "UPDATE seller_profiles SET
                statusVerifikasi = 'rejected',
                tglVerifikasi = NOW(),
                diverifikasiOleh = :adminId,
                alasanPenolakan = :alasan
             WHERE idUser = :id",
            [':adminId' => $adminId, ':alasan' => $alasan, ':id' => $idUser]
        );
    }

    /**
     * Hitung antrean verifikasi yang masih pending
     */
    public function hitungPending(): int
    {
        return (int)$this->scalar("SELECT COUNT(*) FROM seller_profiles WHERE statusVerifikasi = 'pending'");
    }
}
