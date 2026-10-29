<?php
namespace App\Core;

/** Flash message, old input, error validasi, dan token CSRF. */
class Session
{
    public static function flash(string $key, $value): void { $_SESSION['_flash'][$key] = $value; }

    public static function pull(string $key, $default = null)
    {
        $v = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $v;
    }

    /** Simpan input + error untuk ditampilkan ulang di form. */
    public static function flashForm(array $errors, array $old = []): void
    {
        unset($old['password'], $old['password_baru'], $old['csrf']);
        self::flash('errors', $errors);
        self::flash('old', $old);
    }

    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['_csrf'];
    }

    public static function verifyToken(?string $t): bool
    {
        return is_string($t) && !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $t);
    }
}
