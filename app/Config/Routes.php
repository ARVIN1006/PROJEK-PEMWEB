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

    // === GRUP 1: MASTER DATA (Bisa diakses Owner & Admin) ===
    $routes->get('produk', 'Produk::index');

    $routes->group('pemasok', static function ($routes) {
        $routes->get('/', 'Pemasok::index');                 // Menampilkan semua
        $routes->post('save', 'Pemasok::save');              // Menyimpan data baru
        $routes->get('edit/(:num)', 'Pemasok::edit/$1');     // Menampilkan form edit
        $routes->post('update/(:num)', 'Pemasok::update/$1'); // Memproses update
        $routes->get('delete/(:num)', 'Pemasok::delete/$1');   // Menghapus data
    });

    // --- Rute CRUD Pelanggan ---
    $routes->group('pelanggan', static function ($routes) {
        $routes->get('/', 'Pelanggan::index');
        $routes->get('create', 'Pelanggan::create');         // Form tambah
        $routes->post('store', 'Pelanggan::store');          // Proses simpan
        $routes->get('edit/(:num)', 'Pelanggan::edit/$1');   // Form edit
        $routes->post('update/(:num)', 'Pelanggan::update/$1');// Proses update
        $routes->get('delete/(:num)', 'Pelanggan::delete/$1');// Proses hapus
    });
    
    $routes->get('aset', 'Aset::index');

    // === GRUP 2: TRANSAKSI (Bisa diakses Owner & Admin) ===
    $routes->group('penjualan', static function ($routes) {
        $routes->get('tambah', 'Penjualan::tambah');
        // $routes->post('simpan', 'Penjualan::simpan'); 
    });

    // --- Rute Status Pembayaran (Piutang) ---
    $routes->group('piutang', static function ($routes) {
        $routes->get('/', 'Piutang::index');
        $routes->get('update-status/(:num)', 'Piutang::updateStatus/$1'); // Update status bayar
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

    // --- Rute CRUD Pengeluaran ---
    $routes->group('pengeluaran', static function ($routes) {
        $routes->get('/', 'Pengeluaran::index'); // Halaman utama pengeluaran
        $routes->get('create', 'Pengeluaran::create'); // Form tambah (menggantikan 'tambah')
        $routes->post('store', 'Pengeluaran::store');  // Proses simpan
        $routes->get('edit/(:num)', 'Pengeluaran::edit/$1'); // Form edit
        $routes->post('update/(:num)', 'Pengeluaran::update/$1'); // Proses update
        $routes->get('delete/(:num)', 'Pengeluaran::delete/$1'); // Proses hapus
    });

    // === GRUP 3: LAPORAN (HANYA OWNER - Filter 'owner') ===
    // Grup ini memiliki filter 'auth' (dari grup luar) DAN 'owner'
    $routes->group('laporan', ['namespace' => 'App\Controllers', 'filter' => 'owner'], static function ($routes) {
        $routes->get('laba_rugi', 'Laporan::labaRugi');
        $routes->get('posisi_keuangan', 'Laporan::posisiKeuangan');
        
        // Ubah rute 'piutang' agar mengarah ke controller Piutang (tapi tetap di grup Laporan)
        // Jika Anda ingin Laporan Piutang khusus, Anda bisa buat Laporan::piutang
        // Tapi jika ingin halaman manajemennya, arahkan ke Piutang::index
        // Saya asumsikan Anda ingin halaman manajemen, jadi saya akan pindahkan
        // $routes->get('piutang', 'Laporan::piutang'); 
        // $routes->get('piutang', 'Piutang::index'); // Dipindahkan ke grup auth umum
        
        $routes->get('utang', 'Laporan::utang');
        $routes->get('penjualan', 'Laporan::penjualan');
        $routes->get('pembelian', 'Laporan::pembelian');
    });

}); // <-- Akhir dari grup filter 'auth'