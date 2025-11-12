<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pengeluaran extends BaseController
{
    public function tambah()
    {
        // Nanti di sini Anda akan memuat view form
        // return view('Pengeluaran/tambah');

        // Untuk sekarang, tampilkan teks saja
        echo "<h1>Halaman Form Input Pengeluaran</h1><p>Formulir untuk menambah pengeluaran (listrik, air, dll).</p>";
    }
}