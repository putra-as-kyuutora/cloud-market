<?php
namespace App\Models;

class Admin extends Akun
{
    public string $table = 'users';
    public string $pk = 'idUser';

    /** Admin default yang dipakai sebagai pemilik data */
    public function defaultId(): ?int
    {
        $v = $this->scalar('SELECT MIN(idUser) FROM users WHERE is_admin = 1');
        return $v === null || $v === false ? 1 : (int)$v;
    }

    public function updateAdmin(int $id, string $nama): void
    {
        $this->run('UPDATE users SET nama = ? WHERE idUser = ?', [$nama, $id]);
    }
}
