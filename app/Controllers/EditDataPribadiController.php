<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request, Session};
use App\Models\{Admin, Akun, Penjual};

/** "Kelola Profil" untuk semua peran. */
class EditDataPribadiController extends Controller
{
    public function form(): void
    {
        $this->view('profil/edit', ['profil' => $this->getDataProfil(Auth::id(), Auth::peran())]);
    }

    public function editProfil(): void
    {
        $peran = Auth::peran();
        $id = Auth::id();
        $d = [
            'nama' => Request::str('nama'), 'alamat' => Request::str('alamat'), 'noHp' => Request::str('noHp'),
            'namaToko' => Request::str('namaToko'), 'deskripsiToko' => Request::str('deskripsiToko'),
        ];
        $pwBaru = (string)Request::input('password_baru', '');
        $errors = $this->validasiDataProfil($d, $peran);
        if ($pwBaru !== '' && strlen($pwBaru) < 6) $errors['password_baru'] = 'Password baru minimal 6 karakter.';
        if ($errors) $this->backWithErrors('/profil', $errors);

        $model = Akun::forPeran($peran);
        if ($peran === 'admin')        $model->updateAdmin($id, $d['nama']);
        elseif ($peran === 'penjual')  $model->updateProfil($id, $d);
        else                           $model->updateProfil($id, $d);
        if ($pwBaru !== '') $model->updatePassword($id, $pwBaru);

        $_SESSION['user']['nama'] = $d['nama'];
        if ($peran === 'penjual') $_SESSION['user']['toko'] = $d['namaToko'];
        $this->success('Profil berhasil diubah.');
        $this->redirect('/profil');
    }

    private function getDataProfil(int $id, string $peran): array
    {
        return Akun::forPeran($peran)->getById($id) ?? [];
    }

    private function validasiDataProfil(array $d, string $peran): array
    {
        $e = [];
        if ($d['nama'] === '') $e['nama'] = 'Nama wajib diisi.';
        if ($peran !== 'admin') {
            if ($d['alamat'] === '') $e['alamat'] = 'Alamat wajib diisi.';
            if (!preg_match('/^(\+62|62|0)[0-9]{8,13}$/', $d['noHp'])) $e['noHp'] = 'Format nomor HP salah.';
        }
        if ($peran === 'penjual' && $d['namaToko'] === '') $e['namaToko'] = 'Nama toko wajib diisi.';
        return $e;
    }
}
