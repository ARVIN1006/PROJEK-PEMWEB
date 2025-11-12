<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<?php helper('form'); ?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Detail Pembelian</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="nav-link">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('pembelian') ?>" class="nav-link">Data Pembelian</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">

    <!-- Tombol Kembali -->
    <div class="row mb-3">
        <div class="col">
            <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pembelian
            </a>
            <!-- TODO: Buat Halaman Edit -->
            <a href="#" class="btn btn-warning disabled">
                <i class="fas fa-edit"></i> Edit Pembelian (Belum Aktif)
            </a>
        </div>
    </div>

    <!-- Kartu Info Induk -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Informasi Pembelian: <?= esc($pembelian['kode_pembelian']) ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Pemasok:</strong><br>
                    <?= esc($pembelian['nama_pemasok']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Tanggal:</strong><br>
                    <?= esc($pembelian['tanggal_pembelian']) ?>
                </div>
                <div class="col-md-4">
                    <strong>Total Harga:</strong><br>
                    <h4 class="text-success"><?= number_format($pembelian['total_harga'], 0, ',', '.') ?></h4>
                </div>
                <div class="col-md-4 mt-3">
                    <strong>Status Pembayaran:</strong><br>
                    <?php if (esc($pembelian['status_pembayaran']) == 'Lunas') : ?>
                        <span class="badge bg-success fs-6">Lunas</span>
                    <?php else : ?>
                        <span class="badge bg-danger fs-6">Belum Lunas</span>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 mt-3">
                    <strong>Status Pembelian:</strong><br>
                    <?php if (esc($pembelian['status_pembelian']) == 'Selesai') : ?>
                        <span class="badge bg-primary fs-6">Selesai</span>
                    <?php else : ?>
                        <span class="badge bg-warning fs-6">Pending</span>
                    <?php endif; ?>
                </div>
                <div class="col-md-12 mt-3">
                    <strong>Catatan:</strong><br>
                    <?= esc($pembelian['catatan'] ?? '-') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Detail Item -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-maroon text-white">
                    <h3 class="card-title">Daftar Item yang Dipesan</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan</th>
                                <th>Satuan</th>
                                <th>Jml Pesan</th>
                                <th>Jml Diterima</th>
                                <th>Kurang</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- ================== PERBAIKAN DI SINI ================== -->
                            <!-- Variabel-variabel ini dipindahkan ke luar 'if' -->
                            <?php
                            $no = 1;
                            $total_pesan = 0;
                            $total_diterima = 0;
                            $total_kurang = 0;
                            ?>
                            <!-- ================== /PERBAIKAN ================== -->

                            <?php if (!empty($detail_items)): ?>
                                <?php
                                // Inisialisasi 'no' tetap di sini, tapi total sudah di luar
                                $no = 1;
                                foreach ($detail_items as $item):
                                    $kurang = (float)$item['jumlah_pesan'] - (float)$item['jumlah_diterima'];
                                    $total_pesan += (float)$item['jumlah_pesan'];
                                    $total_diterima += (float)$item['jumlah_diterima'];
                                    $total_kurang += $kurang;
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($item['nama_bahan']) ?></td>
                                        <td class="text-center"><?= esc($item['satuan']) ?></td>
                                        <td class="text-center"><?= (float)$item['jumlah_pesan'] ?></td>
                                        <td class="text-center"><?= (float)$item['jumlah_diterima'] ?></td>
                                        <td class="text-center">
                                            <?php if ($kurang > 0): ?>
                                                <span class="badge bg-danger"><?= $kurang ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end"><?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                                        <td class="text-end"><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada item detail untuk pembelian ini.</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                        <tfoot class="fw-bold">
                            <tr class="text-center">
                                <td colspan="3" class="text-end">Total Keseluruhan Item:</td>
                                <!-- Variabel ini sekarang dijamin ada (minimal 0) -->
                                <td><?= $total_pesan ?></td>
                                <td><?= $total_diterima ?></td>
                                <td>
                                    <?php if ($total_kurang > 0): ?>
                                        <span class="badge bg-danger fs-6"><?= $total_kurang ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success fs-6">0</span>
                                    <?php endif; ?>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>