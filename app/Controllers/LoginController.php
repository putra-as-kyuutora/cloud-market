<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\User;

class LoginController extends Controller
{
    public function form(): void
    {
        $this->view('auth/login', [
            'peranAwal' => Request::str('peran', 'pembeli')
        ], 'layouts/auth');
    }

    public function login(): void
    {
        $login    = Request::str('username');
        $password = (string)Request::input('password', '');

        $errors = [];
        if ($login === '' || $password === '') {
            $errors['umum'] = 'Username/email dan password wajib diisi.';
            $this->backWithErrors('/login', $errors);
        }

        $user = (new User())->findByLogin($login);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->backWithErrors('/login', [
                'umum' => 'Username/email atau password salah.',
            ]);
        }

        if (isset($user['aktif']) && (int)$user['aktif'] !== 1) {
            $this->backWithErrors('/login', [
                'umum' => 'Akun Anda sedang dinonaktifkan oleh Administrator.',
            ]);
        }

        Auth::login($user);

        $this->success('Login berhasil! Selamat datang kembali, ' . $user['nama'] . '.');
        $this->redirect(Auth::home());
    }
}
