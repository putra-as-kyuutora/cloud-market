<?php
namespace App\Models;

class Barang extends Model
{
    private const SELECT = 'SELECT b.*, k.namaKategori, s.namaToko,
                                   (s.statusVerifikasi = "approved") AS verified,
                                   u.nama AS namaPenjual
                            FROM barang b
                            JOIN kategori k ON k.idKategori = b.idKategori
                            JOIN users u    ON u.idUser = b.idPenjual
                            LEFT JOIN seller_profiles s ON s.idUser = b.idPenjual';

    public function getById(int $id): ?array
    {
        return $this->one(self::SELECT . ' WHERE b.idBarang = ?', [$id]);
    }

    public function getByPenjual(int $idPenjual): array
    {
        return $this->all(self::SELECT . ' WHERE b.idPenjual = ? ORDER BY b.idBarang DESC', [$idPenjual]);
    }

    /**
     * Pencarian + filter + urutan + paginasi.
     * $f: q, kategori, min, max, urut(terbaru|termurah|termahal), hanyaTersedia
     */
    public function search(array $f, int $page, int $perPage): array
    {
        $where = [];
        $p = [];
        if (($f['q'] ?? '') !== '') {
            $where[] = '(b.namaBarang LIKE ? OR b.deskripsi LIKE ?)';
            $like = '%' . addcslashes($f['q'], '%_\\') . '%';
            array_push($p, $like, $like);
        }
        if (!empty($f['kategori'])) { $where[] = 'b.idKategori = ?'; $p[] = (int)$f['kategori']; }
        if (($f['min'] ?? '') !== '' && is_numeric($f['min'])) { $where[] = 'b.harga >= ?'; $p[] = $f['min']; }
        if (($f['max'] ?? '') !== '' && is_numeric($f['max'])) { $where[] = 'b.harga <= ?'; $p[] = $f['max']; }
        if (!empty($f['hanyaTersedia'])) $where[] = 'b.stok > 0';
        $w = $where ? ' WHERE ' . implode(' AND ', $where) : '';

        $order = ['termurah' => 'b.harga ASC', 'termahal' => 'b.harga DESC'][$f['urut'] ?? ''] ?? 'b.idBarang DESC';

        $total = (int)$this->scalar(
            'SELECT COUNT(*) FROM Barang b' . $w, $p
        );
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min(max(1, $page), $pages);
        $offset = ($page - 1) * $perPage;

        $items = $this->all(self::SELECT . $w . " ORDER BY $order LIMIT $perPage OFFSET $offset", $p);
        return ['items' => $items, 'total' => $total, 'page' => $page, 'pages' => $pages, 'perPage' => $perPage];
    }

    public function add(array $d): int
    {
        $this->run(
            'INSERT INTO Barang (idPenjual, idKategori, namaBarang, harga, stok, deskripsi, gambar) VALUES (?,?,?,?,?,?,?)',
            [$d['idPenjual'], $d['idKategori'], $d['namaBarang'], $d['harga'], $d['stok'], $d['deskripsi'], $d['gambar']]
        );
        return $this->lastId();
    }

    public function update(int $id, array $d): void
    {
        $this->run(
            'UPDATE Barang SET idKategori=?, namaBarang=?, harga=?, stok=?, deskripsi=?, gambar=? WHERE idBarang=?',
            [$d['idKategori'], $d['namaBarang'], $d['harga'], $d['stok'], $d['deskripsi'], $d['gambar'], $id]
        );
    }

    public function delete(int $id): void { $this->run('DELETE FROM Barang WHERE idBarang = ?', [$id]); }

    /** Harus dipanggil di dalam transaksi DB. Mengunci baris & memastikan stok cukup. */
    public function kurangiStok(int $id, int $jumlah): bool
    {
        $stok = $this->scalar('SELECT stok FROM Barang WHERE idBarang = ? FOR UPDATE', [$id]);
        if ($stok === false || (int)$stok < $jumlah) return false;
        $this->run('UPDATE Barang SET stok = stok - ? WHERE idBarang = ?', [$jumlah, $id]);
        return true;
    }

    public function tambahStok(int $id, int $jumlah): void
    {
        $this->run('UPDATE Barang SET stok = stok + ? WHERE idBarang = ?', [$jumlah, $id]);
    }

    public function countTersedia(): int { return (int)$this->scalar('SELECT COUNT(*) FROM Barang WHERE stok > 0'); }

    public function terbaru(int $limit = 5): array
    {
        return $this->all(self::SELECT . ' ORDER BY b.idBarang DESC LIMIT ' . (int)$limit);
    }

    public function getAll(): array { return $this->all(self::SELECT . ' ORDER BY b.idBarang DESC'); }
}
