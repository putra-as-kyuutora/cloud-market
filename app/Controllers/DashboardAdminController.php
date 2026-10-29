<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Barang, SellerProfile, Transaksi, User};

class DashboardAdminController extends Controller
{
    public function index(): void
    {
        $t = new Transaksi();
        $userModel = new User();
        $sellerProfModel = new SellerProfile();

        $allUsers = $userModel->allUsers();
        $totalPengguna = count(array_filter($allUsers, fn($u) => empty($u['is_admin'])));

        $this->view('admin/dashboard', [
            'totalTransaksi'    => $t->count(),
            'produkAktif'       => (new Barang())->countTersedia(),
            'totalPengguna'     => $totalPengguna,
            'pendingVerifikasi' => $sellerProfModel->hitungPending(),
            'transaksiBaru'     => $t->terbaru(5),
            'barangBaru'        => (new Barang())->terbaru(5),
            'penggunaBaru'      => array_slice($allUsers, 0, 5),
        ], 'layouts/admin');
    }
}
