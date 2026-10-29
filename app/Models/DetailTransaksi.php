<?php
namespace App\Models;

class DetailTransaksi extends Model
{
    public function add(int $idTransaksi, int $idBarang, int $jumlah, float $hargaSatuan): void
    {
        $this->run('INSERT INTO DetailTransaksi (idTransaksi, idBarang, jumlah, hargaSatuan) VALUES (?,?,?,?)',
            [$idTransaksi, $idBarang, $jumlah, $hargaSatuan]);
    }

    /** Item transaksi + info barang. $idPenjual opsional: batasi hanya barang milik penjual itu. */
    public function getByTransaksi(int $idTransaksi, ?int $idPenjual = null): array
    {
        $sql = 'SELECT d.*, b.namaBarang, b.gambar, b.idPenjual, p.namaToko,
                       (d.jumlah * d.hargaSatuan) AS subtotal
                FROM DetailTransaksi d
                JOIN Barang b  ON b.idBarang = d.idBarang
                JOIN Penjual p ON p.idPenjual = b.idPenjual
                WHERE d.idTransaksi = ?';
        $params = [$idTransaksi];
        if ($idPenjual !== null) { $sql .= ' AND b.idPenjual = ?'; $params[] = $idPenjual; }
        return $this->all($sql, $params);
    }

    /** Subtotal = jumlah x hargaSatuan (turunan, tidak disimpan - 3NF). */
    public static function hitungSubtotal(array $item): float
    {
        return (float)$item['jumlah'] * (float)$item['hargaSatuan'];
    }
}
