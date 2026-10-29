<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $mw = []): void  { $this->add('GET', $path, $handler, $mw); }
    public function post(string $path, array $handler, array $mw = []): void { $this->add('POST', $path, $handler, $mw); }

    private function add(string $m, string $path, array $handler, array $mw): void
    {
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path) . '$#';
        $this->routes[] = compact('m', 'regex', 'handler', 'mw');
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $r) {
            if ($r['m'] !== $method || !preg_match($r['regex'], $path, $mt)) continue;
            $params = array_filter($mt, 'is_string', ARRAY_FILTER_USE_KEY);

            if ($method === 'POST' && !Session::verifyToken($_POST['_csrf'] ?? null)) {
                http_response_code(419);
                View::render('errors/error', ['kode' => 419, 'pesan' => 'Sesi form kedaluwarsa. Muat ulang halaman dan coba lagi.']);
                return;
            }
            foreach ($r['mw'] as $mw) {
                if (!$this->middleware($mw)) return;
            }
            [$class, $action] = $r['handler'];
            try {
                (new $class())->$action(...array_values($params));
            } catch (\PDOException $e) {
                $this->fail($e);
            }
            return;
        }
        http_response_code(404);
        View::render('errors/error', ['kode' => 404, 'pesan' => 'Halaman tidak ditemukan.']);
    }

    /** 'guest' | 'auth' | 'role:admin' | 'role:penjual,admin' */
    private function middleware(string $mw): bool
    {
        [$name, $arg] = array_pad(explode(':', $mw, 2), 2, null);

        if ($name === 'guest') {
            if (Auth::check()) { header('Location: ' . url(Auth::home())); return false; }
            return true;
        }
        if ($name === 'auth' || $name === 'role') {
            if (!Auth::check()) {
                Session::flash('error', 'Silakan login terlebih dahulu.');
                header('Location: ' . url('/login'));
                return false;
            }
            if (!Auth::masihAktif()) {
                Auth::logout();
                Session::flash('error', 'Akun Anda sudah tidak aktif.');
                header('Location: ' . url('/login'));
                return false;
            }
            if ($name === 'role') {
                $roles = explode(',', (string)$arg);
                $allowed = false;
                foreach ($roles as $reqRole) {
                    if ($reqRole === 'admin' && Auth::isAdmin()) $allowed = true;
                    if ($reqRole === 'pembeli' && Auth::isBuyer()) $allowed = true;
                    if ($reqRole === 'penjual' && Auth::isSellerApproved()) $allowed = true;
                }

                if (!$allowed) {
                    // Jika halaman ini butuh role penjual
                    if (in_array('penjual', $roles, true)) {
                        if (!Auth::isSeller()) {
                            Session::flash('info', 'Anda belum membuka toko. Lengkapi data KTP Anda untuk mulai berjualan.');
                            header('Location: ' . url('/penjual/daftar'));
                            return false;
                        } elseif (Auth::sellerStatus() === 'pending') {
                            Session::flash('info', 'Pendaftaran toko Anda sedang ditinjau oleh Admin.');
                            header('Location: ' . url('/penjual/status-verifikasi'));
                            return false;
                        } elseif (Auth::sellerStatus() === 'rejected') {
                            Session::flash('error', 'Pendaftaran toko Anda belum disetujui. Periksa alasan penolakan.');
                            header('Location: ' . url('/penjual/status-verifikasi'));
                            return false;
                        }
                    }

                    http_response_code(403);
                    View::render('errors/error', ['kode' => 403, 'pesan' => 'Anda tidak punya akses ke halaman ini.']);
                    return false;
                }
            }
        }
        return true;
    }

    private function fail(\PDOException $e): void
    {
        error_log($e->getMessage());
        http_response_code(500);
        View::render('errors/error', ['kode' => 500, 'pesan' => 'Terjadi kesalahan pada database.']);
    }
}
