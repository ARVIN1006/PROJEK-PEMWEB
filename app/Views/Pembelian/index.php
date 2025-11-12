<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<?php helper('form'); ?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Data Pembelian</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="nav-link">Home</a></li>
                    <li class="breadcrumb-item active">Data Pembelian</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">

    <div class="row mb-3">
        <div class="col">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPembelian">
                <i class="fas fa-plus"></i> Tambah Data Pembelian
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabel Data Pembelian (Sudah diubah) -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-maroon text-white">
                    <h3 class="card-title">Daftar Pembelian</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Pembelian</th>
                                <th>Pemasok</th>
                                <th>Total Pesan</th> <!-- 1. TAMBAH KOLOM -->
                                <th>Brg Kurang</th> <!-- 2. TAMBAH KOLOM -->
                                <th>Total Harga</th>
                                <th>Status Pembayaran</th>
                                <th>Status Pembelian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($pembelian)): ?>
                                <?php $no = 1;
                                foreach ($pembelian as $b):
                                    // Hitung barang kurang
                                    $barang_kurang = (int)($b['total_pesanan'] ?? 0) - (int)($b['total_diterima'] ?? 0);
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($b['tanggal_pembelian']) ?></td>
                                        <td><?= esc($b['kode_pembelian']) ?></td>
                                        <td><?= esc($b['nama_pemasok']) ?></td>

                                        <!-- 1. TOTAL PESAN (DARI QUERY BARU) -->
                                        <td class="text-center"><?= esc($b['total_pesanan'] ?? 0) ?></td>

                                        <!-- 2. BARANG KURANG (DARI QUERY BARU) -->
                                        <td class="text-center">
                                            <?php if ($barang_kurang > 0): ?>
                                                <span class="badge bg-danger"><?= $barang_kurang ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success">0</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-end"><?= number_format($b['total_harga'], 0, ',', '.') ?></td>

                                        <td class="text-center">
                                            <?php if (esc($b['status_pembayaran']) == 'Lunas') : ?>
                                                <span class="badge bg-success">Lunas</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">Belum Lunas</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if (esc($b['status_pembelian']) == 'Selesai') : ?>
                                                <span class="badge bg-primary">Selesai</span>
                                            <?php else : ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <!-- TOMBOL DETAIL (BARU) -->
                                            <a href="<?= base_url('pembelian/detail/' . $b['pembelian_id']) ?>"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <a href="<?= base_url('pembelian/delete/' . $b['pembelian_id']) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <!-- 3. UBAH COLSPAN JADI 10 -->
                                    <td colspan="10" class="text-center text-muted">Belum ada data pembelian</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL TAMBAH PEMBELIAN (DIROMBAK TOTAL) ===================== -->
<div class="modal fade" id="modalTambahPembelian" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl"> <!-- Modal diperbesar (modal-xl) -->
        <div class="modal-content">
            <form action="<?= base_url('pembelian/save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-maroon text-white">
                    <h5 class="modal-title">Tambah Data Pembelian</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Data Induk -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Pembelian</label>
                            <input type="text" name="kode_pembelian" class="form-control <?= service('validation')->hasError('kode_pembelian') ? 'is-invalid' : '' ?>" placeholder="PB001" value="<?= old('kode_pembelian') ?>" required>
                            <?= validation_show_error('kode_pembelian') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal_pembelian" class="form-control <?= service('validation')->hasError('tanggal_pembelian') ? 'is-invalid' : '' ?>" value="<?= old('tanggal_pembelian', date('Y-m-d')) ?>" required>
                            <?= validation_show_error('tanggal_pembelian') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pemasok</label>
                            <select name="pemasok_id" class="form-select <?= service('validation')->hasError('pemasok_id') ? 'is-invalid' : '' ?>" required>
                                <option value="">-- Pilih Pemasok --</option>
                                <?php foreach ($pemasok as $p): ?>
                                    <option value="<?= esc($p['pemasok_id']) ?>" <?= old('pemasok_id') == $p['pemasok_id'] ? 'selected' : '' ?>><?= esc($p['nama_pemasok']) ?></option>
                                <?php endforeach ?>
                            </select>
                            <?= validation_show_error('pemasok_id') ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Pembayaran</label>
                            <select name="status_pembayaran" class="form-select" required>
                                <option value="Belum Lunas" selected>Belum Lunas</option>
                                <option value="Lunas">Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Pembelian (Penerimaan)</label>
                            <select name="status_pembelian" class="form-select" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan pembelian..."><?= old('catatan') ?></textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- Data Detail (Item Barang) -->
                    <h5 class="mb-3">Item Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th>Bahan Baku</th>
                                    <th>Jumlah Pesan</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="item-list">
                                <!-- Baris item akan ditambahkan oleh JavaScript di sini -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Keseluruhan</strong></td>
                                    <td class="text-end" id="grand-total">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="tambahItem()">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                    <?= validation_show_error('bahan_id') ?>
                    <?= validation_show_error('bahan_id.*') ?>
                    <?= validation_show_error('jumlah_pesan.*') ?>
                    <?= validation_show_error('harga_satuan.*') ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Pembelian</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ===================== /MODAL ===================== -->

