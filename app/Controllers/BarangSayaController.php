<?php
namespace App\Controllers;

use App\Core\{Auth, Controller};
use App\Models\Barang;

/** Halaman "Produk Saya" milik penjual. */
class BarangSayaController extends Controller
{
    public function index(): void
    {
        $this->view('barang/saya', ['barang' => (new Barang())->getByPenjual(Auth::id())], 'layouts/penjual');
    }
}
