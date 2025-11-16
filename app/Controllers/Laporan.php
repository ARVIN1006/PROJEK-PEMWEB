<?php

namespace App\Controllers;

use App\Models\PemasokModel;
use App\Models\PembelianModel;
use App\Models\BahanBakuModel; // IMPORT MODEL BAHAN BAKU

// NAMA CLASS DIUBAH DARI 'LaporanController' MENJADI 'Laporan' (sesuai routes group Anda)
class Laporan extends BaseController
{
    protected $pemasokModel;
    protected $pembelianModel;
    protected $bahanBakuModel; // DEKLARASI PROPERTY BAHAN BAKU

    public function __construct()
    {
        // Load model Anda di constructor
        $this->pemasokModel = new PemasokModel();
        $this->pembelianModel = new PembelianModel();
        $this->bahanBakuModel = new BahanBakuModel();
    }

    // FUNGSI index() Anda akan berjalan saat URL laporan/pembelian diakses
    public function index()
    {
        // Ambil semua data dari model
        $data = [
            'pemasok' => $this->pemasokModel->findAll(),
            'pembelian' => $this->pembelianModel->findAll(),
            'bahan_baku' => $this->bahanBakuModel->findAll() 
            // Catatan: Untuk data pembelian, Anda mungkin perlu
            // JOIN dengan tabel pemasok untuk mendapatkan nama pemasok
        ];

        // Tampilkan view laporan pembelian
        return view('laporan/pembelian', $data);
    }

    // Tambahkan fungsi-fungsi laporan lain di sini agar Routes Anda tidak error
    public function labaRugi()
    {
        return view('laporan/laba_rugi');
    }
    public function posisiKeuangan()
    {
        return view('laporan/posisi_keuangan');
    }
    public function piutang()
    {
        return view('laporan/piutang');
    }
    public function utang()
    {
        return view('laporan/utang');
    }
    public function penjualan()
    {
        return view('laporan/penjualan');
    }
    public function pembelian()
    {
        return $this->index();
    } // Arahkan pembelian ke index
}
