<?php
/**
 * PETA URL APLIKASI - satu file untuk melihat seluruh fitur.
 * Format: $router->get|post('/path/{param}', [Controller::class, 'metode'], [middleware]);
 * Middleware: 'guest', 'auth', 'role:admin', 'role:penjual,admin'
 * Mau mewajibkan login untuk melihat barang? Tambahkan ['auth'] pada rutenya.
 */
use App\Controllers\{
    BerandaController, RegistrasiController, LoginController, LogoutController,
    CariBarangController, LihatInformasiBarangController, BarangSayaController,
    TambahBarangController, EditBarangController, HapusBarangController,
    BeliBarangController, PembayaranController, PesananController, KonfirmasiPembayaranController,
    EditDataPribadiController, KelolaAkunPenggunaController, KelolaKategoriController,
    LihatLaporanTransaksiController, DashboardAdminController, PesanController,
    BukaTokoController, VerifikasiPenjualController, WilayahController
};

/** @var App\Core\Router $router */

// ---- API Wilayah & Kode Pos (AJAX) ----
$router->get('/api/wilayah/provinsi',  [WilayahController::class, 'provinsi']);
$router->get('/api/wilayah/kabupaten', [WilayahController::class, 'kabupaten']);
$router->get('/api/wilayah/kecamatan', [WilayahController::class, 'kecamatan']);
$router->get('/api/wilayah/desa',      [WilayahController::class, 'desa']);
$router->get('/api/wilayah/kodepos',   [WilayahController::class, 'cariKodePos']);

// ---- Publik ----
$router->get('/',            [BerandaController::class, 'index']);
$router->get('/barang',      [CariBarangController::class, 'cariBarang']);
$router->get('/barang/{id}', [LihatInformasiBarangController::class, 'detail']);

// ---- Autentikasi ----
$router->get('/registrasi',  [RegistrasiController::class, 'form'], ['guest']);
$router->post('/registrasi', [RegistrasiController::class, 'registrasi'], ['guest']);
$router->get('/login',       [LoginController::class, 'form'], ['guest']);
$router->post('/login',      [LoginController::class, 'login'], ['guest']);
$router->get('/logout',      [LogoutController::class, 'konfirmasi'], ['auth']);
$router->post('/logout',     [LogoutController::class, 'logout'], ['auth']);

// ---- Semua peran ----
$router->get('/profil',  [EditDataPribadiController::class, 'form'], ['auth']);
$router->post('/profil', [EditDataPribadiController::class, 'editProfil'], ['auth']);
$router->get('/pesan',               [PesanController::class, 'index'], ['auth']);
$router->post('/pesan/kirim',        [PesanController::class, 'kirim'], ['auth']);
$router->post('/pesan/nego-status',  [PesanController::class, 'statusNego'], ['auth']);

// ---- Transaksi Pembelian & Pesanan (Pembeli & Penjual) ----
$router->get('/beli/{id}',  [BeliBarangController::class, 'form'], ['role:pembeli,penjual']);
$router->post('/beli/{id}', [BeliBarangController::class, 'beliBarang'], ['role:pembeli,penjual']);
$router->get('/pesanan',                [PesananController::class, 'saya'], ['role:pembeli,penjual']);
$router->get('/pesanan/{id}',           [PesananController::class, 'status'], ['role:pembeli,penjual']);
$router->post('/pesanan/{id}/diterima', [PesananController::class, 'diterima'], ['role:pembeli,penjual']);
$router->get('/pesanan/{id}/bayar',     [PembayaranController::class, 'form'], ['role:pembeli,penjual']);
$router->post('/pesanan/{id}/bayar',    [PembayaranController::class, 'prosesPembayaran'], ['role:pembeli,penjual']);
$router->post('/pesanan/{id}/batal',    [PembayaranController::class, 'batalkanPembayaran'], ['role:pembeli,penjual']);

