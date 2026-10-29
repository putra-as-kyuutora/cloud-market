<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request, Upload};
use App\Models\{Barang, Kategori};

class TambahBarangController extends Controller
{
    public function form(): void
    {
        $this->view('barang/form', ['barang' => null, 'kategori' => (new Kategori())->getAll(), 'aksi' => url('/penjual/barang/tambah')], 'layouts/penjual');
    }

    public function tambahBarang(): void
    {
        $d = $this->ambilInput();
        $errors = $this->validasiDataBarang($d);

        [$gambar, $errUp] = Upload::image('gambar', 'barang');
        if ($errUp) $errors['gambar'] = $errUp;
        elseif (!$gambar && !$errors) $errors['gambar'] = 'Foto barang wajib diunggah.';
        elseif (!$gambar) $errors['gambar'] = 'Foto barang wajib diunggah.';

        if ($errors) {
            Upload::delete($gambar);
            $this->backWithErrors('/penjual/barang/tambah', $errors);
        }
        $d['gambar'] = $gambar;
        $d['idPenjual'] = Auth::id();
        (new Barang())->add($d);
        $this->success('Barang berhasil ditambahkan.');
        $this->redirect('/penjual/barang');
    }

    public function ambilInput(): array
    {
        return [
            'namaBarang' => Request::str('namaBarang'), 'idKategori' => Request::int('idKategori'),
            'harga' => Request::str('harga'), 'stok' => Request::str('stok'), 'deskripsi' => Request::str('deskripsi'),
        ];
    }

    /** Dipakai juga oleh EditBarangController. */
    public function validasiDataBarang(array $d): array
    {
        $e = [];
        if ($d['namaBarang'] === '') $e['namaBarang'] = 'Nama barang wajib diisi.';
        if (!(new Kategori())->getById((int)$d['idKategori'])) $e['idKategori'] = 'Pilih kategori yang valid.';
        if (!is_numeric($d['harga']) || (float)$d['harga'] <= 0) $e['harga'] = 'Harga harus lebih dari 0.';
        if (!ctype_digit((string)$d['stok'])) $e['stok'] = 'Stok harus angka 0 atau lebih.';
        if ($d['deskripsi'] === '') $e['deskripsi'] = 'Deskripsi/kondisi barang wajib diisi.';
        return $e;
    }
}
