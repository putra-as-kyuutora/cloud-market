<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Barang;

class LihatInformasiBarangController extends Controller
{
    public function detail(string $id): void
    {
        $barang = $this->getDetailBarang((int)$id);
        if (!$barang) $this->notFound('Barang tidak tersedia.');
        $this->view('barang/detail', ['barang' => $barang]);
    }

    private function getDetailBarang(int $id): ?array { return (new Barang())->getById($id); }
}
