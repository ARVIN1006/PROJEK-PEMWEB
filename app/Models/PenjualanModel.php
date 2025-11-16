<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'penjualan_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'pelanggan_id',
        'total_harga',
        'metode_pembayaran',
        'status',
        'status_pembayaran' // Pastikan Anda sudah ALTER TABLE
    ];

    // Dates
    protected $useTimestamps = true; // Anda punya 'created_at'
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada updated_at

    /**
     * Mengambil semua data penjualan dengan join ke tabel pelanggan.
     */
    public function getPenjualanWithPelanggan()
    {
        return $this->select('penjualan.*, pelanggan.nama_pelanggan')
                    ->join('pelanggan', 'pelanggan.pelanggan_id = penjualan.pelanggan_id', 'left')
                    ->orderBy('penjualan.tanggal', 'DESC')
                    ->findAll();
    }
}