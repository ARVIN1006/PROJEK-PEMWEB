<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPembelianModel extends Model
{
    protected $table            = 'detail_pembelian';
    protected $primaryKey       = 'detail_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Daftar kolom di tabel 'detail_pembelian' yang boleh diisi
    // Ini sudah sesuai dengan perbaikan database yang kita lakukan
    protected $allowedFields    = [
        'pembelian_id',
        'bahan_id',         // Menghubungkan ke tabel 'bahan_baku'
        'jumlah_pesan',     // Total yang dipesan
        'jumlah_diterima',  // Total yang sudah diterima (untuk melacak barang kurang)
        'harga_satuan',
        'subtotal'
    ];
}
