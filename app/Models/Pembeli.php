<?php
namespace App\Models;

class Pembeli extends Akun
{
    public string $table = 'users';
    public string $pk = 'idUser';

    public function getById(int $id): ?array
    {
        $row = $this->one(
            "SELECT idUser AS idPembeli, idUser, username, email, nama, alamat, noHp, aktif
             FROM users WHERE idUser = ?",
            [$id]
        );
        return $row ?: null;
    }

    public function updateProfil(int $id, array $d): void
    {
        (new User())->updateProfil($id, $d);
    }
}
