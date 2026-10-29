<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\{Penjual, Transaksi};

/** Dipakai Admin (semua toko / pilih toko) dan Penjual (hanya tokonya). */
class LihatLaporanTransaksiController extends Controller
{
    public function index(): void
    {
        $admin = Auth::is('admin');
        $filter = [
            'dari' => Request::str('dari'), 'sampai' => Request::str('sampai'),
            'status' => Request::str('status'), 'q' => Request::str('q'),
            'idPenjual' => $admin ? Request::int('toko') : Auth::id(),
        ];
        $rows = (new Transaksi())->getLaporan($filter);
        $this->view('transaksi/laporan', [
            'filter' => $filter, 'rows' => $rows,
            'totalPendapatan' => Transaksi::hitungTotalPendapatan($rows),
            'semuaStatus' => Transaksi::SEMUA_BAYAR,
            'daftarToko' => $admin ? (new Penjual())->getAllSimple() : [],
            'adalahAdmin' => $admin,
        ], $admin ? 'layouts/admin' : 'layouts/penjual');
    }
}
