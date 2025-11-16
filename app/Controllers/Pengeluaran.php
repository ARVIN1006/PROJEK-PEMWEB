<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengeluaranModel;

class Pengeluaran extends BaseController
{
    protected $pengeluaranModel;

    public function __construct()
    {
        $this->pengeluaranModel = new PengeluaranModel();
    }

    /**
     * Menampilkan halaman utama daftar pengeluaran.
     */
    public function index()
    {
        $data = [
            'title'       => 'Daftar Transaksi Pengeluaran',
            'pengeluaran' => $this->pengeluaranModel->orderBy('tanggal', 'DESC')->findAll()
        ];
        
        return view('Pengeluaran/index', $data);
    }

    /**
     * Menampilkan form untuk menambah pengeluaran baru.
     * (Anda sudah punya rute untuk 'tambah', saya ubah jadi 'create' agar konsisten)
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Pengeluaran Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('Pengeluaran/create', $data);
    }

    /**
     * Menyimpan data pengeluaran baru.
     */
    public function store()
    {
        if (!$this->validate($this->pengeluaranModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'tanggal'              => $this->request->getPost('tanggal'),
            'kategori_pengeluaran' => $this->request->getPost('kategori_pengeluaran'),
            'keterangan'           => $this->request->getPost('keterangan'),
            'jumlah'               => $this->request->getPost('jumlah'),
        ];

        if ($this->pengeluaranModel->save($data)) {
            session()->setFlashdata('success', 'Data pengeluaran berhasil ditambahkan.');
            return redirect()->to('/pengeluaran');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan data pengeluaran.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit pengeluaran.
     */
    public function edit($id)
    {
        $data = [
            'title'       => 'Edit Data Pengeluaran',
            'pengeluaran' => $this->pengeluaranModel->find($id),
            'validation'  => \Config\Services::validation()
        ];

        if (empty($data['pengeluaran'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengeluaran dengan ID ' . $id . ' tidak ditemukan.');
        }

        return view('Pengeluaran/edit', $data);
    }

    /**
     * Memproses update data pengeluaran.
     */
    public function update($id)
    {
        if (!$this->validate($this->pengeluaranModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'tanggal'              => $this->request->getPost('tanggal'),
            'kategori_pengeluaran' => $this->request->getPost('kategori_pengeluaran'),
            'keterangan'           => $this->request->getPost('keterangan'),
            'jumlah'               => $this->request->getPost('jumlah'),
        ];

        if ($this->pengeluaranModel->update($id, $data)) {
            session()->setFlashdata('success', 'Data pengeluaran berhasil diperbarui.');
            return redirect()->to('/pengeluaran');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui data pengeluaran.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Menghapus data pengeluaran.
     */
    public function delete($id)
    {
        $pengeluaran = $this->pengeluaranModel->find($id);
        if (!$pengeluaran) {
             session()->setFlashdata('error', 'Data pengeluaran tidak ditemukan.');
             return redirect()->to('/pengeluaran');
        }

        if ($this->pengeluaranModel->delete($id)) {
            session()->setFlashdata('success', 'Data pengeluaran berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data pengeluaran.');
        }
        
        return redirect()->to('/pengeluaran');
    }
}