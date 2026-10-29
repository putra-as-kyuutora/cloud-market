<?php
namespace App\Controllers;

use App\Core\{Config, Controller, Request};
use App\Models\{Barang, Kategori};

class CariBarangController extends Controller
{
    /** Filter kosong tidak disertakan dalam query. */
    public function cariBarang(): void
    {
        $filter = [
            'q' => Request::str('q'), 'kategori' => Request::int('kategori'),
            'min' => Request::str('min'), 'max' => Request::str('max'),
            'urut' => Request::str('urut', 'terbaru'),
        ];
        $hasil = (new Barang())->search($filter, Request::int('page', 1), (int)Config::get('per_page', 12));
        $this->view('barang/cari', [
            'filter' => $filter, 'hasil' => $hasil, 'kategori' => (new Kategori())->getAll(),
        ]);
    }
}
