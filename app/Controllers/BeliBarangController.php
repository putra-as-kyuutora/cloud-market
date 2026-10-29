<?php
namespace App\Controllers;

use App\Core\{Auth, Config, Controller, Database, Request};
use App\Models\{Admin, Barang, DetailTransaksi, Pembeli, Transaksi};

class BeliBarangController extends Controller
{
    public function form(string $id): void
    {
        if (Auth::isAdmin()) {
            $this->error('Akun Administrator hanya dapat melihat katalog dan tidak dapat melakukan pembelian.');
            $this->redirect('/barang/' . $id);
        }

        $barang = (new Barang())->getById((int)$id);
        if (!$barang) $this->notFound('Barang tidak tersedia.');

        if ((int)$barang['idPenjual'] === Auth::id()) {
            $this->error('Anda tidak dapat membeli produk toko Anda sendiri.');
            $this->redirect('/barang/' . $id);
        }

        $pembeli = (new Pembeli())->getById(Auth::id());
        $negoDiterima = (new \App\Models\Pesan())->getNegoDiterima(Auth::id(), (int)$barang['idPenjual'], (int)$barang['idBarang']);
        $hargaSatuan = $negoDiterima ? (float)$negoDiterima['hargaNego'] : (float)$barang['harga'];
        $this->view('transaksi/checkout', [
            'barang' => $barang, 'pembeli' => $pembeli,
            'metode' => Config::get('pembayaran'), 'tersedia' => (int)$barang['stok'] > 0,
            'negoDiterima' => $negoDiterima, 'hargaSatuan' => $hargaSatuan,
        ]);
    }

    /** Orkestrator: cek stok -> buat transaksi -> detail -> kurangi stok (satu transaksi DB). */
    public function beliBarang(string $id): void
    {
        if (Auth::isAdmin()) {
            $this->error('Akun Administrator hanya dapat melihat katalog dan tidak dapat melakukan pembelian.');
            $this->redirect('/barang/' . $id);
        }

        $idBarang = (int)$id;
        $barang = (new Barang())->getById($idBarang);
        if (!$barang) $this->notFound('Barang tidak tersedia.');

        if ((int)$barang['idPenjual'] === Auth::id()) {
            $this->error('Anda tidak dapat membeli produk toko Anda sendiri.');
            $this->redirect('/barang/' . $id);
        }

        $jumlah   = Request::int('jumlah', 1);
        $alamat   = Request::str('alamatPengiriman');
        $metode   = Request::str('metodePembayaran');

        $errors = [];
        if ($jumlah < 1) $errors['jumlah'] = 'Jumlah minimal 1.';
        if ($alamat === '') $errors['alamatPengiriman'] = 'Alamat pengiriman wajib diisi.';
        if (!array_key_exists($metode, Config::get('pembayaran'))) $errors['metodePembayaran'] = 'Pilih metode pembayaran.';
        if ($errors) $this->backWithErrors("/beli/$idBarang", $errors);

        $idAdmin = (new Admin())->defaultId();
        if ($idAdmin === null) { $this->error('Sistem belum memiliki admin.'); $this->redirect("/beli/$idBarang"); }

        $idTransaksi = Database::transaction(function () use ($idBarang, $jumlah, $alamat, $metode, $idAdmin) {
            $barangModel = new Barang();
            $barang = $barangModel->getById($idBarang);
            if (!$barang) return null;
            if (!$this->cekKetersediaanStok($barangModel, $idBarang, $jumlah)) return false;

            $negoDiterima = (new \App\Models\Pesan())->getNegoDiterima(Auth::id(), (int)$barang['idPenjual'], $idBarang);
            $hargaSatuan = $negoDiterima ? (float)$negoDiterima['hargaNego'] : (float)$barang['harga'];

            $idT = $this->buatTransaksi(Auth::id(), $idAdmin, $alamat, $metode);
            $this->buatDetailTransaksi($idT, $idBarang, $jumlah, $hargaSatuan); // snapshot harga
            return $idT;
        });

        if ($idTransaksi === null) $this->notFound('Barang tidak tersedia.');
        if ($idTransaksi === false) {
            $this->error('Stok barang tidak mencukupi.');
            $this->redirect("/beli/$idBarang");
        }
        $this->success('Pesanan dibuat. Silakan selesaikan pembayaran.');
        $this->redirect("/pesanan/$idTransaksi/bayar");
    }

    /** Mengunci & mengurangi stok. Dipanggil di dalam transaksi DB. */
    private function cekKetersediaanStok(Barang $m, int $idBarang, int $jumlah): bool
    {
        return $m->kurangiStok($idBarang, $jumlah);
    }

    private function buatTransaksi(int $idPembeli, int $idAdmin, string $alamat, string $metode): int
    {
        return (new Transaksi())->create($idPembeli, $idAdmin, $alamat, $metode);
    }

    private function buatDetailTransaksi(int $idT, int $idBarang, int $jumlah, float $harga): void
    {
        (new DetailTransaksi())->add($idT, $idBarang, $jumlah, $harga);
    }
}
