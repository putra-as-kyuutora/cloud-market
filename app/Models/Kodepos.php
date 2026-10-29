<?php
namespace App\Models;

/**
 * Model Data Wilayah & Kode Pos Indonesia
 * Mengelola data 83.000+ desa, kecamatan, kabupaten, dan provinsi di Indonesia.
 */
class Kodepos extends Model
{
    /**
     * Ambil daftar seluruh 38 provinsi di Indonesia
     */
    public function getProvinsi(): array
    {
        return $this->all("SELECT DISTINCT provinsi FROM kodepos ORDER BY provinsi ASC");
    }

    /**
     * Ambil daftar kabupaten/kota berdasarkan nama provinsi
     */
    public function getKabupaten(string $provinsi): array
    {
        return $this->all(
            "SELECT DISTINCT kabupaten FROM kodepos WHERE provinsi = :prov ORDER BY kabupaten ASC",
            [':prov' => $provinsi]
        );
    }

    /**
     * Ambil daftar kecamatan berdasarkan nama kabupaten/kota
     */
    public function getKecamatan(string $kabupaten): array
    {
        return $this->all(
            "SELECT DISTINCT kecamatan FROM kodepos WHERE kabupaten = :kab ORDER BY kecamatan ASC",
            [':kab' => $kabupaten]
        );
    }

    /**
     * Ambil daftar desa/kelurahan beserta kode posnya berdasarkan kecamatan & kabupaten
     */
    public function getDesa(string $kecamatan, ?string $kabupaten = null): array
    {
        $sql = "SELECT DISTINCT desa, kode_pos FROM kodepos WHERE kecamatan = :kec";
        $params = [':kec' => $kecamatan];

        if (!empty($kabupaten)) {
            $sql .= " AND kabupaten = :kab";
            $params[':kab'] = $kabupaten;
        }

        $sql .= " ORDER BY desa ASC";
        return $this->all($sql, $params);
    }

    /**
     * Cari wilayah berdasarkan 5 digit Kode Pos
     */
    public function getByKodePos(string $kodePos): array
    {
        $kodePos = trim($kodePos);
        return $this->all(
            "SELECT * FROM kodepos WHERE kode_pos = :kp ORDER BY desa ASC",
            [':kp' => $kodePos]
        );
    }
}
