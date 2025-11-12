<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<?php helper('form'); ?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Data Pemasok</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="nav-link">Home</a></li>
                    <li class="breadcrumb-item active">Data Pemasok</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">

    <div class="row mb-3">
        <div class="col">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Data Pemasok
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <!-- Menampilkan error validasi HANYA DARI FLASHDATA (untuk "data sudah ada") -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-maroon text-white">
                    <h3 class="card-title">Daftar Pemasok</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Pemasok</th>
                                <th>Kontak Person</th>
                                <th>Kategori</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th>Total Pesanan</th> <!-- 1. GANTI NAMA -->
                                <th>Barang Kurang</th> <!-- 2. TAMBAH KOLOM -->
                                <th>Total Pembelian</th>
                                <th>Status Utang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pemasok)): ?>
                                <?php $no = 1;
                                foreach ($pemasok as $p): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($p['kode_pemasok'] ?? '-') ?></td>
                                        <td><?= esc($p['nama_pemasok']) ?></td>
                                        <td><?= esc($p['kontak_person'] ?? '-') ?></td>
                                        <td><?= esc($p['kategori'] ?? '-') ?></td>
                                        <td><?= esc($p['email'] ?? '-') ?></td>
                                        <td><?= esc($p['alamat']) ?></td>
                                        <td><?= esc($p['no_telp']) ?></td>

                                        <td class="text-center">
                                            <?php if (esc($p['status'] ?? 'Aktif') == 'Aktif') : ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- 1. GANTI DATA (menggunakan total_barang_pesanan) -->
                                        <td class="text-center"><?= esc($p['total_barang_pesanan'] ?? 0) ?></td>

                                        <!-- 2. TAMBAH KOLOM (menampilkan barang_kurang) -->
                                        <td class="text-center">
                                            <?php if (($p['barang_kurang'] ?? 0) > 0): ?>
                                                <span class="badge bg-danger"><?= esc($p['barang_kurang']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success">0</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-end"><?= number_format($p['total_pembelian'] ?? 0, 0, ',', '.') ?></td>

                                        <td class="text-center">
                                            <?php if ($p['status_utang'] == 'Belum Lunas') : ?>
                                                <a href="<?= base_url('pembelian/utang/' . $p['pemasok_id']) ?>" class="badge bg-warning text-dark text-decoration-none">
                                                    Belum Lunas
                                                </a>
                                            <?php else : ?>
                                                <span class="badge bg-success">Lunas</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <a href="<?= base_url('pemasok/edit/' . $p['pemasok_id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('pemasok/delete/' . $p['pemasok_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data pemasok ini? Aksi ini akan gagal jika pemasok memiliki riwayat pembelian.')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <!-- 3. UBAH COLSPAN JADI 14 -->
                                    <td colspan="14" class="text-center text-muted">Belum ada data pemasok</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================== MODAL TAMBAH ================== -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="<?= base_url('pemasok/save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-maroon text-white">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Data Pemasok</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Pemasok</label>
                            <input type="text" name="kode_pemasok" class="form-control <?= service('validation')->hasError('kode_pemasok') ? 'is-invalid' : '' ?>" placeholder="PM001" value="<?= old('kode_pemasok') ?>" required>
                            <?= validation_show_error('kode_pemasok') ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Pemasok</label>
                            <input type="text" name="nama_pemasok" class="form-control <?= service('validation')->hasError('nama_pemasok') ? 'is-invalid' : '' ?>" value="<?= old('nama_pemasok') ?>" required>
                            <?= validation_show_error('nama_pemasok') ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kontak Person</label>
                            <input type="text" name="kontak_person" class="form-control <?= service('validation')->hasError('kontak_person') ? 'is-invalid' : '' ?>" value="<?= old('kontak_person') ?>" required>
                            <?= validation_show_error('kontak_person') ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control <?= service('validation')->hasError('no_telp') ? 'is-invalid' : '' ?>" value="<?= old('no_telp') ?>" required>
                            <?= validation_show_error('no_telp') ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control <?= service('validation')->hasError('email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" required>
                            <?= validation_show_error('email') ?>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control <?= service('validation')->hasError('alamat') ? 'is-invalid' : '' ?>" rows="2" required><?= old('alamat') ?></textarea>
                            <?= validation_show_error('alamat') ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori Pemasok</label>
                            <select name="kategori" class="form-select <?= service('validation')->hasError('kategori') ? 'is-invalid' : '' ?>" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Bahan Baku" <?= old('kategori') == 'Bahan Baku' ? 'selected' : '' ?>>Bahan Baku</option>
                                <option value="Kemasan" <?= old('kategori') == 'Kemasan' ? 'selected' : '' ?>>Kemasan</option>
                                <option value="Peralatan" <?= old('kategori') == 'Peralatan' ? 'selected' : '' ?>>Peralatan</option>
                                <option value="Distributor" <?= old('kategori') == 'Distributor' ? 'selected' : '' ?>>Distributor</option>
                            </select>
                            <?= validation_show_error('kategori') ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select <?= service('validation')->hasError('status') ? 'is-invalid' : '' ?>" required>
                                <option value="Aktif" <?= old('status') == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="Nonaktif" <?= old('status') == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                            <?= validation_show_error('status') ?>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan pemasok..."><?= old('catatan') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- ========================================================== -->
<!-- SCRIPT DITAMBAHKAN DI SINI UNTUK MEMBUKA MODAL JIKA ADA ERROR -->
<!-- ========================================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session('validation') && !empty(session('validation')->getErrors())) : ?>
            // Cek apakah ada flashdata error 'sudah ada'
            var hasUniqueError = <?= session()->getFlashdata('error') ? 'true' : 'false' ?>;

            // Jika TIDAK ada flashdata error (artinya errornya 'wajib diisi')
            // maka buka modalnya
            if (!hasUniqueError) {
                var modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
                modalTambah.show();
            }
        <?php endif; ?>
    });
</script>