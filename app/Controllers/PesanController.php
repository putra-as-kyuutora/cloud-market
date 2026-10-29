<?php
namespace App\Controllers;

use App\Core\{Auth, Controller, Request};
use App\Models\{Admin, Barang, Pembeli, Penjual, Pesan};

class PesanController extends Controller
{
    /**
     * Tampilan utama Cloud Message - Nego (Alab/nego-chat.html)
     */
    public function index(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect('/login');
        }

        $userId = Auth::id();
        $peran  = Auth::peran();

        $pesanModel  = new Pesan();
        $barangModel = new Barang();

        // Parameter dari query string
        $idBarangParam     = Request::int('barang');
        $negoParam         = Request::int('nego') === 1;
        $tawaranParam      = Request::str('tawaran');
        $partnerIdParam    = Request::int('partner');
        $partnerPeranParam = Request::str('peran_partner');

        $barangTarget = $idBarangParam ? $barangModel->getById($idBarangParam) : null;

        // Jika pembeli klik Nego/Pesan dari halaman barang, otomatis partner adalah penjual barang
        if ($barangTarget && !$partnerIdParam && $peran === 'pembeli') {
            $partnerIdParam    = (int)$barangTarget['idPenjual'];
            $partnerPeranParam = 'penjual';
        }

        // Ambil daftar percakapan aktif dari database
        $percakapan = $pesanModel->getPercakapan($userId, $peran);

        // Tentukan partner aktif
        if ($partnerIdParam > 0) {
            $partnerAktifId    = $partnerIdParam;
            $partnerAktifPeran = $partnerPeranParam ?: ($peran === 'pembeli' ? 'penjual' : 'pembeli');
        } else {
            $partnerAktifId    = !empty($percakapan[0]['partnerId']) ? (int)$percakapan[0]['partnerId'] : null;
            $partnerAktifPeran = !empty($percakapan[0]['partnerPeran']) ? $percakapan[0]['partnerPeran'] : null;
        }

        // Ambil info detail partner aktif
        $partnerData = null;
        if ($partnerAktifId && $partnerAktifPeran === 'penjual') {
            $partnerData = (new Penjual())->getById($partnerAktifId);
        } elseif ($partnerAktifId && $partnerAktifPeran === 'pembeli') {
            $partnerData = (new Pembeli())->getById($partnerAktifId);
        } elseif ($partnerAktifId && $partnerAktifPeran === 'admin') {
            $partnerData = (new Admin())->getById($partnerAktifId);
        }

        // Jika data partner tidak ditemukan di DB, batalkan seleksi partner aktif
        if ($partnerAktifId && !$partnerData) {
            $partnerAktifId = null;
            $partnerAktifPeran = null;
        }

        // Jika partner aktif valid tapi belum ada di riwayat obrolan, masukkan sementara di posisi paling atas
        if ($partnerAktifId && $partnerData) {
            $adaDiList = false;
            foreach ($percakapan as $c) {
                if ((int)$c['partnerId'] === $partnerAktifId && $c['partnerPeran'] === $partnerAktifPeran) {
                    $adaDiList = true;
                    break;
                }
            }
            if (!$adaDiList) {
                $namaTampil = !empty($partnerData['namaToko'])
                    ? $partnerData['namaToko'] . ' (' . $partnerData['nama'] . ')'
                    : ($partnerData['nama'] ?? 'Pengguna');
                array_unshift($percakapan, [
                    'partnerId'    => $partnerAktifId,
                    'partnerPeran' => $partnerAktifPeran,
                    'partnerNama'  => $namaTampil,
                    'partnerToko'  => $partnerData['namaToko'] ?? '',
                    'pesanTerakhir'=> '(Percakapan baru)',
                    'waktu'        => date('Y-m-d H:i:s'),
                    'idBarang'     => $barangTarget['idBarang'] ?? null,
                    'namaBarang'   => $barangTarget['namaBarang'] ?? null,
                    'hargaBarang'  => $barangTarget['harga'] ?? null,
                    'gambarBarang' => $barangTarget['gambar'] ?? null,
                    'hargaNego'    => null,
                ]);
            }
        }

        // Ambil riwayat chat jika partner aktif ada
        $histori = [];
        if ($partnerAktifId && $partnerAktifPeran) {
            $histori = $pesanModel->getHistori($userId, $peran, $partnerAktifId, $partnerAktifPeran);
        }

        // Tentukan barang yang ditampilkan pada Kartu Nego
        // HANYA tampil jika eksplisit ada barang target atau ada barang yang dibicarakan dalam riwayat chat
        $barangNego = $barangTarget;
        if (!$barangNego && !empty($histori)) {
            foreach (array_reverse($histori) as $h) {
                if (!empty($h['idBarang'])) {
                    $barangNego = $barangModel->getById((int)$h['idBarang']);
                    if ($barangNego) break;
                }
            }
        }

