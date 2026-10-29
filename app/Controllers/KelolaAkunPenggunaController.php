<?php
namespace App\Controllers;

use App\Core\{Controller, Request};
use App\Models\User;

class KelolaAkunPenggunaController extends Controller
{
    public function index(): void
    {
        $q = trim(Request::str('q'));
        $this->view('admin/akun', [
            'pengguna'            => $this->cariPengguna($q),
            'q'                   => $q,
            'bisaNonaktifPembeli' => true,
        ], 'layouts/admin');
    }

    public function ubahStatus(string $peran, string $id): void
    {
        $idUser = (int)$id;
        $aktif = Request::str('aksi') === 'aktifkan';

        (new User())->updateStatusAktif($idUser, $aktif ? 1 : 0);

        $this->success($aktif ? 'Akun pengguna berhasil diaktifkan.' : 'Akun pengguna berhasil dinonaktifkan.');
        $this->redirect('/admin/akun?q=' . urlencode(Request::str('q')));
    }

    private function cariPengguna(string $q): array
    {
        $all = (new User())->allUsers();
        $rows = [];

        foreach ($all as $u) {
            $id = (int)$u['idUser'];
            if (!empty($u['is_admin'])) {
                continue; // Jangan tampilkan admin utama di daftar akun pengguna biasa
            }

            if ($q !== '') {
                $match = (stripos($u['nama'], $q) !== false)
                    || (stripos($u['username'], $q) !== false)
                    || (stripos($u['email'] ?? '', $q) !== false)
                    || ((string)$id === ltrim($q, '#Uu'));
                if (!$match) continue;
            }

            $peranTampil = 'pembeli';
            if (!empty($u['is_seller'])) {
                $peranTampil = 'penjual';
            }

            $rows[] = [
                'peran'            => $peranTampil,
                'id'               => $id,
                'kode'             => 'U' . $id,
                'username'         => $u['username'],
                'email'            => $u['email'] ?? '',
                'nama'             => $u['nama'],
                'noHp'             => $u['noHp'] ?? '',
                'toko'             => $u['namaToko'] ?? null,
                'is_seller'        => !empty($u['is_seller']),
                'statusVerifikasi' => $u['statusVerifikasi'] ?? null,
                'aktif'            => (int)($u['aktif'] ?? 1) === 1,
            ];
        }

        return $rows;
    }
}
