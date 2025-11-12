<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    // Nama tabel di database
    protected $table      = 'produk';
    // Primary Key tabel
    protected $primaryKey = 'produk_id';

    // Izinkan kolom-kolom ini untuk diisi/diperbarui
    protected $allowedFields = [
        'kategori_id',
        'nama_produk',
        'harga',
        'stok',
        'foto_produk',
        'deskripsi'
        // 'created_at' tidak perlu karena sudah ada default value di DB
    ];

    // Setel tipe data untuk nilai kembalian (opsional, tapi baik)
    protected $returnType = 'array';

    // Gunakan timestamps (opsional, tapi baik)
    protected $useTimestamps = false;
    protected $createdField  = 'created_at'; // Sesuai dengan nama kolom di DB
    protected $updatedField  = null; // Tidak ada kolom updated_at di DB Anda

    // Tambahan: Relasi (Contoh untuk JOIN dengan Kategori)
    public function getProdukWithKategori($id = false)
    {
        if ($id === false) {
            return $this->select('produk.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.kategori_id = produk.kategori_id')
                ->findAll();
        }

        return $this->select('produk.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kategori_id = produk.kategori_id')
            ->where(['produk_id' => $id])
            ->first();
    }
}
