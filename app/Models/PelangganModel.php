<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'pelanggan_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'username',
        'email',
        'password',
        // 'total_pembelian' sengaja tidak dimasukkan, 
        // karena seharusnya di-update oleh trigger atau dari transaksi penjualan
    ];

    // Dates
    protected $useTimestamps = false; // Tidak ada created_at/updated_at di tabel pelanggan

    // Validation
    protected $validationRules      = [
        'nama_pelanggan' => 'required|string|max_length[100]',
        'no_hp'          => 'permit_empty|string|max_length[20]',
        'alamat'         => 'permit_empty|string|max_length[255]',
        'username'       => 'required|string|max_length[255]|is_unique[pelanggan.username,pelanggan_id,{pelanggan_id}]',
        'email'          => 'required|string|max_length[100]|valid_email|is_unique[pelanggan.email,pelanggan_id,{pelanggan_id}]',
        'password'       => 'permit_empty|min_length[6]', // Hanya divalidasi jika diisi
    ];
    protected $validationMessages   = [
        'username' => [
            'is_unique' => 'Username ini sudah digunakan.',
        ],
        'email' => [
            'is_unique' => 'Email ini sudah terdaftar.',
            'valid_email' => 'Format email tidak valid.'
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash password sebelum disimpan ke database.
     */
    protected function hashPassword(array $data)
    {
        // Hanya hash jika field password ada dan tidak kosong
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            // Jika password kosong (misal saat update tapi tidak ganti password),
            // hapus field password dari data array agar tidak menimpa password lama
            unset($data['data']['password']);
        }
        
        return $data;
    }
}