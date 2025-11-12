<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Penjualan extends BaseController
{
    public function tambah()
    {
        // Nanti di sini Anda akan memuat view form
        // return view('Penjualan/tambah');

        // Untuk sekarang, tampilkan teks saja
        echo "<h1>Halaman Form Input Penjualan</h1><p>Formulir untuk menambah transaksi penjualan baru.</p>";
    }
}