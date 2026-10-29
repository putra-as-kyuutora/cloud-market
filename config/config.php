<?php
/**
 * Konfigurasi utama Cloud Market.
 * Ubah nilai di sini saja; kode lain tidak perlu disentuh.
 */
return [
    'app_name' => 'Cloud Market',

    // ---- Database (sesuaikan dengan XAMPP / server Anda) ----
    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'cloud_market',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],

    // ---- TEMA / TAMPILAN ----
    // Nama folder di /themes. Ganti nilai ini untuk mengganti seluruh UI.
    // Jika sebuah file view tidak ada di tema aktif, otomatis dipakai dari tema 'default'.
    'theme' => 'default',

    // ---- Upload ----
    'upload' => [
        'max_bytes' => 2 * 1024 * 1024,                 // 2 MB
        'mime'      => ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'],
    ],

    // ---- Paginasi ----
    'per_page' => 12,

    // ---- Metode pembayaran ----
    // Tambah metode baru cukup dengan menambah satu entri di bawah.
    // 'butuh_bukti' = pembeli wajib unggah bukti bayar.
    'pembayaran' => [
        'Transfer Bank' => [
            'butuh_bukti' => true,
            'instruksi'   => 'Transfer ke rekening BCA 1234567890 a.n. Cloud Market, sesuai total pembayaran, lalu unggah bukti transfer.',
        ],
        'QRIS' => [
            'butuh_bukti' => true,
            'instruksi'   => 'Scan kode QRIS Cloud Market melalui aplikasi e-wallet/mobile banking Anda, lalu unggah bukti pembayaran.',
        ],
        'COD' => [
            'butuh_bukti' => false,
            'instruksi'   => 'Bayar tunai kepada kurir/penjual saat barang diterima. Tidak perlu unggah bukti.',
        ],
    ],
];
