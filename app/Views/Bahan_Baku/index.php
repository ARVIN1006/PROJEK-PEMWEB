<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<?php helper('form'); ?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Data Bahan Baku</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="nav-link">Home</a></li>
                    <li class="breadcrumb-item active">Bahan Baku</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">

    <!-- Tombol Tambah -->
    <div class="row mb-3">
        <div class="col">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Bahan Baku
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

    <!-- Tabel Data -->
    <div class="card">
        <div class="card-header bg-maroon text-white">
            <h3 class="card-title">Daftar Bahan Baku</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Bahan Baku</th>
                        <th>Kategori</th>
                        <th>Satuan Umum</th>
                        <th class="text-end">Harga Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bahan_baku)): ?>
                        <?php $no = 1;
                        foreach ($bahan_baku as $item): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($item['nama_bahan']) ?></td>
                                <td><?= esc($item['kategori']) ?></td>
                                <td><?= esc($item['satuan']) ?></td>
                                <td class="text-end">Rp <?= number_format($item['harga_satuan'] ?? 0, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit-<?= $item['bahan_id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= base_url('bahanbaku/delete/' . $item['bahan_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                        </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data bahan baku.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('bahanbaku/save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Bahan Baku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <input type="text" name="nama_bahan" class="form-control <?= service('validation')->hasError('nama_bahan') ? 'is-invalid' : '' ?>" value="<?= old('nama_bahan') ?>" required>
                        <?= validation_show_error('nama_bahan', 'custom_validation_error') ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" value="<?= old('kategori') ?>" placeholder="Contoh: Bahan Baku Makanan, Kemasan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control <?= service('validation')->hasError('satuan') ? 'is-invalid' : '' ?>" value="<?= old('satuan') ?>" placeholder="Contoh: pcs, kg, box, liter" required>
                        <?= validation_show_error('satuan', 'custom_validation_error') ?>
                    </div>
                    <!-- FIELD INPUT HARGA BARU -->
                    <div class="mb-3">
                        <label class="form-label">Harga Satuan</label>
                        <input type="number" step="0.01" min="0" name="harga_satuan" class="form-control <?= service('validation')->hasError('harga_satuan') ? 'is-invalid' : '' ?>" value="<?= old('harga_satuan') ?>" placeholder="Contoh: 50000" required>
                        <div class="invalid-feedback">
                            <?= validation_show_error('harga_satuan', 'custom_validation_error') ?>
                        </div>
                    </div>
                    <!-- AKHIR FIELD HARGA -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit (Loop) -->
<?php if (!empty($bahan_baku)): foreach ($bahan_baku as $item): ?>
        <div class="modal fade" id="modalEdit-<?= $item['bahan_id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="<?= base_url('bahanbaku/update/' . $item['bahan_id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">Edit Bahan Baku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Bahan</label>
                                <input type="text" name="nama_bahan" class="form-control" value="<?= old('nama_bahan', $item['nama_bahan']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control" value="<?= old('kategori', $item['kategori']) ?>" placeholder="Contoh: Bahan Baku Makanan, Kemasan">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control" value="<?= old('satuan', $item['satuan']) ?>" placeholder="Contoh: pcs, kg, box, liter" required>
                            </div>
                            <!-- FIELD INPUT HARGA DI EDIT MODAL -->
                            <div class="mb-3">
                                <label class="form-label">Harga Satuan</label>
                                <input type="number" step="0.01" min="0" name="harga_satuan" class="form-control" value="<?= old('harga_satuan', $item['harga_satuan'] ?? 0) ?>" placeholder="Contoh: 50000" required>
                            </div>
                            <!-- AKHIR FIELD HARGA -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php endforeach;
endif; ?>

<?= $this->endSection() ?>

<!-- Script untuk membuka modal jika ada error validasi (wajib diisi) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // PERBAIKAN: Gunakan fungsi session() dan json_encode untuk menghindari error $this
        var validationErrors = <?php echo json_encode(session('validation') ? session('validation')->getErrors() : []); ?>;
        var hasFlashError = <?php echo json_encode(session()->getFlashdata('error') ? true : false); ?>;

        if (Object.keys(validationErrors).length > 0) {
            // Jika TIDAK ada flashdata error (artinya errornya 'wajib diisi', bukan error delete/unique)
            if (!hasFlashError) {
                // Buka modal Tambah secara otomatis jika ada error validasi
                var modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
                modalTambah.show();
            }
        }
    });
</script>