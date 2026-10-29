<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\Kategori;

class KelolaKategoriController extends Controller
{
    public function index(): void
    {
        $this->view('admin/kategori', ['kategori' => $this->getSemuaKategori()], 'layouts/admin');
    }

    public function formTambah(): void
    {
        $this->view('admin/kategori_form', [
            'kategori' => null,
            'aksi' => url('/admin/kategori/tambah'),
            'punyaDeskripsi' => (new Kategori())->hasDeskripsi()
        ], 'layouts/admin');
    }

    public function tambahKategori(): void
    {
        $nama = Request::str('namaKategori');
        $err = $this->validasiNamaKategori($nama);
        if ($err) $this->backWithErrors('/admin/kategori/tambah', ['namaKategori' => $err]);
        (new Kategori())->add($nama, Auth::id(), Request::str('deskripsi') ?: null);
        $this->success('Kategori ditambahkan.');
        $this->redirect('/admin/kategori');
    }

    public function formEdit(string $id): void
    {
        $k = $this->ambil((int)$id);
        $m = new Kategori();
        $this->view('admin/kategori_form', [
            'kategori' => $k,
            'aksi' => url("/admin/kategori/$id/edit"),
            'punyaDeskripsi' => $m->hasDeskripsi(),
            'jumlahBarang' => $m->jumlahBarang((int)$id),
        ], 'layouts/admin');
    }

    public function editKategori(string $id): void
    {
        $this->ambil((int)$id);
        $nama = Request::str('namaKategori');
        $err = $this->validasiNamaKategori($nama, (int)$id);
        if ($err) $this->backWithErrors("/admin/kategori/$id/edit", ['namaKategori' => $err]);
        (new Kategori())->update((int)$id, $nama, Request::str('deskripsi') ?: null);
        $this->success('Kategori diperbarui.');
        $this->redirect('/admin/kategori');
    }

    public function konfirmasiHapus(string $id): void
    {
        $k = $this->ambil((int)$id);
        $this->view('shared/konfirmasi', [
            'judul' => 'Hapus kategori?', 'pesan' => "Kategori \"{$k['namaKategori']}\" akan dihapus.",
            'aksi' => url("/admin/kategori/$id/hapus"), 'batal' => url('/admin/kategori'), 'labelYa' => 'Ya, Hapus', 'bahaya' => true,
        ], 'layouts/admin');
    }

    public function hapusKategori(string $id): void
    {
        $m = new Kategori();
        $this->ambil((int)$id);
        if ($m->isUsedByBarang((int)$id)) {
            $this->error('Kategori masih digunakan oleh barang, penghapusan dibatalkan.');
        } else {
            $m->delete((int)$id);
            $this->success('Kategori dihapus.');
        }
        $this->redirect('/admin/kategori');
    }

    private function getSemuaKategori(): array { return (new Kategori())->getAllWithCount(); }

    private function validasiNamaKategori(string $nama, ?int $except = null): ?string
    {
        if (mb_strlen($nama) < 3) return 'Nama kategori minimal 3 karakter.';
        if ((new Kategori())->namaSudahAda($nama, $except)) return 'Nama kategori sudah ada.';
        return null;
    }

    private function ambil(int $id): array
    {
        $k = (new Kategori())->getById($id);
        if (!$k) $this->notFound('Kategori tidak ditemukan.');
        return $k;
    }
}
