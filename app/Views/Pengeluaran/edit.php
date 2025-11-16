<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><?= $title ?></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('pengeluaran') ?>">Pengeluaran</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $title ?>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form <?= $title ?></h3>
                    </div>
                    <div class="card-body">
                        <?php $validation = session()->get('validation'); ?>
                        
                        <form action="<?= base_url('pengeluaran/update/' . $pengeluaran['pengeluaran_id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="POST"> <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?= $validation?->hasError('tanggal') ? 'is-invalid' : '' ?>" id="tanggal" name="tanggal" value="<?= old('tanggal', $pengeluaran['tanggal']) ?>" required>
                                <?php if ($validation?->hasError('tanggal')) : ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('tanggal') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="kategori_pengeluaran" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= $validation?->hasError('kategori_pengeluaran') ? 'is-invalid' : '' ?>" id="kategori_pengeluaran" name="kategori_pengeluaran" value="<?= old('kategori_pengeluaran', $pengeluaran['kategori_pengeluaran']) ?>" placeholder="Contoh: Listrik, Air, Gaji, dll." required>
                                <?php if ($validation?->hasError('kategori_pengeluaran')) : ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('kategori_pengeluaran') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                             <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control <?= $validation?->hasError('jumlah') ? 'is-invalid' : '' ?>" id="jumlah" name="jumlah" value="<?= old('jumlah', $pengeluaran['jumlah']) ?>" required>
                                <?php if ($validation?->hasError('jumlah')) : ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('jumlah') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control <?= $validation?->hasError('keterangan') ? 'is-invalid' : '' ?>" id="keterangan" name="keterangan" rows="3"><?= old('keterangan', $pengeluaran['keterangan']) ?></textarea>
                                <?php if ($validation?->hasError('keterangan')) : ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('keterangan') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <a href="<?= base_url('pengeluaran') ?>" class="btn btn-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>