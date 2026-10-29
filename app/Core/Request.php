<?php
namespace App\Core;

class Request
{
    /** Folder tempat index.php berada, mis. "/cloud-market/public" (kosong jika di root domain). */
    public static function basePath(): string
    {
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        return rtrim($dir === '/' ? '' : $dir, '/');
    }

    /** Path bersih tanpa base & query string, mis. "/barang/5" */
    public static function path(): string
    {
        $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = self::basePath();
        if ($base !== '' && strpos($uri, $base) === 0) $uri = substr($uri, strlen($base));
        $uri = '/' . trim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    public static function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public static function str(string $key, string $default = ''): string
    {
        $v = self::input($key, $default);
        return is_string($v) ? trim($v) : $default;
    }

    public static function int(string $key, int $default = 0): int
    {
        $v = self::input($key, null);
        return is_numeric($v) ? (int)$v : $default;
    }
}
