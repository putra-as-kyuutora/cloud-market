<?php
/** Fungsi bantu global yang dipakai di controller maupun template tema. */

use App\Core\{Auth, Config, Request, Session, View};

/** Escape HTML - WAJIB dipakai untuk setiap data yang dicetak di view. */
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/** URL halaman: url('/barang/5') */
function url(string $path = '/', array $query = []): string
{
    $u = Request::basePath() . '/' . ltrim($path, '/');
    $u = rtrim($u, '/') === '' ? '/' : $u;
    return $query ? $u . '?' . http_build_query(array_filter($query, fn($x) => $x !== '' && $x !== null)) : $u;
}

/** URL aset tema: asset('css/app.css') -> /assets/{tema}/css/app.css (fallback ke tema default) */
function asset(string $file): string
{
    foreach ([View::theme(), 'default'] as $t) {
        $p = ROOT_PATH . "/public/assets/$t/$file";
        if (is_file($p)) {
            $ver = filemtime($p);
            return url("/assets/$t/$file") . '?v=' . $ver;
        }
    }
    return url("/assets/default/$file");
}

/** URL file hasil upload (gambar barang / bukti bayar). Null jika kosong. */
function upload_url(?string $rel): ?string { return $rel ? url('/uploads/' . $rel) : null; }

function rupiah($n): string { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }

function tanggal($dt): string { return $dt ? date('d-m-Y H:i', strtotime($dt)) : '-'; }

function csrf_field(): string { return '<input type="hidden" name="_csrf" value="' . e(Session::token()) . '">'; }

/** Isian lama form: value="<?= e(old('nama')) ?>" */
function old(string $key, $default = '')
{
    return View::$old[$key] ?? $default;
}

function auth(): ?array { return Auth::user(); }
function peran(): string { return Auth::peran(); }

/** Penanda menu aktif */
function menu_aktif(string $prefix): bool
{
    $p = Request::path();
    return $prefix === '/' ? $p === '/' : ($p === $prefix || strpos($p, $prefix . '/') === 0);
}

/** Kelas CSS untuk lencana status: pakai di tema sebagai class="badge <?= status_class($s) ?>" */
function status_class(string $s): string
{
    $map = [
        'Menunggu Pembayaran' => 'warn', 'Menunggu Konfirmasi' => 'info', 'Sudah Dibayar' => 'ok',
        'Ditolak' => 'bad', 'Dibatalkan' => 'bad',
        'Belum Dikirim' => 'muted', 'Sedang Diproses' => 'info', 'Sudah Dikirim' => 'info', 'Diterima' => 'ok',
    ];
    return $map[$s] ?? 'muted';
}
