<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\{DetailTransaksi, Transaksi};

/** Sisi pembeli (Pesanan Saya, status) dan sisi penjual (Pesanan Masuk, kirim). */
class PesananController extends Controller
{
    // ---------- Pembeli ----------
    public function saya(): void
    {
        $this->view('transaksi/pesanan_saya', ['pesanan' => (new Transaksi())->getByPembeli(Auth::id())]);
    }

    public function status(string $id): void
    {
        $t = $this->milikPembeli((int)$id);
        $this->view('transaksi/status', [
            'transaksi' => $t, 'items' => (new DetailTransaksi())->getByTransaksi((int)$id),
            'kelompok' => $this->kelompokStatus($t['statusPembayaran']),
        ]);
    }

    public function diterima(string $id): void
    {
        $t = $this->milikPembeli((int)$id);
        if ($t['statusPengiriman'] !== Transaksi::KIRIM_DIKIRIM) {
            $this->error('Pesanan belum dikirim.'); $this->redirect("/pesanan/$id");
        }
        (new Transaksi())->updateStatusPengiriman((int)$id, Transaksi::KIRIM_TERIMA);
        $this->success('Terima kasih! Pesanan ditandai diterima.');
        $this->redirect("/pesanan/$id");
    }

    // ---------- Penjual ----------
    public function masuk(): void
    {
        $rows = (new Transaksi())->getLaporan(['idPenjual' => Auth::id()]);
        $rows = array_values(array_filter($rows, fn($r) => $r['statusPembayaran'] !== Transaksi::BAYAR_BATAL));
        $this->view('transaksi/pesanan_masuk', ['pesanan' => $rows], 'layouts/penjual');
    }

    public function kirim(string $id): void
    {
        $m = new Transaksi();
        $t = $m->getById((int)$id);
        if (!$t || !$m->milikPenjual((int)$id, Auth::id())) $this->forbidden();
        if ($t['statusPembayaran'] !== Transaksi::BAYAR_LUNAS || $t['statusPengiriman'] !== Transaksi::KIRIM_PROSES) {
            $this->error('Pesanan belum siap dikirim.'); $this->redirect('/penjual/pesanan');
        }
        $m->updateStatusPengiriman((int)$id, Transaksi::KIRIM_DIKIRIM);
        $this->success('Pesanan ditandai sudah dikirim.');
        $this->redirect('/penjual/pesanan');
    }

    /** menunggu | berhasil | batal  (3 tampilan di desain referensi) */
    private function kelompokStatus(string $s): string
    {
        if ($s === Transaksi::BAYAR_LUNAS) return 'berhasil';
        if ($s === Transaksi::BAYAR_BATAL) return 'batal';
        return 'menunggu';
    }

    private function milikPembeli(int $id): array
    {
        $t = (new Transaksi())->getById($id);
        if (!$t) $this->notFound('Pesanan tidak ditemukan.');
        if ((int)$t['idPembeli'] !== Auth::id()) $this->forbidden('Pesanan ini bukan milik Anda.');
        return $t;
    }
}
