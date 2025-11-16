<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table            = 'pengeluaran';
    protected $primaryKey       = 'pengeluaran_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'kategori_pengeluaran',
        'keterangan',
        'jumlah'
    ];

    // Dates
    protected $useTimestamps = true; // Anda punya 'created_at'
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada updated_at

    // Validation
    protected $validationRules      = [
        'tanggal'              => 'required|valid_date',
        'kategori_pengeluaran' => 'required|string|max_length[100]',
        'keterangan'           => 'permit_empty|string',
        'jumlah'               => 'required|decimal'
    ];
    protected $validationMessages   = [
        'jumlah' => [
            'required' => 'Jumlah tidak boleh kosong.',
            'decimal'  => 'Jumlah harus berupa angka desimal.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}