<?php
namespace App\Core;

/** Kelas dasar semua controller. */
abstract class Controller
{
    protected function view(string $name, array $data = [], ?string $layout = 'layouts/main'): void
    {
        View::render($name, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function success(string $msg): void { Session::flash('success', $msg); }
    protected function error(string $msg): void   { Session::flash('error', $msg); }

    /** Kembali ke form dengan pesan error per-field + isian lama. */
    protected function backWithErrors(string $path, array $errors): void
    {
        Session::flashForm($errors, $_POST);
        $this->redirect($path);
    }

    protected function notFound(string $msg = 'Halaman tidak ditemukan.'): void
    {
        http_response_code(404);
        View::render('errors/error', ['kode' => 404, 'pesan' => $msg]);
        exit;
    }

    protected function forbidden(string $msg = 'Anda tidak punya akses ke halaman ini.'): void
    {
        http_response_code(403);
        View::render('errors/error', ['kode' => 403, 'pesan' => $msg]);
        exit;
    }

    protected function user(): array { return Auth::user() ?? []; }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
