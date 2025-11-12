<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pelanggan extends BaseController
{
    public function index()
    {
        // Nanti di sini Anda akan mengambil data dari Model
        // $model = new PelangganModel();
        // $data['pelanggan'] = $model->findAll();
        
        // Dan mengirimkannya ke view
        // return view('Pelanggan/index', $data);

        // Untuk sekarang, tampilkan teks saja
        echo "<h1>Halaman Master Data Pelanggan</h1><p>Tabel data pelanggan akan muncul di sini.</p>";
    }
}