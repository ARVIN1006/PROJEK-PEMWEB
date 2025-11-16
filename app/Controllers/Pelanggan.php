<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;

class Pelanggan extends BaseController
{
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    /**
     * Menampilkan halaman utama daftar pelanggan.
     */
    public function index()
    {
        $data = [
            'title'     => 'Master Data Pelanggan',
            'pelanggan' => $this->pelangganModel->findAll()
        ];
        
        return view('Pelanggan/index', $data);
    }

    /**
     * Menampilkan form untuk menambah pelanggan baru.
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Pelanggan Baru',
            'validation' => \Config\Services::validation() // Kirim service validasi
        ];
        return view('Pelanggan/create', $data);
    }

    /**
     * Menyimpan data pelanggan baru.
     */
    public function store()
    {
        // Gunakan semua aturan validasi dari Model yang sudah diperbarui
        $rules = $this->pelangganModel->getValidationRules();
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Ambil data dari form (Hanya field yang ada di form yang diambil)
        $data = [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'alamat'         => $this->request->getPost('alamat'),
            // username, email, dan password secara eksplisit tidak diambil/disubmit
        ];
        
        // Catatan: Karena field username/email/password tidak dikirim dari form,
        // Model akan menganggapnya kosong. Jika di DB Anda masih NOT NULL,
        // Anda harus mengubah DB atau mengisi nilai default di sini.
        // Saya TIDAK mengisi nilai default agar DB Error muncul jika ada masalah.

        // Simpan data
        if ($this->pelangganModel->save($data)) {
            session()->setFlashdata('success', 'Data pelanggan berhasil ditambahkan.');
            return redirect()->to('/pelanggan');
        } else {
            // Penanganan Error Model/Database
            $db_errors = $this->pelangganModel->errors();
            $error_message = 'Gagal menambahkan data pelanggan.';

            if (!empty($db_errors)) {
                $error_message .= ' (Detail: ' . implode(', ', $db_errors) . ')';
            } else {
                $error_message .= ' (Kesalahan Database, cek log)';
            }

            session()->setFlashdata('error', $error_message);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit pelanggan.
     */
    public function edit($id)
    {
        $data = [
            'title'      => 'Edit Data Pelanggan',
            'pelanggan'  => $this->pelangganModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['pelanggan'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pelanggan dengan ID ' . $id . ' tidak ditemukan.');
        }

        return view('Pelanggan/edit', $data);
    }

    /**
     * Memproses update data pelanggan.
     */
    public function update($id)
    {
        // Gunakan semua aturan validasi dari Model yang sudah diperbarui
        $rules = $this->pelangganModel->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Ambil data dari form (Hanya field yang ada di form yang diambil)
        $data = [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'alamat'         => $this->request->getPost('alamat'),
            // username, email, dan password secara eksplisit tidak diambil/disubmit
        ];

        // Update data
        if ($this->pelangganModel->update($id, $data)) {
            session()->setFlashdata('success', 'Data pelanggan berhasil diperbarui.');
            return redirect()->to('/pelanggan');
        } else {
            // Penanganan Error Model/Database
            $db_errors = $this->pelangganModel->errors();
            $error_message = 'Gagal memperbarui data pelanggan.';

            if (!empty($db_errors)) {
                $error_message .= ' (Detail: ' . implode(', ', $db_errors) . ')';
            } else {
                $error_message .= ' (Kesalahan Database, cek log)';
            }
            
            session()->setFlashdata('error', $error_message);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Menghapus data pelanggan.
     */
    public function delete($id)
    {
        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
             session()->setFlashdata('error', 'Data pelanggan tidak ditemukan.');
             return redirect()->to('/pelanggan');
        }
        
        // Coba hapus
        if ($this->pelangganModel->delete($id)) {
            session()->setFlashdata('success', 'Data pelanggan berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data pelanggan. (Kesalahan Database, cek log)');
        }
        
        return redirect()->to('/pelanggan');
    }
}