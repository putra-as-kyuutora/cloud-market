<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\User;

class RegistrasiController extends Controller
{
    public function form(): void
    {
        $this->view('auth/registrasi', [], 'layouts/auth');
    }

    public function registrasi(): void
    {
        $d = [
            'username' => Request::str('username'),
            'email'    => Request::str('email'),
            'password' => (string)Request::input('password', ''),
            'nama'     => Request::str('nama'),
            'alamat'   => Request::str('alamat'),
            'noHp'     => Request::str('noHp'),
        ];

        $userModel = new User();
        $errors = $this->validasiInput($d, $userModel);

        if ($errors) {
            $this->backWithErrors('/registrasi', $errors);
        }

        $idUser = $userModel->register($d);
        $user = $userModel->getById($idUser);

        // Auto login setelah registrasi
        Auth::login($user);

        $this->success('Selamat datang di Cloud Market! Akun Anda berhasil dibuat dan siap digunakan.');
        $this->redirect('/');
    }

    private function validasiInput(array $d, User $userModel): array
    {
        $e = [];
        if ($d['nama'] === '') {
            $e['nama'] = 'Nama lengkap wajib diisi.';
        }

        if (!preg_match('/^[A-Za-z0-9_.]{4,50}$/', $d['username'])) {
            $e['username'] = 'Username 4-50 karakter (hanya huruf, angka, titik, atau garis bawah).';
        } elseif ($userModel->findByUsername($d['username'])) {
            $e['username'] = 'Username sudah digunakan, silakan pilih username lain.';
        }

        if ($d['email'] !== '') {
            if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
                $e['email'] = 'Format email tidak valid.';
            } elseif ($userModel->findByEmail($d['email'])) {
                $e['email'] = 'Email sudah terdaftar.';
            }
        }

        if (strlen($d['password']) < 6) {
            $e['password'] = 'Password minimal 6 karakter.';
        }

        if (!preg_match('/^(\+62|62|0)[0-9]{8,13}$/', $d['noHp'])) {
            $e['noHp'] = 'Nomor HP tidak valid (contoh: 081234567890).';
        }

        if ($d['alamat'] === '') {
            $e['alamat'] = 'Alamat tempat tinggal wajib diisi.';
        }

        return $e;
    }
}