// ---- Pendaftaran & Status Toko Penjual ----
$router->get('/penjual/daftar',            [BukaTokoController::class, 'form'], ['auth']);
$router->post('/penjual/daftar',           [BukaTokoController::class, 'daftar'], ['auth']);
$router->get('/penjual/status-verifikasi', [BukaTokoController::class, 'status'], ['auth']);

// ---- Penjual ----
$router->get('/penjual/barang',                 [BarangSayaController::class, 'index'], ['role:penjual']);
$router->get('/penjual/barang/tambah',          [TambahBarangController::class, 'form'], ['role:penjual']);
$router->post('/penjual/barang/tambah',         [TambahBarangController::class, 'tambahBarang'], ['role:penjual']);
$router->get('/penjual/barang/edit',            [EditBarangController::class, 'pilihProduk'], ['role:penjual']);
$router->get('/penjual/barang/{id}/edit',       [EditBarangController::class, 'form'], ['role:penjual']);
$router->post('/penjual/barang/{id}/edit',      [EditBarangController::class, 'editBarang'], ['role:penjual']);
$router->get('/penjual/barang/{id}/hapus',      [HapusBarangController::class, 'konfirmasiHapus'], ['role:penjual']);
$router->post('/penjual/barang/{id}/hapus',     [HapusBarangController::class, 'hapusBarang'], ['role:penjual']);
$router->get('/penjual/pesanan',                [PesananController::class, 'masuk'], ['role:penjual']);
$router->post('/penjual/pesanan/{id}/kirim',    [PesananController::class, 'kirim'], ['role:penjual']);
$router->get('/penjual/konfirmasi',             [KonfirmasiPembayaranController::class, 'index'], ['role:penjual']);
$router->post('/penjual/konfirmasi/{id}/terima',[KonfirmasiPembayaranController::class, 'konfirmasiPembayaran'], ['role:penjual']);
$router->post('/penjual/konfirmasi/{id}/tolak', [KonfirmasiPembayaranController::class, 'tolakPembayaran'], ['role:penjual']);

// ---- Admin + Penjual ----
$router->get('/laporan', [LihatLaporanTransaksiController::class, 'index'], ['role:admin,penjual']);

// ---- Admin ----
$router->get('/admin',                              [DashboardAdminController::class, 'index'], ['role:admin']);
$router->get('/admin/akun',                         [KelolaAkunPenggunaController::class, 'index'], ['role:admin']);
$router->post('/admin/akun/{peran}/{id}/status',    [KelolaAkunPenggunaController::class, 'ubahStatus'], ['role:admin']);
$router->get('/admin/verifikasi-penjual',          [VerifikasiPenjualController::class, 'index'], ['role:admin']);
$router->get('/admin/verifikasi-penjual/{id}',     [VerifikasiPenjualController::class, 'detail'], ['role:admin']);
$router->post('/admin/verifikasi-penjual/{id}/setujui', [VerifikasiPenjualController::class, 'setujui'], ['role:admin']);
$router->post('/admin/verifikasi-penjual/{id}/tolak',   [VerifikasiPenjualController::class, 'tolak'], ['role:admin']);
$router->get('/admin/kategori',                     [KelolaKategoriController::class, 'index'], ['role:admin']);
$router->get('/admin/kategori/tambah',              [KelolaKategoriController::class, 'formTambah'], ['role:admin']);
$router->post('/admin/kategori/tambah',             [KelolaKategoriController::class, 'tambahKategori'], ['role:admin']);
$router->get('/admin/kategori/{id}/edit',           [KelolaKategoriController::class, 'formEdit'], ['role:admin']);
$router->post('/admin/kategori/{id}/edit',          [KelolaKategoriController::class, 'editKategori'], ['role:admin']);
$router->get('/admin/kategori/{id}/hapus',          [KelolaKategoriController::class, 'konfirmasiHapus'], ['role:admin']);
$router->post('/admin/kategori/{id}/hapus',         [KelolaKategoriController::class, 'hapusKategori'], ['role:admin']);
