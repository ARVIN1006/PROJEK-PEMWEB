<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<?php helper('form'); ?>

<!-- Header Halaman -->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Data Pemasok</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="nav-link">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('pemasok') ?>" class="nav-link">Data Pemasok</a></li>
                    <li class="breadcrumb-item active">Edit Data</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Konten Utama -->
<div class="dashboard-container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title">Mengedit: <?= esc($pemasok['nama_pemasok']) ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('pemasok/update/' . $pemasok['pemasok_id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row g-3">

                            <!-- Baris 1: Kode & Nama -->
                            <div class="col-md-6">
                                <label class="form-label">Kode Pemasok</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <input type="text" name="kode_pemasok" class="form-control <?= service('validation')->hasError('kode_pemasok') ? 'is-invalid' : '' ?>" value="<?= old('kode_pemasok', $pemasok['kode_pemasok']) ?>" required>
                                <?= validation_show_error('kode_pemasok') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Pemasok</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <input type="text" name="nama_pemasok" class="form-control <?= service('validation')->hasError('nama_pemasok') ? 'is-invalid' : '' ?>" value="<?= old('nama_pemasok', $pemasok['nama_pemasok']) ?>" required>
                                <?= validation_show_error('nama_pemasok') ?>
                            </div>

                            <!-- Baris 2: Kontak & Telepon -->
                            <div class="col-md-6">
                                <label class="form-label">Kontak Person</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <input type="text" name="kontak_person" class="form-control <?= service('validation')->hasError('kontak_person') ? 'is-invalid' : '' ?>" value="<?= old('kontak_person', $pemasok['kontak_person']) ?>">
                                <?= validation_show_error('kontak_person') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <input type="text" name="no_telp" class="form-control <?= service('validation')->hasError('no_telp') ? 'is-invalid' : '' ?>" value="<?= old('no_telp', $pemasok['no_telp']) ?>" required>
                                <?= validation_show_error('no_telp') ?>
                            </div>

                            <!-- Baris 3: Email & Alamat -->
                            <div class="col-md-12">
                                <label class="form-label">Email</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <input type="email" name="email" class="form-control <?= service('validation')->hasError('email') ? 'is-invalid' : '' ?>" value="<?= old('email', $pemasok['email']) ?>">
                                <?= validation_show_error('email') ?>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Alamat</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <textarea name="alamat" class="form-control <?= service('validation')->hasError('alamat') ? 'is-invalid' : '' ?>" rows="2" required><?= old('alamat', $pemasok['alamat']) ?></textarea>
                                <?= validation_show_error('alamat') ?>
                            </div>

                            <!-- Baris 4: Kategori & Status -->
                            <div class="col-md-6">
                                <label class="form-label">Kategori Pemasok</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <select name="kategori" class="form-select <?= service('validation')->hasError('kategori') ? 'is-invalid' : '' ?>" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Bahan Baku" <?= old('kategori', $pemasok['kategori']) == 'Bahan Baku' ? 'selected' : '' ?>>Bahan Baku</option>
                                    <option value="Kemasan" <?= old('kategori', $pemasok['kategori']) == 'Kemasan' ? 'selected' : '' ?>>Kemasan</option>
                                    <option value="Peralatan" <?= old('kategori', $pemasok['kategori']) == 'Peralatan' ? 'selected' : '' ?>>Peralatan</option>
                                    <option value="Distributor" <?= old('kategori', $pemasok['kategori']) == 'Distributor' ? 'selected' : '' ?>>Distributor</option>
                                </select>
                                <?= validation_show_error('kategori') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <!-- PERBAIKAN CLASS: Menggunakan service('validation')->hasError() -->
                                <select name="status" class="form-select <?= service('validation')->hasError('status') ? 'is-invalid' : '' ?>" required>
                                    <option value="Aktif" <?= old('status', $pemasok['status']) == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="Nonaktif" <?= old('status', $pemasok['status']) == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                                <?= validation_show_error('status') ?>
                            </div>

                            <!-- Baris 5: Catatan & Total (Read-Only) -->
                            <div class="col-md-6">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan pemasok..."><?= old('catatan', $pemasok['catatan']) ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Pembelian (Read-Only)</label>
                                <input type="text" class="form-control" value="Rp <?= number_format($pemasok['total_pembelian'] ?? 0, 0, ',', '.') ?>" readonly>
                                <div class="form-text">Total pembelian di-update otomatis dari Data Pembelian.</div>
                            </div>

                            <!-- Tombol -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success">Update Data</button>
                                <a href="<?= base_url('pemasok') ?>" class="btn btn-secondary">Kembali</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>