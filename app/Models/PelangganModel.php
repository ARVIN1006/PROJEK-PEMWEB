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
        'username', // Tetap di allowed fields
        'email',    // Tetap di allowed fields
        'password', // Tetap di allowed fields
        // 'total_pembelian' sengaja tidak dimasukkan, 
        // karena seharusnya di-update oleh trigger atau dari transaksi penjualan
    ];

    // Dates
    protected $useTimestamps = false; // Tidak ada created_at/updated_at di tabel pelanggan

    // PERUBAHAN: Menjadikan username, email, dan password sebagai field opsional.
    protected $validationRules      = [
        'nama_pelanggan' => 'required|string|max_length[100]',
        'no_hp'          => 'permit_empty|string|max_length[20]',
        'alamat'         => 'permit_empty|string|max_length[255]',
        'username'       => 'permit_empty|string|max_length[255]', // DIUBAH: Hapus 'required' dan 'is_unique'
        'email'          => 'permit_empty|string|max_length[100]|valid_email', // DIUBAH: Hapus 'required' dan 'is_unique'
        'password'       => 'permit_empty|min_length[6]', // Hanya divalidasi jika diisi
    ];
    // PERUBAHAN: Pesan error keunikan dihapus karena aturan keunikan dihapus
    protected $validationMessages   = [];
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
        // Logic ini tetap bekerja. Jika 'password' tidak ada di $data (karena tidak ada di form),
        // maka unset akan dijalankan sehingga tidak menimpa field di DB dengan nilai kosong.
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            // Jika password kosong atau tidak disubmit dari form (karena dihilangkan),
            // hapus field password dari data array agar tidak menimpa password lama
            unset($data['data']['password']);
        }
        
        return $data;
    }
}