        // Tentukan harga dan status nego terakhir (spesifik untuk barang terkait)
        $hargaNegoTerakhir  = null;
        $statusNegoTerakhir = null;
        if (!empty($histori) && $barangNego) {
            foreach (array_reverse($histori) as $h) {
                if (!empty($h['hargaNego']) && (!empty($h['idBarang']) && (int)$h['idBarang'] === (int)$barangNego['idBarang'])) {
                    $hargaNegoTerakhir  = (float)$h['hargaNego'];
                    $statusNegoTerakhir = $h['statusNego'] ?? null;
                    break;
                }
            }
        }
        if ($hargaNegoTerakhir === null && $negoParam && !empty($tawaranParam)) {
            $hargaNegoTerakhir  = (float)$tawaranParam;
            $statusNegoTerakhir = 'draft';
        }

        $layout = ($peran === 'penjual') ? 'layouts/penjual' : ($peran === 'admin' ? 'layouts/admin' : 'layouts/main');

        $this->view('pesan/index', [
            'user'               => $user,
            'peran'              => $peran,
            'percakapan'         => $percakapan,
            'partnerAktifId'     => $partnerAktifId,
            'partnerAktifPeran'  => $partnerAktifPeran,
            'partnerData'        => $partnerData,
            'histori'            => $histori,
            'barangNego'         => $barangNego,
            'hargaNegoTerakhir'  => $hargaNegoTerakhir,
            'statusNegoTerakhir' => $statusNegoTerakhir,
            'isNegoMode'         => $negoParam,
            'tawaranParam'       => $tawaranParam,
        ], $layout);
    }

    /**
     * Kirim pesan baru
     */
    public function kirim(): void
    {
        $userId = Auth::id();
        $peran  = Auth::peran();
        if (!$userId) $this->redirect('/login');

        $idPenerima    = Request::int('idPenerima');
        $peranPenerima = Request::str('peranPenerima');
        $idBarang      = Request::int('idBarang');
        $isiPesan      = trim(Request::str('isiPesan'));
        $hargaNegoRaw  = trim(Request::str('hargaNego'));

        if ($idPenerima <= 0 || empty($peranPenerima)) {
            $this->error('Tujuan penerima pesan tidak valid.');
            $this->redirect('/pesan');
        }

        if ($isiPesan === '' && $hargaNegoRaw === '') {
            $this->redirect("/pesan?partner=$idPenerima&peran_partner=$peranPenerima" . ($idBarang ? "&barang=$idBarang" : ""));
        }

        $hargaNego = null;
        $statusNego = null;
        if ($hargaNegoRaw !== '' && is_numeric($hargaNegoRaw) && (float)$hargaNegoRaw > 0) {
            $hargaNego = (float)$hargaNegoRaw;
            $statusNego = 'pending';
        }

        $d = [
            'idPengirim'    => $userId,
            'peranPengirim' => $peran,
            'idPenerima'    => $idPenerima,
            'peranPenerima' => $peranPenerima,
            'idBarang'      => $idBarang ?: null,
            'isiPesan'      => $isiPesan !== '' ? $isiPesan : ($hargaNego ? 'Saya mengajukan penawaran harga Rp ' . number_format($hargaNego, 0, ',', '.') : ''),
            'hargaNego'     => $hargaNego,
            'statusNego'    => $statusNego,
        ];

        (new Pesan())->kirim($d);

        $this->redirect("/pesan?partner=$idPenerima&peran_partner=$peranPenerima" . ($idBarang ? "&barang=$idBarang" : ""));
    }

    /**
     * Terima / tolak nego
     */
    public function statusNego(): void
    {
        $userId = Auth::id();
        if (!$userId) $this->redirect('/login');

        $idPesan      = Request::int('idPesan');
        $status       = Request::str('status'); // 'diterima' | 'ditolak'
        $partnerId    = Request::int('partner');
        $partnerPeran = Request::str('peran_partner');

        if (in_array($status, ['diterima', 'ditolak'], true)) {
            $pesanModel = new Pesan();
            $pesan = $pesanModel->getById($idPesan);
            if ($pesan) {
                $pesanModel->updateStatusNego($idPesan, $status);
                $this->success("Penawaran nego telah " . ($status === 'diterima' ? 'disetujui' : 'ditolak') . ".");
            }
        }

        $this->redirect("/pesan?partner=$partnerId&peran_partner=$partnerPeran");
    }
}
