<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembelianModel;
use App\Models\PemasokModel;
use App\Models\BahanBakuModel;      // 1. TAMBAHKAN MODEL BARU
use App\Models\DetailPembelianModel; // 2. TAMBAHKAN MODEL BARU
use Exception;

class Pembelian extends BaseController
{
    protected $pembelianModel;
    protected $pemasokModel;
    protected $bahanBakuModel;      // 3. DEFINISIKAN
    protected $detailPembelianModel; // 4. DEFINISIKAN

    public function __construct()
    {
        $this->pembelianModel       = new PembelianModel();
        $this->pemasokModel         = new PemasokModel();
        $this->bahanBakuModel       = new BahanBakuModel();      // 5. INISIALISASI
        $this->detailPembelianModel = new DetailPembelianModel(); // 6. INISIALISASI
    }

    // ================== INDEX (FUNGSI INI DIPERBARUI) ==================
    public function index()
    {
        // Query ini diperbarui untuk menghitung total kuantitas barang dari tabel detail
        $pembelianData = $this->pembelianModel
            ->select('
                pembelian.*, 
                pemasok.nama_pemasok,
                SUM(detail_pembelian.jumlah_pesan) as total_pesanan,
                SUM(detail_pembelian.jumlah_diterima) as total_diterima
            ')
            ->join('pemasok', 'pemasok.pemasok_id = pembelian.pemasok_id', 'left')
            ->join('detail_pembelian', 'detail_pembelian.pembelian_id = pembelian.pembelian_id', 'left')
            ->groupBy('pembelian.pembelian_id') // Group berdasarkan ID pembelian
            ->orderBy('pembelian.tanggal_pembelian', 'DESC') // Urutkan
            ->findAll();

        $data = [
            'title'      => 'Data Pembelian',
            'pembelian'  => $pembelianData, // Kirim data yang sudah di-join
            'pemasok'    => $this->pemasokModel->findAll(),
            'bahan_baku' => $this->bahanBakuModel->findAll(), // <-- INI PERBAIKANNYA
        ];

        return view('pembelian/index', $data);
    }

    // ================== DETAIL (FUNGSI BARU) ==================
    // Halaman ini yang diminta oleh "Tombol Detail"
    public function detail($pembelian_id)
    {
        $pembelian = $this->pembelianModel
            ->select('pembelian.*, pemasok.nama_pemasok')
            ->join('pemasok', 'pemasok.pemasok_id = pembelian.pemasok_id', 'left')
            ->find($pembelian_id);

        if (!$pembelian) {
            return redirect()->to('/pembelian')->with('error', 'Data pembelian tidak ditemukan.');
        }

        $detail_items = $this->detailPembelianModel
            ->select('detail_pembelian.*, bahan_baku.nama_bahan, bahan_baku.satuan')
            ->join('bahan_baku', 'bahan_baku.bahan_id = detail_pembelian.bahan_id', 'left')
            ->where('pembelian_id', $pembelian_id)
            ->findAll();

        $data = [
            'title'     => 'Detail Pembelian ' . $pembelian['kode_pembelian'],
            'pembelian' => $pembelian,
            'detail_items' => $detail_items
        ];

        return view('pembelian/detail', $data);
    }


    // ================== SAVE (DIROMBAK TOTAL) ==================
    public function save()
    {
        // --- 1. Validasi Data Induk ---
        $rules = [
            'tanggal_pembelian' => 'required',
            'kode_pembelian'    => 'required|is_unique[pembelian.kode_pembelian]',
            'pemasok_id'        => 'required|is_natural_no_zero',
            'status_pembayaran' => 'required',
            'status_pembelian'  => 'required',
            // Validasi untuk item (minimal 1 item)
            'bahan_id'          => 'required', // Memastikan minimal ada 1 bahan_id
            'bahan_id.*'        => 'required',
            'jumlah_pesan.*'    => 'required|numeric|greater_than[0]',
            'harga_satuan.*'    => 'required|numeric'
        ];
        
        $errors = [
             'bahan_id' => [
                'required' => 'Anda harus menambahkan minimal 1 item barang.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            // Jika validasi gagal, kembalikan dengan error
            $validationErrors = $this->validator->getErrors();
            
            // Cek error 'is_unique' atau 'required' di item
            $errorMsg = 'Data gagal disimpan. ';
            if (isset($validationErrors['kode_pembelian'])) {
                $errorMsg .= $validationErrors['kode_pembelian'];
            } elseif (isset($validationErrors['bahan_id'])) {
                 $errorMsg .= $validationErrors['bahan_id'];
            }
            
            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }

        // --- 2. Simpan Data Induk (Pembelian) ---
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai Database Transaction

        try {
            $pembelianData = [
                'kode_pembelian'    => $this->request->getPost('kode_pembelian'),
                'pemasok_id'        => $this->request->getPost('pemasok_id'),
                'tanggal_pembelian' => $this->request->getPost('tanggal_pembelian'),
                'status_pembelian'  => $this->request->getPost('status_pembelian'),
                'status_pembayaran' => $this->request->getPost('status_pembayaran'),
                'catatan'           => $this->request->getPost('catatan'),
                'total_harga'       => 0 // Kita isi 0 dulu, akan di-update nanti
            ];

            $this->pembelianModel->insert($pembelianData);
            $pembelianID = $this->pembelianModel->getInsertID(); // Ambil ID dari data yg baru disimpan

            // --- 3. Simpan Data Detail (Item Barang) ---
            $bahan_ids     = $this->request->getPost('bahan_id');
            $jumlah_pesan  = $this->request->getPost('jumlah_pesan');
            $harga_satuan  = $this->request->getPost('harga_satuan');
            
            $grandTotal = 0;

            if (!empty($bahan_ids)) {
                foreach ($bahan_ids as $index => $bahan_id) {
                    $jml_pesan = (float) $jumlah_pesan[$index];
                    $harga     = (float) $harga_satuan[$index];
                    $subtotal  = $jml_pesan * $harga;
                    
                    $jml_diterima = 0;
                    // Jika status pembelian "Selesai", anggap semua barang langsung diterima
                    if($pembelianData['status_pembelian'] == 'Selesai') {
                        $jml_diterima = $jml_pesan;
                    }

                    $detailData = [
                        'pembelian_id'  => $pembelianID,
                        'bahan_id'      => $bahan_id,
                        'jumlah_pesan'  => $jml_pesan,
                        'jumlah_diterima' => $jml_diterima, // Logika barang diterima
                        'harga_satuan'  => $harga,
                        'subtotal'      => $subtotal
                    ];

                    $this->detailPembelianModel->insert($detailData);
                    $grandTotal += $subtotal;
                }
            }

            // --- 4. Update Total Harga di Tabel Induk ---
            $this->pembelianModel->update($pembelianID, ['total_harga' => $grandTotal]);

            // --- 5. Update Total Pembelian di Pemasok ---
            $this->pemasokModel
                ->where('pemasok_id', $pembelianData['pemasok_id'])
                ->set('total_pembelian', 'total_pembelian + ' . $grandTotal, false)
                ->update();

            $db->transComplete(); // Selesaikan Transaction

            return redirect()->to('/pembelian')->with('success', 'Data pembelian baru berhasil disimpan.');
        
        } catch (Exception $e) {
            $db->transRollback(); // Batalkan semua jika ada error
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // ================== EDIT (Akan kita buat nanti) ==================
    public function edit($id)
    {
        // Halaman edit kini jadi sangat kompleks, kita akan buat di langkah berikutnya
        return redirect()->to('/pembelian/detail/' . $id)->with('error', 'Halaman edit belum di-upgrade.');
    }

    // ================== UPDATE (Kosongkan dulu) ==================
    public function update($id)
    {
       // Logika update akan dirombak total
       return redirect()->to('/pembelian');
    }

    // ================== DELETE (Diperbarui) ==================
    public function delete($id)
    {
        $pembelian = $this->pembelianModel->find($id);

        if ($pembelian) {
            $db = \Config\Database::connect();
            $db->transStart();
            try {
                $pemasokId  = $pembelian['pemasok_id'];
                $totalHarga = (float) $pembelian['total_harga'];

                // 1. Kurangi total pembelian dari Pemasok
                if ($pemasokId && $totalHarga > 0) {
                    $this->pemasokModel
                         ->where('pemasok_id', $pemasokId)
                         ->set('total_pembelian', 'total_pembelian - ' . $totalHarga, false)
                         ->update();
                }

                // 2. Hapus data pembelian (detail_pembelian akan ikut terhapus otomatis berkat 'ON DELETE CASCADE' di SQL)
                $this->pembelianModel->delete($id);

                $db->transComplete();
                return redirect()->to('/pembelian')->with('success', 'Data pembelian berhasil dihapus.');
           
            } catch (Exception $e) {
                $db->transRollback();
                session()->setFlashdata('error', 'Data gagal dihapus. Terjadi kesalahan: ' . $e->getMessage());
                return redirect()->to('/pembelian');
            }
        }
        return redirect()->to('/pembelian')->with('error', 'Data pembelian tidak ditemukan.');
    }

    // ================== FUNGSI utang (Sudah Benar) ==================
    public function utang($pemasok_id = null)
    {
        if ($pemasok_id === null) {
            return redirect()->to('/pemasok')->with('error', 'Pemasok tidak valid.');
        }
        $pemasok = $this->pemasokModel->find($pemasok_id);
        if (!$pemasok) {
            return redirect()->to('/pemasok')->with('error', 'Data pemasok tidak ditemukan.');
        }
            
        // --- QUERY BARU YANG MENGHITUNG TOTAL BARANG DARI DETAIL ---
         $pembelian = $this->pembelianModel
            ->select('pembelian.*, pemasok.nama_pemasok, SUM(detail_pembelian.jumlah_pesan) as total_barang')
            ->join('pemasok', 'pemasok.pemasok_id = pembelian.pemasok_id', 'left')
            ->join('detail_pembelian', 'detail_pembelian.pembelian_id = pembelian.pembelian_id', 'left')
            ->where('pembelian.pemasok_id', $pemasok_id)
            ->where('pembelian.status_pembayaran', 'Belum Lunas')
            ->groupBy('pembelian.pembelian_id') // Group berdasarkan pembelian
            ->findAll();

        $data = [
            'title'     => 'Data utang ke ' . $pemasok['nama_pemasok'],
            'pemasok'   => $pemasok,
            'pembelian' => $pembelian, 
        ];
        return view('Pemasok/utang', $data);
    }
}