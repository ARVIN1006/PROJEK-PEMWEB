<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Aset extends BaseController
{
    public function index()
    {
        // Nanti di sini Anda akan mengambil data dari Model
        // $model = new AsetModel();
        // $data['aset'] = $model->findAll();
        
        // Dan mengirimkannya ke view
        // return view('Aset/index', $data);

        // Untuk sekarang, tampilkan teks saja
        echo "<h1>Halaman Master Data Aset Tetap</h1><p>Tabel data aset tetap akan muncul di sini.</p>";
    }
}