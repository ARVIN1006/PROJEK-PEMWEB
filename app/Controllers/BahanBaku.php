<?php

namespace App\Controllers;

use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    protected $bahanBakuModel;

    public function __construct()
    {
        $this->bahanBakuModel = new BahanBakuModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Bahan Baku',
            'bahan_baku' => $this->bahanBakuModel->findAll(),
            'validation' => session('validation')
        ];
        return view('bahan_baku/index', $data);
    }

    public function save()
    {
        $rules = [
            'nama_bahan' => 'required|is_unique[bahan_baku.nama_bahan]',
            'satuan'     => 'required',
            'harga_satuan' => 'required|numeric|greater_than_equal_to[0]' // TAMBAH VALIDASI HARGA
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->bahanBakuModel->save([
            'nama_bahan'   => $this->request->getPost('nama_bahan'),
            'kategori'     => $this->request->getPost('kategori'),
            'satuan'       => $this->request->getPost('satuan'),
            'harga_satuan' => $this->request->getPost('harga_satuan'), // SIMPAN HARGA
        ]);

        return redirect()->to('/bahanbaku')->with('success', 'Bahan baku berhasil disimpan.');
    }

    public function update($id)
    {
        $rules = [
            'nama_bahan' => "required|is_unique[bahan_baku.nama_bahan,bahan_id,$id]",
            'satuan'     => 'required',
            'harga_satuan' => 'required|numeric|greater_than_equal_to[0]' // TAMBAH VALIDASI HARGA
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->bahanBakuModel->update($id, [
            'nama_bahan'   => $this->request->getPost('nama_bahan'),
            'kategori'     => $this->request->getPost('kategori'),
            'satuan'       => $this->request->getPost('satuan'),
            'harga_satuan' => $this->request->getPost('harga_satuan'), // UPDATE HARGA
        ]);

        return redirect()->to('/bahanbaku')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function delete($id)
    {
        try {
            $this->bahanBakuModel->delete($id);
            return redirect()->to('/bahanbaku')->with('success', 'Bahan baku berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/bahanbaku')->with('error', 'Gagal menghapus! Bahan baku mungkin sedang digunakan di Data Pembelian.');
        }
    }
}