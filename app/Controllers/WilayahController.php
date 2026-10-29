<?php
namespace App\Controllers;

use App\Core\{Controller, Request};
use App\Models\Kodepos;

class WilayahController extends Controller
{
    private Kodepos $model;

    public function __construct()
    {
        $this->model = new Kodepos();
    }

    public function provinsi(): void
    {
        $rows = $this->model->getProvinsi();
        $this->json([
            'sukses' => true,
            'data'   => array_column($rows, 'provinsi')
        ]);
    }

    public function kabupaten(): void
    {
        $prov = trim(Request::str('provinsi'));
        if ($prov === '') {
            $this->json(['sukses' => false, 'pesan' => 'Provinsi wajib diisi', 'data' => []], 400);
        }
        $rows = $this->model->getKabupaten($prov);
        $this->json([
            'sukses' => true,
            'data'   => array_column($rows, 'kabupaten')
        ]);
    }

    public function kecamatan(): void
    {
        $kab = trim(Request::str('kabupaten'));
        if ($kab === '') {
            $this->json(['sukses' => false, 'pesan' => 'Kabupaten wajib diisi', 'data' => []], 400);
        }
        $rows = $this->model->getKecamatan($kab);
        $this->json([
            'sukses' => true,
            'data'   => array_column($rows, 'kecamatan')
        ]);
    }

    public function desa(): void
    {
        $kec = trim(Request::str('kecamatan'));
        $kab = trim(Request::str('kabupaten'));
        if ($kec === '') {
            $this->json(['sukses' => false, 'pesan' => 'Kecamatan wajib diisi', 'data' => []], 400);
        }
        $rows = $this->model->getDesa($kec, $kab ?: null);
        $this->json([
            'sukses' => true,
            'data'   => $rows
        ]);
    }

    public function cariKodePos(): void
    {
        $kode = trim(Request::str('kode'));
        if (strlen($kode) < 5) {
            $this->json(['sukses' => false, 'pesan' => 'Kode pos harus 5 digit', 'data' => []], 400);
        }

        $rows = $this->model->getByKodePos($kode);
        if (empty($rows)) {
            $this->json(['sukses' => false, 'pesan' => 'Kode pos tidak ditemukan di database.', 'data' => []]);
        }

        $first = $rows[0];
        $this->json([
            'sukses'    => true,
            'kode_pos'  => $first['kode_pos'],
            'provinsi'  => $first['provinsi'],
            'kabupaten' => $first['kabupaten'],
            'kecamatan' => $first['kecamatan'],
            'desa_list' => array_values(array_unique(array_column($rows, 'desa'))),
            'semua'     => $rows
        ]);
    }
}
