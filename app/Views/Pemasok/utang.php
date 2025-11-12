<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <!-- 1. Judul Halaman Diperbaiki -->
                <h3 class="mb-0">Detail Utang Pembelian</h3>
            </div>
            <div class="col-sm-6">
                <!-- 2. Breadcrumb ini sudah benar dan rapi -->
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="nav-link">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('pemasok') ?>" class="nav-link">Data Pemasok</a></li>
                    <li class="breadcrumb-item active">Detail Utang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Konten Utama -->
<div class="dashboard-container">

    <!-- Tombol Kembali -->
    <div class="row mb-3">
        <div class="col">
            <a href="<?= base_url('pemasok') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pemasok
            </a>
        </div>
    </div>

    <!-- Tabel Data Pembelian Belum Lunas -->
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- 3. WARNA HEADER DIUBAH DI SINI -->
                <!-- Kelas 'bg-primary' dihapus dan diganti dengan style inline -->
                <div class="card-header text-white" style="background-color: #740000;">
                    <h3 class="card-title">Daftar Utang ke: <?= esc($pemasok['nama_pemasok']) ?></h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Pembelian</th>
                                <th>Total Barang</th>
                                <th>Total Harga</th>
                                <th>Status Pembayaran</th>
                                <th>Status Pembelian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pembelian)): ?>
                                <?php $no = 1;
                                foreach ($pembelian as $b): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($b['tanggal_pembelian']) ?></td>
                                        <td><?= esc($b['kode_pembelian']) ?></td>
                                        <td class="text-center"><?= esc($b['total_barang']) ?></td>
                                        <td class="text-end"><?= number_format($b['total_harga'], 0, ',', '.') ?></td>

                                        <td class="text-center">
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        </td>
                                        <td class="text-center">
                                            <?php if (esc($b['status_pembelian']) == 'Selesai') : ?>
                                                <span class="badge bg-primary">Selesai</span>
                                            <?php else : ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data utang untuk pemasok ini</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>