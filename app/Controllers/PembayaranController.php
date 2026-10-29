<?php
namespace App\Controllers;

use App\Core\{Auth, Config, Controller, Database, Upload};
use App\Models\{Barang, DetailTransaksi, Transaksi};

class PembayaranController extends Controller
{
    private const BISA_BAYAR = [Transaksi::BAYAR_MENUNGGU, Transaksi::BAYAR_DITOLAK];

    public function form(string $id): void
    {
        $t = $this->milik((int)$id);
        $cfg = Config::get('pembayaran')[$t['metodePembayaran']] ?? ['butuh_bukti' => false, 'instruksi' => ''];
        $this->view('transaksi/pembayaran', [
            'transaksi' => $t, 'items' => (new DetailTransaksi())->getByTransaksi((int)$id),
            'instruksi' => $this->getInstruksiPembayaran($t['metodePembayaran']), 'butuhBukti' => $cfg['butuh_bukti'],
            'bisaBayar' => in_array($t['statusPembayaran'], self::BISA_BAYAR, true),
        ]);
    }

    /** Pembeli menyatakan sudah membayar (+ bukti) -> status Menunggu Konfirmasi. */
    public function prosesPembayaran(string $id): void
    {
        $t = $this->milik((int)$id);
        if (!in_array($t['statusPembayaran'], self::BISA_BAYAR, true)) {
            $this->error('Pesanan ini tidak bisa dibayar lagi.'); $this->redirect("/pesanan/$id");
        }
        $butuh = Config::get('pembayaran')[$t['metodePembayaran']]['butuh_bukti'] ?? false;
        $bukti = null;
        if ($butuh) {
            [$bukti, $err] = Upload::image('bukti', 'bukti');
            if ($err || !$bukti) $this->backWithErrors("/pesanan/$id/bayar", ['bukti' => $err ?: 'Bukti pembayaran wajib diunggah.']);
        }
        $m = new Transaksi();
        if ($bukti) { Upload::delete($t['buktiPembayaran']); $m->setBukti((int)$id, $bukti); }
        $m->updateStatusPembayaran((int)$id, Transaksi::BAYAR_KONFIRMASI);
        $this->success('Pembayaran dikirim. Menunggu konfirmasi penjual.');
        $this->redirect("/pesanan/$id");
    }

    /** Batal: status Dibatalkan + stok dikembalikan. */
    public function batalkanPembayaran(string $id): void
    {
        $t = $this->milik((int)$id);
        if (!in_array($t['statusPembayaran'], self::BISA_BAYAR, true)) {
            $this->error('Pesanan ini tidak bisa dibatalkan.'); $this->redirect("/pesanan/$id");
        }
        Database::transaction(function () use ($t, $id) {
            (new Transaksi())->updateStatusPembayaran((int)$id, Transaksi::BAYAR_BATAL);
            $barang = new Barang();
            foreach ((new DetailTransaksi())->getByTransaksi((int)$id) as $it) {
                $barang->tambahStok((int)$it['idBarang'], (int)$it['jumlah']);
            }
        });
        $this->success('Pesanan dibatalkan.');
        $this->redirect("/pesanan/$id");
    }

    private function getInstruksiPembayaran(string $metode): string
    {
        return Config::get('pembayaran')[$metode]['instruksi'] ?? '';
    }

    private function milik(int $id): array
    {
        $t = (new Transaksi())->getById($id);
        if (!$t) $this->notFound('Pesanan tidak ditemukan.');
        if ((int)$t['idPembeli'] !== Auth::id()) $this->forbidden('Pesanan ini bukan milik Anda.');
        return $t;
    }
}
