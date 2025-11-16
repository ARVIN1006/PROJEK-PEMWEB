<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenjualanModel;

class Piutang extends BaseController
{
    protected $penjualanModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
    }

    /**
     * Menampilkan daftar piutang (penjualan yang belum lunas).
     */
    public function index()
    {
        $data = [
            'title'     => 'Daftar Piutang Pelanggan',
            'penjualan' => $this->penjualanModel->getPenjualanWithPelanggan()
        ];
        
        return view('Piutang/index', $data);
    }

    /**
     * Mengubah status pembayaran (Lunas / Belum Lunas).
     * Ini adalah fungsi "Update" untuk status pembayaran.
     */
    public function updateStatus($penjualan_id)
    {
        $penjualan = $this->penjualanModel->find($penjualan_id);

        if (!$penjualan) {
            session()->setFlashdata('error', 'Data penjualan tidak ditemukan.');
            return redirect()->to('/piutang');
        }

        // Toggle status
        $newStatus = ($penjualan['status_pembayaran'] == 'Lunas') ? 'Belum Lunas' : 'Lunas';

        if ($this->penjualanModel->update($penjualan_id, ['status_pembayaran' => $newStatus])) {
            session()->setFlashdata('success', 'Status pembayaran berhasil diubah menjadi ' . $newStatus);
        } else {
            session()->setFlashdata('error', 'Gagal mengubah status pembayaran.');
        }

        return redirect()->to('/piutang');
    }
}