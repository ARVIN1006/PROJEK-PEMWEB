<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Menunjukkan tabel yang akan digunakan
    protected $table = 'users';

    // Menunjukkan primary key tabel
    protected $primaryKey = 'id';

    // Mengizinkan field-field ini untuk diisi
    protected $allowedFields = ['name', 'email', 'password', 'role'];

    // --- PERUBAHAN DI SINI ---
    // Matikan timestamp karena database sudah menanganinya
    protected $useTimestamps = false;


}
