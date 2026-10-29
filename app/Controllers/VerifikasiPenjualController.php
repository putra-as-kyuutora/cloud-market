<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\SellerProfile;

class VerifikasiPenjualController extends Controller
{
    /**
     * Daftar semua pengajuan verifikasi penjual (Admin)
     */
    public function index(): void
    {
        $statusFilter = Request::str('status', 'pending');
        $model = new SellerProfile();

        $daftar = $model->getVerifikasiList($statusFilter !== 'semua' ? $statusFilter : null);
        $pendingCount = $model->hitungPending();

        $this->view('admin/verifikasi_penjual', [
            'daftar'        => $daftar,
            'statusFilter'  => $statusFilter,
            'pendingCount'  => $pendingCount,
        ], 'layouts/admin');
    }

    /**
     * Detail pengajuan verifikasi toko (Admin)
     */
    public function detail(string $id): void
    {
        $idUser = (int)$id;
        $model = new SellerProfile();
        $pengajuan = $model->getDetailVerifikasi($idUser);

        if (!$pengajuan) {
            $this->notFound('Data pengajuan verifikasi toko tidak ditemukan.');
        }

        $this->view('admin/verifikasi_detail', [
            'p' => $pengajuan,
        ], 'layouts/admin');
    }

    /**
     * Admin Menyetujui Pengajuan Toko
     */
    public function setujui(string $id): void
    {
        $idUser = (int)$id;
        $adminId = Auth::id();

        (new SellerProfile())->setujui($idUser, $adminId);

        $this->success('Toko berhasil disetujui! Penjual sekarang dapat mengunggah produk dan berjualan.');
        $this->redirect('/admin/verifikasi-penjual?status=approved');
    }

    /**
     * Admin Menolak Pengajuan Toko
     */
    public function tolak(string $id): void
    {
        $idUser = (int)$id;
        $adminId = Auth::id();
        $alasan = trim(Request::str('alasanPenolakan'));

        if ($alasan === '') {
            $alasan = 'Foto KTP / data identitas tidak jelas atau tidak sesuai ketentuan.';
        }

        (new SellerProfile())->tolak($idUser, $adminId, $alasan);

        $this->error('Pengajuan toko telah ditolak dengan alasan: ' . $alasan);
        $this->redirect('/admin/verifikasi-penjual?status=rejected');
    }
}
