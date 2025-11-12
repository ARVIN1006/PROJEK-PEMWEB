<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'pembelian_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $protectFields = true;

    // Ini adalah kolom-kolom di tabel 'pembelian' SETELAH di-upgrade
    protected $allowedFields = [
        'kode_pembelian',
        'tanggal_pembelian',
        'pemasok_id',
        'total_harga',
        'status_pembayaran',
        'status_pembelian',
        'catatan',
        'keterangan',
        'created_at'
    ];
}
