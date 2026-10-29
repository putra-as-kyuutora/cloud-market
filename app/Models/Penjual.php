<?php
namespace App\Models;

class Penjual extends Akun
{
    public string $table = 'users';
    public string $pk = 'idUser';

    public function getById(int $id): ?array
    {
        $row = $this->one(
            "SELECT u.idUser AS idPenjual, u.idUser, u.username, u.nama, u.alamat, u.noHp, u.aktif,
                    s.namaToko, s.deskripsiToko, s.statusVerifikasi,
                    (s.statusVerifikasi = 'approved') AS verified
             FROM users u
             LEFT JOIN seller_profiles s ON u.idUser = s.idUser
             WHERE u.idUser = ?",
            [$id]
        );
        return $row ?: null;
    }

    public function updateProfil(int $id, array $d): void
    {
        (new User())->updateProfil($id, $d);
        if (isset($d['namaToko'])) {
            $this->run(
                "UPDATE seller_profiles SET namaToko = :nama, deskripsiToko = :desc WHERE idUser = :id",
                [
                    ':nama' => $d['namaToko'],
                    ':desc' => $d['deskripsiToko'] ?? '',
                    ':id'   => $id
                ]
            );
        }
    }

    public function setVerified(int $id, bool $v): void
    {
        $status = $v ? 'approved' : 'rejected';
        $this->run("UPDATE seller_profiles SET statusVerifikasi = ? WHERE idUser = ?", [$status, $id]);
    }

    public function getAllSimple(): array
    {
        return $this->all(
            "SELECT u.idUser AS idPenjual, s.namaToko
             FROM users u
             JOIN seller_profiles s ON u.idUser = s.idUser
             WHERE s.statusVerifikasi = 'approved'
             ORDER BY s.namaToko"
        );
    }
}
