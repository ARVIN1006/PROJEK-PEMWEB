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
        // Aturan validasi
        $rules = $this->pelangganModel->getValidationRules([
            'password' => 'required|min_length[6]' // Wajibkan password saat buat baru
        ]);
        
        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan ke form create dengan pesan error
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Ambil data dari form
        $data = [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'alamat'         => $this->request->getPost('alamat'),
            'username'       => $this->request->getPost('username'),
            'email'          => $this->request->getPost('email'),
            'password'       => $this->request->getPost('password'),
        ];

        // Simpan data (Model akan otomatis hash password via callback)
        if ($this->pelangganModel->save($data)) {
            session()->setFlashdata('success', 'Data pelanggan berhasil ditambahkan.');
            return redirect()->to('/pelanggan');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan data pelanggan.');
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
        // Dapatkan data lama untuk aturan is_unique
        $oldData = $this->pelangganModel->find($id);
        
        // Aturan validasi
        // 'pelanggan_id' digunakan untuk memberitahu aturan is_unique agar mengabaikan ID saat ini
        $rules = $this->pelangganModel->getValidationRules([
            'pelanggan_id' => $id 
        ]);

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Ambil data dari form
        $data = [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'alamat'         => $this->request->getPost('alamat'),
            'username'       => $this->request->getPost('username'),
            'email'          => $this->request->getPost('email'),
        ];

        // Cek apakah password diisi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password; // Model akan hash otomatis
        }
        // Jika password kosong, model akan otomatis mengabaikannya (lihat callback)

        // Update data
        if ($this->pelangganModel->update($id, $data)) {
            session()->setFlashdata('success', 'Data pelanggan berhasil diperbarui.');
            return redirect()->to('/pelanggan');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui data pelanggan.');
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
            session()->setFlashdata('error', 'Gagal menghapus data pelanggan.');
        }
        
        return redirect()->to('/pelanggan');
    }
}