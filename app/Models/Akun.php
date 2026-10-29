<?php
namespace App\Models;

/**
 * Dasar untuk model akun yang disesuaikan dengan arsitektur Users terpadu.
 */
abstract class Akun extends Model
{
    public string $table = 'users';
    public string $pk = 'idUser';

    public const PERAN = ['admin', 'penjual', 'pembeli'];

    public static function forPeran(string $peran): self
    {
        switch ($peran) {
            case 'admin':   return new Admin();
            case 'penjual': return new Penjual();
            case 'pembeli': return new Pembeli();
        }
        throw new \InvalidArgumentException("Peran tidak dikenal: $peran");
    }

    public static function usernameTerpakai(string $username): bool
    {
        return (new User())->findByUsername($username) !== null;
    }

    public function getById(int $id): ?array
    {
        return (new User())->getById($id);
    }

    public function getByUsername(string $username): ?array
    {
        return (new User())->findByUsername($username);
    }

    public function validateLogin(string $username, string $password): ?array
    {
        $user = (new User())->findByLogin($username);
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        if (isset($user['aktif']) && (int)$user['aktif'] !== 1) {
            return null;
        }
        return $user;
    }

    public function hasAktif(): bool { return true; }

    public function isAktif(array $row): bool
    {
        return !array_key_exists('aktif', $row) || (int)$row['aktif'] === 1;
    }

    public function setStatus(int $id, bool $aktif): void
    {
        (new User())->updateStatusAktif($id, $aktif ? 1 : 0);
    }

    public function updatePassword(int $id, string $plain): void
    {
        $this->run("UPDATE users SET password = ? WHERE idUser = ?",
            [password_hash($plain, PASSWORD_DEFAULT), $id]);
    }

    public function count(): int
    {
        return (int)$this->scalar("SELECT COUNT(*) FROM users");
    }

    public function getAll(): array
    {
        return (new User())->allUsers();
    }
}
