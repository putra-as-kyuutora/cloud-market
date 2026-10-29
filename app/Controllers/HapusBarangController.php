<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Upload};
use App\Models\Barang;

class HapusBarangController extends Controller
{
    public function konfirmasiHapus(string $id): void
    {
        $b = $this->milik((int)$id);
        $this->view('shared/konfirmasi', [
            'judul' => 'Hapus barang?', 'pesan' => "Barang \"{$b['namaBarang']}\" akan dihapus permanen.",
            'aksi' => url("/penjual/barang/$id/hapus"), 'batal' => url('/penjual/barang'),
            'labelYa' => 'Ya, Hapus', 'bahaya' => true,
        ], 'layouts/penjual');
    }

    public function hapusBarang(string $id): void
    {
        $b = $this->milik((int)$id);
        try {
            (new Barang())->delete((int)$id);
        } catch (\PDOException $e) {
            // FK RESTRICT: barang sudah tercatat di DetailTransaksi
            if ($e->getCode() === '23000') {
                $this->error('Barang sudah pernah dipesan sehingga tidak bisa dihapus (riwayat transaksi dijaga). Ubah stok menjadi 0 sebagai gantinya.');
                $this->redirect('/penjual/barang');
            }
            throw $e;
        }
        Upload::delete($b['gambar']);
        $this->success('Barang berhasil dihapus.');
        $this->redirect('/penjual/barang');
    }

    private function milik(int $id): array
    {
        $b = (new Barang())->getById($id);
        if (!$b) $this->notFound('Barang tidak ditemukan.');
        if ((int)$b['idPenjual'] !== Auth::id()) $this->forbidden('Barang ini bukan milik Anda.');
        return $b;
    }
}
