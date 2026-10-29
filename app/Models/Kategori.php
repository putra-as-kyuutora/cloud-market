<?php
namespace App\Models;

use App\Core\Database;

class Kategori extends Model
{
    /** Kolom deskripsi hanya ada jika migrasi opsional dijalankan. */
    public function hasDeskripsi(): bool { return Database::hasColumn('Kategori', 'deskripsi'); }

    public function getById(int $id): ?array
    {
        return $this->one('SELECT * FROM Kategori WHERE idKategori = ?', [$id]);
    }

    public function getAll(): array
    {
        return $this->all('SELECT * FROM Kategori ORDER BY namaKategori');
    }

    /** Daftar kategori + jumlah barang aktif (untuk halaman admin). */
    public function getAllWithCount(): array
    {
        return $this->all(
            'SELECT k.*, COUNT(b.idBarang) AS jumlahBarang
             FROM Kategori k LEFT JOIN Barang b ON b.idKategori = k.idKategori
             GROUP BY k.idKategori ORDER BY k.namaKategori'
        );
    }

    public function namaSudahAda(string $nama, ?int $exceptId = null): bool
    {
        return (bool)$this->scalar(
            'SELECT COUNT(*) FROM Kategori WHERE LOWER(namaKategori) = LOWER(?) AND idKategori <> ?',
            [$nama, $exceptId ?? 0]
        );
    }

    public function add(string $nama, int $idAdmin, ?string $deskripsi = null): int
    {
        if ($this->hasDeskripsi()) {
            $this->run('INSERT INTO Kategori (idAdmin, namaKategori, deskripsi) VALUES (?,?,?)', [$idAdmin, $nama, $deskripsi]);
        } else {
            $this->run('INSERT INTO Kategori (idAdmin, namaKategori) VALUES (?,?)', [$idAdmin, $nama]);
        }
        return $this->lastId();
    }

    public function update(int $id, string $nama, ?string $deskripsi = null): void
    {
        if ($this->hasDeskripsi()) {
            $this->run('UPDATE Kategori SET namaKategori=?, deskripsi=? WHERE idKategori=?', [$nama, $deskripsi, $id]);
        } else {
            $this->run('UPDATE Kategori SET namaKategori=? WHERE idKategori=?', [$nama, $id]);
        }
    }

    public function delete(int $id): void { $this->run('DELETE FROM Kategori WHERE idKategori = ?', [$id]); }

    public function jumlahBarang(int $id): int
    {
        return (int)$this->scalar('SELECT COUNT(*) FROM Barang WHERE idKategori = ?', [$id]);
    }

    public function isUsedByBarang(int $id): bool { return $this->jumlahBarang($id) > 0; }
}
