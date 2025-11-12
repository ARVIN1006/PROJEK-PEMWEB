<?php

namespace App\Models;

use CodeIgniter\Model;

class PemasokModel extends Model
{
    protected $table            = 'pemasok'; // Pastikan nama tabel Anda 'pemasok'
    protected $primaryKey       = 'pemasok_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // PASTIKAN HANYA KOLOM TABEL PEMASOK YANG ADA DI SINI
    protected $allowedFields    = [
        'kode_pemasok',
        'nama_pemasok',
        'kontak_person',
        'kategori',
        'email',
        'alamat',
        'no_telp', // Sesuaikan dengan nama kolom Anda (no_telp atau no_telepon)
        'status',
        'total_pembelian',
        'total_barang', // <-- TAMBAHKAN BARIS INI
        'catatan'
        // JANGAN TAMBAHKAN 'status_pembayaran' atau 'status_pembelian' di sini
    ];

    // Dates
    protected $useTimestamps = false; // Set true jika Anda punya created_at, updated_at
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
}
