<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\{DetailTransaksi, Transaksi};

class KonfirmasiPembayaranController extends Controller
{
    public function index(): void
    {
        $status = Request::str('status', Transaksi::BAYAR_KONFIRMASI);
        $filter = ['idPenjual' => Auth::id(), 'status' => $status === 'semua' ? '' : $status];
        $this->view('transaksi/konfirmasi', [
            'daftar' => (new Transaksi())->getLaporan($filter),
            'statusDipilih' => $status, 'semuaStatus' => Transaksi::SEMUA_BAYAR,
        ], 'layouts/penjual');
    }

    public function konfirmasiPembayaran(string $id): void
    {
        $this->ubah((int)$id, Transaksi::BAYAR_LUNAS, 'Pembayaran dikonfirmasi. Pesanan diproses.');
    }

    public function tolakPembayaran(string $id): void
    {
        $this->ubah((int)$id, Transaksi::BAYAR_DITOLAK, 'Pembayaran ditolak. Pembeli diminta membayar ulang.');
    }

    private function ubah(int $id, string $status, string $pesan): void
    {
        $m = new Transaksi();
        $t = $m->getById($id);
        if (!$t || !$m->milikPenjual($id, Auth::id())) $this->forbidden();
        if ($t['statusPembayaran'] !== Transaksi::BAYAR_KONFIRMASI) {
            $this->error('Pesanan ini tidak sedang menunggu konfirmasi.'); $this->redirect('/penjual/konfirmasi');
        }
        $m->updateStatusPembayaran($id, $status);
        if ($status === Transaksi::BAYAR_LUNAS) $m->updateStatusPengiriman($id, Transaksi::KIRIM_PROSES);
        $this->success($pesan);
        $this->redirect('/penjual/konfirmasi');
    }
}
