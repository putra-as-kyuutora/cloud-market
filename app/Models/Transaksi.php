<?php
namespace App\Models;

class Transaksi extends Model
{
    public const BAYAR_MENUNGGU   = 'Menunggu Pembayaran';
    public const BAYAR_KONFIRMASI = 'Menunggu Konfirmasi';
    public const BAYAR_LUNAS      = 'Sudah Dibayar';
    public const BAYAR_DITOLAK    = 'Ditolak';
    public const BAYAR_BATAL      = 'Dibatalkan';

    public const KIRIM_BELUM   = 'Belum Dikirim';
    public const KIRIM_PROSES  = 'Sedang Diproses';
    public const KIRIM_DIKIRIM = 'Sudah Dikirim';
    public const KIRIM_TERIMA  = 'Diterima';

    public const SEMUA_BAYAR = [self::BAYAR_MENUNGGU, self::BAYAR_KONFIRMASI, self::BAYAR_LUNAS, self::BAYAR_DITOLAK, self::BAYAR_BATAL];

    /** Total dihitung dari DetailTransaksi (atribut turunan, tidak disimpan). */
    private const TOTAL = '(SELECT COALESCE(SUM(d.jumlah * d.hargaSatuan),0) FROM DetailTransaksi d WHERE d.idTransaksi = t.idTransaksi)';

    public function create(int $idPembeli, int $idAdmin, string $alamat, string $metode): int
    {
        $this->run(
            'INSERT INTO Transaksi (idPembeli, idAdmin, metodePembayaran, alamatPengiriman) VALUES (?,?,?,?)',
            [$idPembeli, $idAdmin, $metode, $alamat]
        );
        return $this->lastId();
    }

    public function getById(int $id): ?array
    {
        return $this->one(
            'SELECT t.*, pb.nama AS namaPembeli, pb.noHp AS noHpPembeli, ' . self::TOTAL . ' AS total
             FROM Transaksi t JOIN users pb ON pb.idUser = t.idPembeli WHERE t.idTransaksi = ?', [$id]
        );
    }

    public function updateStatusPembayaran(int $id, string $status): void
    {
        $this->run('UPDATE Transaksi SET statusPembayaran = ? WHERE idTransaksi = ?', [$status, $id]);
    }

    public function updateStatusPengiriman(int $id, string $status): void
    {
        $this->run('UPDATE Transaksi SET statusPengiriman = ? WHERE idTransaksi = ?', [$status, $id]);
    }

    public function setBukti(int $id, string $path): void
    {
        $this->run('UPDATE Transaksi SET buktiPembayaran = ? WHERE idTransaksi = ?', [$path, $id]);
    }

    /** Apakah transaksi memuat minimal satu barang milik penjual ini. */
    public function milikPenjual(int $idTransaksi, int $idPenjual): bool
    {
        return (bool)$this->scalar(
            'SELECT COUNT(*) FROM DetailTransaksi d JOIN Barang b ON b.idBarang = d.idBarang
             WHERE d.idTransaksi = ? AND b.idPenjual = ?', [$idTransaksi, $idPenjual]
        );
    }

    public function getByPembeli(int $idPembeli): array
    {
        return $this->all(
            "SELECT t.*, " . self::TOTAL . " AS total,
                    (SELECT GROUP_CONCAT(b.namaBarang SEPARATOR ', ') FROM DetailTransaksi d
                       JOIN Barang b ON b.idBarang = d.idBarang WHERE d.idTransaksi = t.idTransaksi) AS barang
             FROM Transaksi t WHERE t.idPembeli = ? ORDER BY t.idTransaksi DESC", [$idPembeli]
        );
    }

    /**
     * Laporan/daftar transaksi (admin: semua, penjual: hanya barangnya).
     * $f: idPenjual, dari, sampai, status, q
     */
    public function getLaporan(array $f): array
    {
        $w = [];
        $p = [];
        if (!empty($f['idPenjual'])) { $w[] = 'b.idPenjual = ?'; $p[] = (int)$f['idPenjual']; }
        if (!empty($f['dari']))      { $w[] = 't.tanggal >= ?'; $p[] = $f['dari'] . ' 00:00:00'; }
        if (!empty($f['sampai']))    { $w[] = 't.tanggal <= ?'; $p[] = $f['sampai'] . ' 23:59:59'; }
        if (!empty($f['status']))    { $w[] = 't.statusPembayaran = ?'; $p[] = $f['status']; }
        if (!empty($f['q'])) {
            $w[] = '(pb.nama LIKE ? OR b.namaBarang LIKE ? OR t.idTransaksi = ?)';
            $like = '%' . addcslashes($f['q'], '%_\\') . '%';
            array_push($p, $like, $like, (int)ltrim($f['q'], '#CM-0'));
        }
        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        return $this->all(
            "SELECT t.idTransaksi, t.tanggal, t.metodePembayaran, t.statusPembayaran, t.statusPengiriman,
                    t.buktiPembayaran, pb.nama AS namaPembeli,
                    GROUP_CONCAT(b.namaBarang SEPARATOR ', ') AS barang,
                    SUM(d.jumlah * d.hargaSatuan) AS total
             FROM Transaksi t
             JOIN users pb ON pb.idUser = t.idPembeli
             JOIN DetailTransaksi d ON d.idTransaksi = t.idTransaksi
             JOIN Barang b ON b.idBarang = d.idBarang
             $where
             GROUP BY t.idTransaksi
             ORDER BY t.idTransaksi DESC", $p
        );
    }

    /** Jumlahkan hanya transaksi "Sudah Dibayar". */
    public static function hitungTotalPendapatan(array $rows): float
    {
        $sum = 0.0;
        foreach ($rows as $r) if ($r['statusPembayaran'] === self::BAYAR_LUNAS) $sum += (float)$r['total'];
        return $sum;
    }

    public function count(): int { return (int)$this->scalar('SELECT COUNT(*) FROM Transaksi'); }

    public function terbaru(int $limit = 5): array
    {
        return $this->all(
            'SELECT t.idTransaksi, t.tanggal, t.statusPembayaran, pb.nama AS namaPembeli
             FROM Transaksi t JOIN users pb ON pb.idUser = t.idPembeli
             ORDER BY t.idTransaksi DESC LIMIT ' . (int)$limit
        );
    }
}
