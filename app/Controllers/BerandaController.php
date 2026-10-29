<?php
namespace App\Controllers;

use App\Core\{Auth, Config, Controller};
use App\Models\{Barang, Kategori};

class BerandaController extends Controller
{
    public function index(): void
    {

        $hasil = (new Barang())->search(['hanyaTersedia' => 1], 1, 8);
        $this->view('beranda/index', [
            'kategori' => (new Kategori())->getAll(),
            'barang'   => $hasil['items'],
        ], 'layouts/beranda');
    }
}
