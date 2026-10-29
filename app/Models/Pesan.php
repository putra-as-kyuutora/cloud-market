<?php
namespace App\Models;

/**
 * Model Pesan & Nego (Cloud Message)
 */
class Pesan extends Model
{
    public function ensureTable(): void
    {
        $this->run("CREATE TABLE IF NOT EXISTS pesan (
            idPesan INT AUTO_INCREMENT PRIMARY KEY,
            idPengirim INT NOT NULL,
            peranPengirim VARCHAR(20) NOT NULL,
            idPenerima INT NOT NULL,
            peranPenerima VARCHAR(20) NOT NULL,
            idBarang INT NULL,
            isiPesan TEXT NOT NULL,
            hargaNego DECIMAL(12,2) NULL,
            statusNego VARCHAR(20) NULL,
            waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            dibaca TINYINT(1) NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Kirim pesan baru
     */
    public function kirim(array $d): int
    {
        $this->ensureTable();
        $this->run(
            "INSERT INTO pesan (idPengirim, peranPengirim, idPenerima, peranPenerima, idBarang, isiPesan, hargaNego, statusNego, waktu, dibaca)
             VALUES (:pengirim, :peranPengirim, :penerima, :peranPenerima, :idBarang, :isi, :hargaNego, :statusNego, NOW(), 0)",
            [
                ':pengirim'      => (int)$d['idPengirim'],
                ':peranPengirim' => $d['peranPengirim'],
                ':penerima'      => (int)$d['idPenerima'],
                ':peranPenerima' => $d['peranPenerima'],
                ':idBarang'      => !empty($d['idBarang']) ? (int)$d['idBarang'] : null,
                ':isi'           => $d['isiPesan'],
                ':hargaNego'     => !empty($d['hargaNego']) ? (float)$d['hargaNego'] : null,
                ':statusNego'    => $d['statusNego'] ?? (!empty($d['hargaNego']) ? 'pending' : null),
            ]
        );
        return $this->lastId();
    }

    /**
     * Ambil daftar kontak / percakapan untuk user tertentu
     */
    public function getPercakapan(int $userId, string $peran): array
    {
        $this->ensureTable();
        // Cari semua partner chat
        $sql = "SELECT p.*,
                       b.namaBarang, b.harga AS hargaBarang, b.gambar AS gambarBarang
                FROM pesan p
                LEFT JOIN barang b ON p.idBarang = b.idBarang
                WHERE (p.idPengirim = :u1 AND p.peranPengirim = :r1)
                   OR (p.idPenerima = :u2 AND p.peranPenerima = :r2)
                ORDER BY p.waktu DESC";
        $semua = $this->all($sql, [
            ':u1' => $userId, ':r1' => $peran,
            ':u2' => $userId, ':r2' => $peran
        ]);

        $kontakMap = [];
        foreach ($semua as $m) {
            $isPengirim = ((int)$m['idPengirim'] === $userId && $m['peranPengirim'] === $peran);
            $partnerId = $isPengirim ? (int)$m['idPenerima'] : (int)$m['idPengirim'];
            if ($partnerId <= 0) continue;
            $partnerPeran = $isPengirim ? $m['peranPenerima'] : $m['peranPengirim'];
            $key = $partnerPeran . '_' . $partnerId;

            if (!isset($kontakMap[$key])) {
                // Cari nama partner
                $partnerNama = 'Pengguna';
                $partnerToko = '';
                $partnerHp   = '';
                $userRow = (new User())->getById($partnerId);
                if ($userRow) {
                    $partnerNama = $userRow['nama'];
                    $partnerToko = $userRow['namaToko'] ?? '';
                    $partnerHp   = $userRow['noHp'] ?? '';
                }

                $kontakMap[$key] = [
                    'partnerId'    => $partnerId,
                    'partnerPeran' => $partnerPeran,
                    'partnerNama'  => $partnerNama,
                    'partnerToko'  => $partnerToko,
                    'partnerHp'    => $partnerHp,
                    'pesanTerakhir'=> $m['isiPesan'],
                    'waktu'        => $m['waktu'],
                    'idBarang'     => $m['idBarang'],
                    'namaBarang'   => $m['namaBarang'],
                    'hargaBarang'  => $m['hargaBarang'],
                    'gambarBarang' => $m['gambarBarang'],
                    'hargaNego'    => $m['hargaNego'],
                ];
            }
        }

        return array_values($kontakMap);
    }

    /**
     * Ambil histori pesan dengan satu partner
     */
    public function getHistori(int $userId, string $peran, int $partnerId, string $partnerPeran, ?int $idBarang = null): array
    {
        $this->ensureTable();
        $sql = "SELECT p.*,
                       b.namaBarang, b.harga AS hargaBarang, b.gambar AS gambarBarang
                FROM pesan p
                LEFT JOIN barang b ON p.idBarang = b.idBarang
                WHERE ((p.idPengirim = :u1 AND p.peranPengirim = :r1 AND p.idPenerima = :p1 AND p.peranPenerima = :pr1)
                    OR (p.idPengirim = :p2 AND p.peranPengirim = :pr2 AND p.idPenerima = :u2 AND p.peranPenerima = :r2))
                ORDER BY p.waktu ASC";
        return $this->all($sql, [
            ':u1' => $userId, ':r1' => $peran, ':p1' => $partnerId, ':pr1' => $partnerPeran,
            ':p2' => $partnerId, ':pr2' => $partnerPeran, ':u2' => $userId, ':r2' => $peran
        ]);
    }

    /**
     * Ambil 1 baris pesan berdasarkan idPesan
     */
    public function getById(int $idPesan): ?array
    {
        $this->ensureTable();
        return $this->one("SELECT * FROM pesan WHERE idPesan = :id", [':id' => $idPesan]);
    }

    /**
     * Update status penawaran nego (diterima / ditolak)
     */
    public function updateStatusNego(int $idPesan, string $status): void
    {
        $this->run("UPDATE pesan SET statusNego = :s WHERE idPesan = :id", [':s' => $status, ':id' => $idPesan]);
    }

    /**
     * Ambil penawaran nego yang disetujui untuk pembeli, penjual, dan barang tertentu
     */
    public function getNegoDiterima(int $idPembeli, int $idPenjual, int $idBarang): ?array
    {
        $this->ensureTable();
        return $this->one(
            "SELECT * FROM pesan
             WHERE idBarang = :idBarang
               AND ((idPengirim = :u1 AND peranPengirim = 'pembeli' AND idPenerima = :p1 AND peranPenerima = 'penjual')
                 OR (idPengirim = :p2 AND peranPengirim = 'penjual' AND idPenerima = :u2 AND peranPenerima = 'pembeli'))
               AND statusNego = 'diterima'
             ORDER BY idPesan DESC LIMIT 1",
            [
                ':idBarang' => $idBarang,
                ':u1' => $idPembeli, ':p1' => $idPenjual,
                ':p2' => $idPenjual, ':u2' => $idPembeli
            ]
        );
    }
}
