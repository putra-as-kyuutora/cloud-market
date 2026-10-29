<?php
namespace App\Models;

/**
 * Model User Utama (Multi-Role: Buyer, Seller, Admin)
 */
class User extends Model
{
    public function getById(int $id): ?array
    {
        return $this->one(
            "SELECT u.*, s.namaToko, s.deskripsiToko, s.statusVerifikasi, s.noKtp, s.fotoKtp, s.fotoSelfie,
                    s.namaBank, s.noRekening, s.atasNamaRekening, s.alasanPenolakan, s.tglVerifikasi
             FROM users u
             LEFT JOIN seller_profiles s ON u.idUser = s.idUser
             WHERE u.idUser = :id",
            [':id' => $id]
        );
    }

    public function findByLogin(string $login): ?array
    {
        return $this->one(
            "SELECT u.*, s.namaToko, s.deskripsiToko, s.statusVerifikasi
             FROM users u
             LEFT JOIN seller_profiles s ON u.idUser = s.idUser
             WHERE u.username = :l1 OR u.email = :l2",
            [':l1' => $login, ':l2' => $login]
        );
    }

    public function findByUsername(string $username): ?array
    {
        return $this->one("SELECT * FROM users WHERE username = :u", [':u' => $username]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->one("SELECT * FROM users WHERE email = :e", [':e' => $email]);
    }

    /**
     * Registrasi pengguna baru (default: pembeli)
     */
    public function register(array $d): int
    {
        $this->run(
            "INSERT INTO users (username, email, password, nama, alamat, noHp, is_buyer, is_seller, is_admin, aktif, created_at)
             VALUES (:u, :e, :p, :nama, :alamat, :hp, 1, 0, 0, 1, NOW())",
            [
                ':u'      => $d['username'],
                ':e'      => !empty($d['email']) ? $d['email'] : ($d['username'] . '@user.cloudmarket.id'),
                ':p'      => password_hash($d['password'], PASSWORD_DEFAULT),
                ':nama'   => $d['nama'],
                ':alamat' => $d['alamat'] ?? '',
                ':hp'     => $d['noHp'] ?? '',
            ]
        );
        return $this->lastId();
    }

    /**
     * Update profil dasar pengguna
     */
    public function updateProfil(int $idUser, array $d): void
    {
        $sql = "UPDATE users SET nama = :nama, alamat = :alamat, noHp = :hp";
        $params = [
            ':nama'   => $d['nama'],
            ':alamat' => $d['alamat'],
            ':hp'     => $d['noHp'],
            ':id'     => $idUser,
        ];
        if (!empty($d['email'])) {
            $sql .= ", email = :email";
            $params[':email'] = $d['email'];
        }
        if (!empty($d['password'])) {
            $sql .= ", password = :pass";
            $params[':pass'] = password_hash($d['password'], PASSWORD_DEFAULT);
        }
        $sql .= " WHERE idUser = :id";
        $this->run($sql, $params);
    }

    /**
     * Aktifkan flag is_seller
     */
    public function setSeller(int $idUser, bool $isSeller = true): void
    {
        $this->run("UPDATE users SET is_seller = :s WHERE idUser = :id", [
            ':s'  => $isSeller ? 1 : 0,
            ':id' => $idUser
        ]);
    }

    /**
     * Toggle status aktif akun pengguna (oleh Admin)
     */
    public function updateStatusAktif(int $idUser, int $aktif): void
    {
        $this->run("UPDATE users SET aktif = :a WHERE idUser = :id", [
            ':a'  => $aktif ? 1 : 0,
            ':id' => $idUser
        ]);
    }

    /**
     * Ambil semua pengguna untuk kelola akun di dashboard admin
     */
    public function allUsers(): array
    {
        return $this->all(
            "SELECT u.*, s.namaToko, s.statusVerifikasi
             FROM users u
             LEFT JOIN seller_profiles s ON u.idUser = s.idUser
             ORDER BY u.idUser DESC"
        );
    }
}
