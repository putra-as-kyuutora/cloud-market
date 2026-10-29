<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Upload};
use App\Models\{Barang, Kategori};

class EditBarangController extends Controller
{
    /**
     * Halaman "Pilih Produk Untuk di Edit" (Alab/edit-produk.html)
     */
    public function pilihProduk(): void
    {
        $penjualId = Auth::id();
        $barangList = (new Barang())->getByPenjual($penjualId);
        $user = Auth::user();

        $this->view('barang/pilih_edit', [
            'barang' => $barangList,
            'user'   => $user,
        ], 'layouts/penjual');
    }

    /**
     * Halaman form edit setelah memilih produk (Alab/edit-produk-setelah-klik.html)
     */
    public function form(string $id): void
    {
        $barang = $this->getDataBarang((int)$id);
        $this->view('barang/edit_form', [
            'barang'   => $barang,
            'kategori' => (new Kategori())->getAll(),
            'aksi'     => url("/penjual/barang/$id/edit")
        ], 'layouts/penjual');
    }

    public function editBarang(string $id): void
    {
        $barang = $this->getDataBarang((int)$id);
        $tambah = new TambahBarangController();
        $d = $tambah->ambilInput();
        $errors = $tambah->validasiDataBarang($d);

        [$baru, $errUp] = Upload::image('gambar', 'barang');
        if ($errUp) $errors['gambar'] = $errUp;

        if ($errors) {
            Upload::delete($baru);
            $this->backWithErrors("/penjual/barang/$id/edit", $errors);
        }
        $d['gambar'] = $baru ?: $barang['gambar'];
        (new Barang())->update((int)$id, $d);
        if ($baru) Upload::delete($barang['gambar']);
        $this->success('Perubahan barang disimpan.');
        $this->redirect('/penjual/barang/edit');
    }

    /** Ambil barang & pastikan milik penjual yang login. */
    private function getDataBarang(int $id): array
    {
        $b = (new Barang())->getById($id);
        if (!$b) $this->notFound('Barang tidak ditemukan.');
        if ((int)$b['idPenjual'] !== Auth::id()) $this->forbidden('Barang ini bukan milik Anda.');
        return $b;
    }
}
