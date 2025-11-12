<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// === RUTE PUBLIK (Bisa diakses tanpa login) ===
$routes->get('/', 'Home::index');
$routes->get('login', 'Home::login');
$routes->post('user/account', 'User::account'); // Rute untuk proses login & register
$routes->get('/register', 'Home::register');
$routes->get('logout', 'User::logout'); // Rute untuk logout

// =======================================================
// === RUTE INTERNAL (HARUS LOGIN - Filter 'auth') ===
// =======================================================
// Semua rute di dalam grup ini akan dilindungi oleh AuthFilter
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // Dashboard (Bisa diakses Owner & Admin)
    $routes->get('dashboard', 'Dashboard::index');

    // // --- Rute untuk Modul Produk coba diawal progress pemweb---
    // ... (Komentar Anda dipertahankan) ...

    // === GRUP 1: MASTER DATA (Bisa diakses Owner & Admin) ===
    $routes->get('produk', 'Produk::index');

    $routes->group('pemasok', static function ($routes) {
        $routes->get('/', 'Pemasok::index');                 // Menampilkan semua
        $routes->post('save', 'Pemasok::save');              // Menyimpan data baru
        $routes->get('edit/(:num)', 'Pemasok::edit/$1');     // Menampilkan form edit
        $routes->post('update/(:num)', 'Pemasok::update/$1'); // Memproses update
        $routes->get('delete/(:num)', 'Pemasok::delete/$1');   // Menghapus data
    });

    $routes->get('pelanggan', 'Pelanggan::index');
    $routes->get('aset', 'Aset::index');

    // === GRUP 2: TRANSAKSI (Bisa diakses Owner & Admin) ===
    $routes->group('penjualan', static function ($routes) {
        $routes->get('tambah', 'Penjualan::tambah');
        // $routes->post('simpan', 'Penjualan::simpan'); 
    });

    $routes->group('pembelian', static function ($routes) {
        $routes->get('/', 'Pembelian::index');
        $routes->post('save', 'Pembelian::save');
        $routes->get('edit/(:num)', 'Pembelian::edit/$1');
        $routes->post('update/(:num)', 'Pembelian::update/$1');
        $routes->get('delete/(:num)', 'Pembelian::delete/$1');
    });
    $routes->get('pembelian/utang/(:num)', 'Pembelian::utang/$1');
    $routes->get('pembelian/detail/(:num)', 'Pembelian::detail/$1');

    $routes->group('bahanbaku', static function ($routes) {
        $routes->get('/', 'BahanBaku::index');
        $routes->post('save', 'BahanBaku::save');
        $routes->post('update/(:num)', 'BahanBaku::update/$1');
        $routes->get('delete/(:num)', 'BahanBaku::delete/$1');
    });

    $routes->group('pengeluaran', static function ($routes) {
        $routes->get('tambah', 'Pengeluaran::tambah');
        // $routes->post('simpan', 'Pengeluaran::simpan'); 
    });

    // === GRUP 3: LAPORAN (HANYA OWNER - Filter 'owner') ===
    // Grup ini memiliki filter 'auth' (dari grup luar) DAN 'owner'
    $routes->group('laporan', ['namespace' => 'App\Controllers', 'filter' => 'owner'], static function ($routes) {
        $routes->get('laba_rugi', 'Laporan::labaRugi');
        $routes->get('posisi_keuangan', 'Laporan::posisiKeuangan');
        $routes->get('piutang', 'Laporan::piutang');
        $routes->get('utang', 'Laporan::utang');
        $routes->get('penjualan', 'Laporan::penjualan');
        $routes->get('pembelian', 'Laporan::pembelian');
        // Catatan: Rute 'jurnal' dan 'bukuBesar' ada di sidebar Anda
        // tapi tidak ada di file Routes.php Anda. 
        // Anda mungkin perlu menambahkannya di sini jika diperlukan.
    });
}); // <-- Akhir dari grup filter 'auth'