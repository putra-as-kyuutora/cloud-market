<?php
namespace App\Core;

/** Akses konfigurasi: Config::get('db.host'), Config::get('theme') */
class Config
{
    private static array $data = [];

    public static function load(array $data): void { self::$data = $data; }

    public static function get(string $key, $default = null)
    {
        $v = self::$data;
        foreach (explode('.', $key) as $part) {
            if (!is_array($v) || !array_key_exists($part, $v)) return $default;
            $v = $v[$part];
        }
        return $v;
    }
}
