<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request, Upload};
use App\Models\{SellerProfile, User};

class BukaTokoController extends Controller
{
    /**
     * Form Pendaftaran Toko / Upgrade jadi Penjual
     */
    public function form(): void
    {
        $userId = Auth::id();
        if (!$userId) $this->redirect('/login');

        // Jika sudah disetujui, langsung arahkan ke dashboard barang
        if (Auth::isSellerApproved()) {
            $this->redirect('/penjual/barang');
        }

        $sellerProfile = (new SellerProfile())->getByUserId($userId);
        if ($sellerProfile && $sellerProfile['statusVerifikasi'] === 'pending') {
            $this->redirect('/penjual/status-verifikasi');
        }

        $user = (new User())->getById($userId);

        $this->view('penjual/buka_toko', [
            'user'          => $user,
            'sellerProfile' => $sellerProfile,
            'errors'        => [],
            'old'           => $sellerProfile ?? [],
        ]);
    }

    /**
     * Proses pengajuan buka toko & verifikasi KTP
     */
    public function daftar(): void
    {
        $userId = Auth::id();
        if (!$userId) $this->redirect('/login');

        $namaToko      = trim(Request::str('namaToko'));
        $deskripsiToko = trim(Request::str('deskripsiToko'));
        $noKtp         = trim(Request::str('noKtp'));
        $namaBank      = trim(Request::str('namaBank'));
        $noRekening    = trim(Request::str('noRekening'));
        $atasNama      = trim(Request::str('atasNamaRekening'));

        $sellerModel = new SellerProfile();
        $existing = $sellerModel->getByUserId($userId);

        $errors = [];
        if ($namaToko === '') {
            $errors['namaToko'] = 'Nama toko wajib diisi.';
        } elseif (strlen($namaToko) < 3 || strlen($namaToko) > 100) {
            $errors['namaToko'] = 'Nama toko minimal 3 dan maksimal 100 karakter.';
        }

        if ($noKtp === '') {
            $errors['noKtp'] = 'Nomor Induk Kependudukan (NIK/KTP) wajib diisi.';
        } elseif (!preg_match('/^[0-9]{16}$/', $noKtp)) {
            $errors['noKtp'] = 'Nomor KTP harus 16 digit angka.';
        }

        // Upload Foto KTP
        [$pathKtp, $errKtp] = Upload::image('fotoKtp', 'ktp');
        if ($errKtp) {
            $errors['fotoKtp'] = $errKtp;
        } elseif (!$pathKtp && empty($existing['fotoKtp'])) {
            $errors['fotoKtp'] = 'Foto KTP wajib diunggah.';
        }

        // Upload Selfie dengan KTP
        [$pathSelfie, $errSelfie] = Upload::image('fotoSelfie', 'ktp');
        if ($errSelfie) {
            $errors['fotoSelfie'] = $errSelfie;
        } elseif (!$pathSelfie && empty($existing['fotoSelfie'])) {
            $errors['fotoSelfie'] = 'Foto selfie memegang KTP wajib diunggah.';
        }

        if ($errors) {
            $this->backWithErrors('/penjual/daftar', $errors);
        }

        // Simpan data profil penjual & ajukan verifikasi
        $dataSimpan = [
            'namaToko'         => $namaToko,
            'deskripsiToko'    => $deskripsiToko,
            'noKtp'            => $noKtp,
            'namaBank'         => $namaBank,
            'noRekening'       => $noRekening,
            'atasNamaRekening' => $atasNama,
        ];
        if ($pathKtp) {
            $dataSimpan['fotoKtp'] = $pathKtp;
            if (!empty($existing['fotoKtp']) && $existing['fotoKtp'] !== $pathKtp) {
                Upload::delete($existing['fotoKtp']);
            }
        }
        if ($pathSelfie) {
            $dataSimpan['fotoSelfie'] = $pathSelfie;
            if (!empty($existing['fotoSelfie']) && $existing['fotoSelfie'] !== $pathSelfie) {
                Upload::delete($existing['fotoSelfie']);
            }
        }

        $sellerModel->ajukan($userId, $dataSimpan);

        // Perbarui sesi pengguna
        Auth::refresh();

        $this->success('Pengajuan pendaftaran toko Anda berhasil dikirim! Tim Admin akan memverifikasi berkas KTP Anda dalam 1-3 hari kerja.');
        $this->redirect('/penjual/status-verifikasi');
    }

    /**
     * Halaman Status Verifikasi Toko
     */
    public function status(): void
    {
        $userId = Auth::id();
        if (!$userId) $this->redirect('/login');

        $sellerProfile = (new SellerProfile())->getByUserId($userId);
        if (!$sellerProfile) {
            $this->redirect('/penjual/daftar');
        }

        $this->view('penjual/status_verifikasi', [
            'sellerProfile' => $sellerProfile,
            'user'          => (new User())->getById($userId),
        ]);
    }
}
