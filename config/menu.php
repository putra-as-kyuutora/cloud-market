<?php
/**
 * Menu navigasi per peran. Tema hanya me-render array ini,
 * jadi menambah halaman baru = tambah satu baris di sini.
 * Format: [label, path, awalan-path-untuk-penanda-aktif]
 */
return [
    'tamu' => [
        ['Beranda', '/', '/'],
        ['Cari Barang', '/barang', '/barang'],
    ],
    'pembeli' => [
        ['Beranda', '/', '/'],
        ['Cari Barang', '/barang', '/barang'],
    ],
    'penjual' => [
        ['Beranda', '/', '/'],
        ['Cari Barang', '/barang', '/barang'],
    ],
    'admin' => [
        ['Beranda', '/', '/'],
        ['Cari Barang', '/barang', '/barang'],
    ],
];