<?= $this->endSection() ?>

<!-- ===================== JAVASCRIPT ===================== -->
<?= $this->section('page_scripts') // Pastikan 'page_scripts' ada di template.php Anda 
?>
<script>
    // Siapkan data bahan baku dari PHP ke JavaScript
    // Cek dulu apakah variabel $bahan_baku ada dan tidak kosong
    const bahanBakuData = <?= !empty($bahan_baku) ? json_encode($bahan_baku) : '[]' ?>;

    // Buat opsi HTML untuk <select>
    let bahanBakuOptions = '<option value="">-- Pilih Bahan --</option>';
    if (bahanBakuData.length > 0) {
        bahanBakuData.forEach(item => {
            bahanBakuOptions += `<option value="${item.bahan_id}" data-satuan="${item.satuan}">${item.nama_bahan} (${item.satuan})</option>`;
        });
    } else {
        bahanBakuOptions = '<option value="">-- Silakan isi Data Bahan Baku dulu --</option>';
    }


    function tambahItem() {
        const itemList = document.getElementById('item-list');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <select name="bahan_id[]" class="form-select" onchange="cekSatuan(this)" required>
                    ${bahanBakuOptions}
                </select>
            </td>
            <td>
                <input type="number" name="jumlah_pesan[]" class="form-control text-end" value="1" min="1" oninput="hitungSubtotal(this)" required>
            </td>
            <td>
                <input type="number" name="harga_satuan[]" class="form-control text-end" value="0" min="0" oninput="hitungSubtotal(this)" required>
            </td>
            <td>
                <input type="text" name="subtotal[]" class="form-control text-end" value="Rp 0" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        itemList.appendChild(newRow);
    }

    function cekSatuan(selectElement) {
        // Fungsi ini bisa digunakan nanti jika Anda ingin menampilkan satuan
        // const selectedOption = selectElement.options[selectElement.selectedIndex];
        // const satuan = selectedOption.getAttribute('data-satuan');
        // console.log('Satuan: ', satuan);
    }

    function hitungSubtotal(inputElement) {
        const row = inputElement.closest('tr');
        const jumlah = parseFloat(row.querySelector('[name="jumlah_pesan[]"]').value) || 0;
        const harga = parseFloat(row.querySelector('[name="harga_satuan[]"]').value) || 0;
        const subtotal = jumlah * harga;

        row.querySelector('[name="subtotal[]"]').value = formatRupiah(subtotal);
        hitungGrandTotal();
    }

    function hitungGrandTotal() {
        let grandTotal = 0;
        const subtotalInputs = document.querySelectorAll('[name="subtotal[]"]');

        subtotalInputs.forEach(input => {
            grandTotal += parseFloat(input.value.replace(/[^0-9]/g, '')) || 0;
        });

        document.getElementById('grand-total').textContent = formatRupiah(grandTotal);
    }

    function hapusItem(button) {
        button.closest('tr').remove();
        hitungGrandTotal();
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Skrip untuk membuka modal jika ada error validasi
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session('validation') && !empty(session('validation')->getErrors())) : ?>
            var hasUniqueError = <?= session()->getFlashdata('error') ? 'true' : 'false' ?>;
            if (!hasUniqueError) {
                var modalTambah = new bootstrap.Modal(document.getElementById('modalTambahPembelian'));
                modalTambah.show();
            }
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>