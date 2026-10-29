<?php
namespace App\Core;

use App\Models\{SellerProfile, User};

class Auth
{
    /**
     * Login pengguna ke dalam session
     */
    public static function login(array $user): void
    {
        session_regenerate_id(true);

        $idUser = (int)$user['idUser'];
        $sellerProf = (new SellerProfile())->getByUserId($idUser);

        $isBuyer  = (bool)($user['is_buyer'] ?? 1);
        $isSeller = (bool)($user['is_seller'] ?? 0);
        $isAdmin  = (bool)($user['is_admin'] ?? 0);
        $statusVerif = $sellerProf['statusVerifikasi'] ?? null;

        // Tentukan peran utama untuk kompatibilitas tampilan lama
        $peran = 'pembeli';
        if ($isAdmin) {
            $peran = 'admin';
        } elseif ($isSeller && $statusVerif === 'approved') {
            $peran = 'penjual';
        }

        $_SESSION['user'] = [
            'id'             => $idUser,
            'username'       => $user['username'],
            'email'          => $user['email'] ?? '',
            'nama'           => $user['nama'],
            'alamat'         => $user['alamat'] ?? '',
            'noHp'           => $user['noHp'] ?? '',
            'is_buyer'       => $isBuyer,
            'is_seller'      => $isSeller,
            'is_admin'       => $isAdmin,
            'seller_status'  => $statusVerif,
            'toko'           => $sellerProf['namaToko'] ?? null,
            'peran'          => $peran,
        ];
    }

    /**
     * Muat ulang data user & status toko terbaru ke dalam session
     */
    public static function refresh(): void
    {
        $id = self::id();
        if (!$id) return;

        $user = (new User())->getById($id);
        if ($user) {
            self::login($user);
        }
    }

    /** Kosongkan sesi saat logout */
    public static function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
    }

    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function check(): bool  { return isset($_SESSION['user']['id']); }
    public static function id(): ?int     { return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null; }

    public static function peran(): string
    {
        return $_SESSION['user']['peran'] ?? 'tamu';
    }

    public static function is(string $peran): bool
    {
        if ($peran === 'admin')   return self::isAdmin();
        if ($peran === 'penjual') return self::isSellerApproved();
        if ($peran === 'pembeli') return self::isBuyer();
        return self::peran() === $peran;
    }

    public static function isBuyer(): bool
    {
        // Pengguna non-admin (pembeli maupun penjual) dapat bertindak sebagai pembeli
        return self::check() && !self::isAdmin();
    }

    public static function isSeller(): bool
    {
        return !empty($_SESSION['user']['is_seller']);
    }

    public static function isAdmin(): bool
    {
        return !empty($_SESSION['user']['is_admin']);
    }

    public static function sellerStatus(): ?string
    {
        return $_SESSION['user']['seller_status'] ?? null;
    }

    public static function isSellerApproved(): bool
    {
        return self::isSeller() && self::sellerStatus() === 'approved';
    }

    public static function toko(): ?string
    {
        return $_SESSION['user']['toko'] ?? null;
    }

    /** Cegah akun yang dinonaktifkan admin tetap memakai sesi lamanya. */
    public static function masihAktif(): bool
    {
        $id = self::id();
        if (!$id) return false;
        $u = (new User())->getById($id);
        return $u && (int)$u['aktif'] === 1;
    }

    public static function home(?string $peran = null): string
    {
        if (self::isAdmin()) {
            return '/admin';
        }
        if (self::isSellerApproved() && $peran === 'penjual') {
            return '/penjual/barang';
        }
        return '/';
    }
}
