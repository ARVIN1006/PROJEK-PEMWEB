<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PemasokModel;
use App\Models\PembelianModel;
use App\Models\DetailPembelianModel; // 1. TAMBAHKAN INI
use Exception;

class Pemasok extends BaseController
{
    protected $pemasokModel;
    protected $pembelianModel;
    protected $detailPembelianModel; // 2. TAMBAHKAN INI

    public function __construct()
    {
        // Penamaan model Anda sudah benar
        $this->pemasokModel   = new PemasokModel();
        $this->pembelianModel = new PembelianModel();
        $this->detailPembelianModel = new DetailPembelianModel(); // 3. TAMBAHKAN INI
    }

    // ================== INDEX (FUNGSI INI DIPERBARUI TOTAL) ==================
    public function index()
    {
        // 1. Ambil semua data pemasok
        $pemasokList = $this->pemasokModel->findAll();

        // 2. Buat Query Statistik Pembelian
        // Query ini akan menghitung SEMUA yang kita butuhkan dari tabel pembelian dan detail
        $purchaseStats = $this->pembelianModel
            ->select('
                pembelian.pemasok_id, 
                SUM(detail_pembelian.jumlah_pesan) as total_pesanan, 
                SUM(detail_pembelian.jumlah_diterima) as total_diterima, 
                COUNT(DISTINCT CASE WHEN pembelian.status_pembayaran = "Belum Lunas" THEN pembelian.pembelian_id ELSE NULL END) as total_belum_lunas
            ')
            ->join('detail_pembelian', 'detail_pembelian.pembelian_id = pembelian.pembelian_id', 'left')
            ->groupBy('pembelian.pemasok_id')
            ->findAll();

        // 3. Buat "Peta" Statistik agar mudah dicari
        $statsMap = [];
        foreach ($purchaseStats as $stats) {
            $statsMap[$stats['pemasok_id']] = $stats;
        }

        // 4. Gabungkan data
        $dataPemasokFinal = [];
        foreach ($pemasokList as $pemasok) {
            $pemasokId = $pemasok['pemasok_id'];

            // Ambil data statistik untuk pemasok ini
            $stats = $statsMap[$pemasokId] ?? null;

            // Hitung total pesanan (menggunakan data dari detail)
            // Kolom 'total_barang' di tabel pemasok sudah tidak kita pakai lagi
            $pemasok['total_barang_pesanan'] = $stats ? (int)$stats['total_pesanan'] : 0;

            // Hitung barang kurang
            $pemasok['barang_kurang'] = $stats ? (int)$stats['total_pesanan'] - (int)$stats['total_diterima'] : 0;

            // Tentukan status utang (pembayaran)
            $pemasok['status_utang'] = ($stats && $stats['total_belum_lunas'] > 0) ? 'Belum Lunas' : 'Lunas';

            // 'total_pembelian' di tabel pemasok masih akurat karena di-update oleh Controller Pembelian

            $dataPemasokFinal[] = $pemasok;
        }

        // 5. Kirim data ke view
        $data = [
            'title'   => 'Data Pemasok',
            'pemasok' => $dataPemasokFinal, // Kirim data yg sudah digabung
            'validation' => session('validation')
        ];

        return view('pemasok/index', $data);
    }

    // ================== SAVE (LOGIKA VALIDASI DIPERBARUI) ==================
    public function save()
    {
        // Validasi data (sesuai form modal tambah)
        $rules = [
            'kode_pemasok' => [
                'rules' => 'required|is_unique[pemasok.kode_pemasok]',
                'errors' => [
                    'required' => 'Kode pemasok wajib diisi.',
                    'is_unique' => 'Kode pemasok ini sudah ada.'
                ]
            ],
            'nama_pemasok' => [
                'rules' => 'required|is_unique[pemasok.nama_pemasok]',
                'errors' => [
                    'required' => 'Nama pemasok wajib diisi.',
                    'is_unique' => 'Nama pemasok ini sudah ada.'
                ]
            ],
            // ===== PERUBAHAN DI SINI: 'permit_empty' dihapus =====
            'kontak_person' => [
                'rules' => 'required|is_unique[pemasok.kontak_person]',
                'errors' => [
                    'required' => 'Kontak person wajib diisi.',
                    'is_unique' => 'Kontak person ini sudah ada.'
                ]
            ],
            'no_telp'      => [
                'rules' => 'required|is_unique[pemasok.no_telp]',
                'errors' => [
                    'required' => 'No. Telepon wajib diisi.',
                    'is_unique' => 'No. Telepon ini sudah ada.'
                ]
            ],
            // ===== PERUBAHAN DI SINI: 'permit_empty' dihapus =====
            'email' => [
                'rules' => 'required|valid_email|is_unique[pemasok.email]',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'is_unique' => 'Email ini sudah ada.',
                    'valid_email' => 'Format email tidak valid.'
                ]
            ],
            'alamat'       => 'required',
            'kategori'     => 'required',
            'status'       => 'required'
        ];

        if (!$this->validate($rules)) {
            // --- PERBAIKAN LOGIKA FLASH MESSAGE ---
            $errors = $this->validator->getErrors();
            $hasUniqueError = false;
            $uniqueErrorMessages = [];

            // Cek apakah ada error "is_unique"
            foreach ($errors as $field => $message) {
                if (strpos($message, 'sudah ada') !== false) {
                    $hasUniqueError = true;
                    $uniqueErrorMessages[] = $message; // Kumpulkan pesan error unik
                }
            }

            if ($hasUniqueError) {
                // Jika ada error "data sudah ada", tampilkan di flash message
                session()->setFlashdata('error', 'Data gagal disimpan. ' . implode(' ', $uniqueErrorMessages));
            }
            // Jika errornya HANYA "wajib diisi", JANGAN tampilkan flash message
            // Biarkan error muncul di form
            // --- AKHIR PERBAIKAN ---

            return redirect()->back()->withInput();
        }

        try {
            // Ambil data HANYA yang relevan dengan Pemasok
            $data = [
                'kode_pemasok'    => $this->request->getPost('kode_pemasok'),
                'nama_pemasok'  => $this->request->getPost('nama_pemasok'),
                'kontak_person' => $this->request->getPost('kontak_person'),
                'no_telp'       => $this->request->getPost('no_telp'),
                'email'         => $this->request->getPost('email'),
                'alamat'        => $this->request->getPost('alamat'),
                'kategori'      => $this->request->getPost('kategori'),
                'status'        => $this->request->getPost('status'),
                'catatan'       => $this->request->getPost('catatan'),
                // 'total_barang' dan 'total_pembelian' di-handle oleh Controller Pembelian
            ];

            // Simpan ke Pemasok Model
            $this->pemasokModel->insert($data);
            return redirect()->to('/pemasok')->with('success', 'Data pemasok berhasil disimpan.');
        } catch (Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // ================== EDIT (Sudah Benar) ==================
    public function edit($id)
    {
        $data = [
            'title'   => 'Edit Pemasok',
            'pemasok' => $this->pemasokModel->find($id),
            'validation' => session('validation') // Kirim validasi ke view jika ada
        ];

        if (empty($data['pemasok'])) {
            return redirect()->to('/pemasok')->with('error', 'Data pemasok tidak ditemukan.');
        }

        // Mengarahkan ke view 'pemasok/edit'
        return view('pemasok/edit', $data);
    }


    // ================== UPDATE (LOGIKA VALIDASI DIPERBARUI) ==================
    public function update($id)
    {
        // Validasi data (kode pemasok boleh sama dengan yg lama)
        $rules = [
            'kode_pemasok' => [
                'rules' => "required|is_unique[pemasok.kode_pemasok,pemasok_id,$id]",
                'errors' => [
                    'required' => 'Kode pemasok wajib diisi.',
                    'is_unique' => 'Kode pemasok ini sudah ada.'
                ]
            ],
            'nama_pemasok' => [
                'rules' => "required|is_unique[pemasok.nama_pemasok,pemasok_id,$id]",
                'errors' => [
                    'required' => 'Nama pemasok wajib diisi.',
                    'is_unique' => 'Nama pemasok ini sudah ada.'
                ]
            ],
            // ===== PERUBAHAN DI SINI: 'permit_empty' dihapus =====
            'kontak_person' => [
                'rules' => "required|is_unique[pemasok.kontak_person,pemasok_id,$id]",
                'errors' => [
                    'required' => 'Kontak person wajib diisi.',
                    'is_unique' => 'Kontak person ini sudah ada.'
                ]
            ],
            'no_telp'      => [
                'rules' => "required|is_unique[pemasok.no_telp,pemasok_id,$id]",
                'errors' => [
                    'required' => 'No. Telepon wajib diisi.',
                    'is_unique' => 'No. Telepon ini sudah ada.'
                ]
            ],
            // ===== PERUBAHAN DI SINI: 'permit_empty' dihapus =====
            'email' => [
                'rules' => "required|valid_email|is_unique[pemasok.email,pemasok_id,$id]",
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'is_unique' => 'Email ini sudah ada.',
                    'valid_email' => 'Format email tidak valid.'
                ]
            ],
            'alamat'       => 'required',
            'kategori'     => 'required',
            'status'       => 'required'
        ];

        if (!$this->validate($rules)) {
            // --- PERBAIKAN LOGIKA FLASH MESSAGE ---
            $errors = $this->validator->getErrors();
            $hasUniqueError = false;
            $uniqueErrorMessages = [];

            // Cek apakah ada error "is_unique"
            foreach ($errors as $field => $message) {
                if (strpos($message, 'sudah ada') !== false) {
                    $hasUniqueError = true;
                    $uniqueErrorMessages[] = $message; // Kumpulkan pesan error unik
                }
            }

            if ($hasUniqueError) {
                // Jika ada error "data sudah ada", tampilkan di flash message
                session()->setFlashdata('error', 'Data gagal diperbarui. ' . implode(' ', $uniqueErrorMessages));
            }
            // Jika errornya HANYA "wajib diisi", JANGAN tampilkan flash message
            // Biarkan error muncul di form
            // --- AKHIR PERBAIKAN ---

            return redirect()->back()->withInput();
        }

        try {
            // Ambil data HANYA yang relevan dengan Pemasok
            $data = [
                'kode_pemasok'    => $this->request->getPost('kode_pemasok'),
                'nama_pemasok'  => $this->request->getPost('nama_pemasok'),
                'kontak_person' => $this->request->getPost('kontak_person'),
                'no_telp'       => $this->request->getPost('no_telp'),
                'email'         => $this->request->getPost('email'),
                'alamat'        => $this->request->getPost('alamat'),
                'kategori'      => $this->request->getPost('kategori'),
                'status'        => $this->request->getPost('status'),
                'catatan'       => $this->request->getPost('catatan'),
            ];

            // Update ke Pemasok Model
            $this->pemasokModel->update($id, $data);
            return redirect()->to('/pemasok')->with('success', 'Data pemasok berhasil diperbarui.');
        } catch (Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // ================== DELETE (Sudah Aman) ==================
    public function delete($id)
    {
        try {
            // Cek apakah pemasok punya riwayat pembelian
            $pembelian = $this->pembelianModel->where('pemasok_id', $id)->first();

            if ($pembelian) {
                return redirect()->to('/pemasok')->with('error', 'Gagal menghapus! Pemasok ini memiliki riwayat pembelian.');
            }

            // Jika tidak ada, hapus
            $this->pemasokModel->delete($id);
            return redirect()->to('/pemasok')->with('success', 'Data pemasok berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->to('/pemasok')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
