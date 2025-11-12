<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use CodeIgniter\Controller;

class Produk extends Controller
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        // Helpers form dan url diperlukan untuk validasi dan redirect
        helper(['form', 'url']);
    }

    // A. Tampilkan Form Tambah Produk (CREATE - VIEW)
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Produk Baru',
            'validation' => \Config\Services::validation()
        ];

        // Memuat View: app/Views/produk/tambah.php
        return view('produk/tambah', $data);
    }

    // B. Simpan Data Produk Baru (CREATE - ACTION)
    public function save()
    {
        // 1. Tentukan Aturan Validasi
        $rules = [
            'kategori_id' => 'required|integer',
            'nama_produk' => 'required|max_length[100]|is_unique[produk.nama_produk]', // Cek nama produk unik
            'harga'       => 'required', // Diperiksa setelah parsing
            'stok'        => 'required|integer|greater_than_equal_to[0]'
        ];

        // 2. Lakukan Validasi Input
        if (!$this->validate($rules)) {
            return redirect()->to('/produk/tambah')
                ->withInput()
                ->with('validation_errors', $this->validator->getErrors());
        }

        // --- KODE PARSING HARGA ---
        $harga_input = $this->request->getPost('harga');
        $harga_bersih = str_replace(['.', ','], '', $harga_input);

        if (!is_numeric($harga_bersih)) {
            session()->setFlashdata('pesan', '❌ GAGAL! Harga harus berupa angka valid setelah pembersihan.');
            return redirect()->to('/produk/tambah')->withInput();
        }
        // --- AKHIR KODE PARSING ---

        // 3. Simpan Data ke Database (TEST UTAMA)
        try {
            $this->produkModel->save([
                'kategori_id' => $this->request->getPost('kategori_id'),
                'nama_produk' => $this->request->getPost('nama_produk'),
                'harga'       => $harga_bersih, // Gunakan harga yang sudah bersih
                'stok'        => $this->request->getPost('stok'),
                'deskripsi'   => $this->request->getPost('deskripsi')
            ]);

            session()->setFlashdata('pesan', 'Produk **' . $this->request->getPost('nama_produk') . '** berhasil ditambahkan ke database.');
        } catch (\Exception $e) {
            session()->setFlashdata('pesan', 'GAGAL! Error Database: ' . $e->getMessage());
        }

        return redirect()->to('/produk');
    }

    // C. Tampilkan Daftar Produk (READ)
    public function index()
    {
        $data = [
            'title'  => 'Hasil Pengujian Produk',
            'produk' => $this->produkModel->findAll()
        ];
        return view('produk/index', $data);
    }

    // <-- MULAI SISIPAN EDIT/UPDATE DI SINI -->

    // D. Tampilkan Form Edit Produk (UPDATE - VIEW LOAD)
    public function edit($produk_id)
    {
        // Ambil data produk berdasarkan ID
        $data = [
            'title' => 'Edit Produk',
            'validation' => \Config\Services::validation(),
            'produk' => $this->produkModel->find($produk_id) // Ambil data
        ];

        if (empty($data['produk'])) {
            // Jika data tidak ditemukan, lemparkan error 404
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk dengan ID ' . $produk_id . ' tidak ditemukan.');
        }

        // Memuat View: app/Views/produk/edit.php
        return view('produk/edit', $data);
    }

    // E. Proses Update Data Produk (UPDATE - ACTION)
    public function update($produk_id)
    {
        // 1. Tentukan Aturan Validasi (Hanya cek UNIQUE jika nama produk berubah)
        $produkLama = $this->produkModel->find($produk_id);
        if ($produkLama['nama_produk'] == $this->request->getPost('nama_produk')) {
            $rule_nama = 'required|max_length[100]'; // Nama tidak berubah, tidak perlu cek unique
        } else {
            $rule_nama = 'required|max_length[100]|is_unique[produk.nama_produk]'; // Nama berubah, wajib cek unique
        }

        $rules = [
            'kategori_id' => 'required|integer',
            'nama_produk' => $rule_nama,
            'harga'       => 'required', // Tetap required untuk parsing
            'stok'        => 'required|integer|greater_than_equal_to[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/produk/edit/' . $produk_id)
                ->withInput()
                ->with('validation_errors', $this->validator->getErrors());
        }

        // Parsing Harga (seperti di method save())
        $harga_input = $this->request->getPost('harga');
        $harga_bersih = str_replace(['.', ','], '', $harga_input);

        if (!is_numeric($harga_bersih)) {
            session()->setFlashdata('pesan', '❌ GAGAL! Harga harus berupa angka valid setelah pembersihan.');
            return redirect()->to('/produk/edit/' . $produk_id)->withInput();
        }

        // 2. Simpan Data (UPDATE)
        try {
            $this->produkModel->update($produk_id, [
                'kategori_id' => $this->request->getPost('kategori_id'),
                'nama_produk' => $this->request->getPost('nama_produk'),
                'harga'       => $harga_bersih, // Gunakan harga yang sudah bersih
                'stok'        => $this->request->getPost('stok'),
                'deskripsi'   => $this->request->getPost('deskripsi')
            ]);

            session()->setFlashdata('pesan', '✅ Produk **' . $this->request->getPost('nama_produk') . '** berhasil diubah.');
        } catch (\Exception $e) {
            session()->setFlashdata('pesan', '❌ GAGAL! Error Database: ' . $e->getMessage());
        }

        return redirect()->to('/produk');
    }

    // F. Proses Hapus Data Produk (DELETE - ACTION)
    public function delete($produk_id)
    {
        $produk = $this->produkModel->find($produk_id);

        if ($produk) {
            $this->produkModel->delete($produk_id);
            session()->setFlashdata('pesan', '✅ Produk **' . $produk['nama_produk'] . '** berhasil dihapus.');
        } else {
            session()->setFlashdata('pesan', '❌ GAGAL! Produk tidak ditemukan.');
        }

        return redirect()->to('/produk');
    }

    // <-- AKHIR SISIPAN EDIT/UPDATE DI SINI -->
}
