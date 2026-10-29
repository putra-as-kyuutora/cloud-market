<?php
namespace App\Core;

/**
 * Mesin tampilan. Controller TIDAK pernah menulis HTML; ia hanya memanggil
 *     View::render('barang/detail', ['barang' => $row]);
 * File dicari di themes/{tema aktif}/barang/detail.php, lalu fallback ke themes/default.
 */
class View
{
    public string $title = '';
    public string $bodyClass = '';
    private array $shared = [];
    /** Isian form lama (dibaca helper old()). */
    public static array $old = [];

    public function share(string $k, $v): void { $this->shared[$k] = $v; }

    public static function theme(): string { return Config::get('theme', 'default'); }

    /** Cari file di tema aktif, fallback ke tema default. */
    public static function locate(string $name): string
    {
        foreach ([self::theme(), 'default'] as $t) {
            $f = ROOT_PATH . "/themes/$t/$name.php";
            if (is_file($f)) return $f;
        }
        throw new \RuntimeException("View '$name' tidak ditemukan di tema '" . self::theme() . "' maupun 'default'.");
    }

    public static function render(string $name, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $v = new self();
        $v->share('menu', self::menuUntuk());
        $v->share('user', Auth::user());
        $v->share('flashSuccess', Session::pull('success'));
        $v->share('flashError', Session::pull('error'));
        $v->share('errors', Session::pull('errors', []));
        self::$old = Session::pull('old', []);
        $v->share('old', self::$old);

        // Otomatis gunakan layout dashboard penjual untuk rute penjual
        if ($layout === 'layouts/main' && Auth::is('penjual')) {
            $path = Request::path();
            if (strpos($path, '/penjual') === 0 || $path === '/laporan' || $path === '/pesan') {
                $layout = 'layouts/penjual';
            }
        }

        $content = $v->capture($name, $data);
        if ($layout) {
            echo $v->capture($layout, $data + ['content' => $content]);
        } else {
            echo $content;
        }
    }

    public static function menuUntuk(): array
    {
        $menu = require ROOT_PATH . '/config/menu.php';
        return $menu[Auth::peran()] ?? $menu['tamu'];
    }

    private function capture(string $name, array $data): string
    {
        $file = self::locate($name);
        $vars = $this->shared + $data;
        $run = function () use ($file, $vars) {
            extract($vars, EXTR_SKIP);
            ob_start();
            include $file;
            return ob_get_clean();
        };
        return \Closure::bind($run, $this, self::class)();
    }

    /** Dipanggil dari dalam template: $this->partial('partials/produk_kartu', ['b' => $b]) */
    public function partial(string $name, array $data = []): void
    {
        echo $this->capture($name, $data);
    }
}
