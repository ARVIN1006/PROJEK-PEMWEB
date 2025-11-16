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
                    <li class="breadcrumb-item"><a href="<?= base_url('pelanggan') ?>">Pelanggan</a></li>
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

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success" role="alert">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php $validation = session()->get('validation'); ?>

                        <form action="<?= base_url('pelanggan/update/' . $pelanggan['pelanggan_id']) ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="nama_pelanggan" class="form-label">Nama Pelanggan <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control <?= $validation?->hasError('nama_pelanggan') ? 'is-invalid' : '' ?>"
                                    id="nama_pelanggan" name="nama_pelanggan"
                                    value="<?= old('nama_pelanggan', $pelanggan['nama_pelanggan']) ?>" required>

                                <?php if ($validation?->hasError('nama_pelanggan')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('nama_pelanggan') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control <?= $validation?->hasError('username') ? 'is-invalid' : '' ?>"
                                    id="username" name="username" value="<?= old('username', $pelanggan['username']) ?>"
                                    required>

                                <?php if ($validation?->hasError('username')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('username') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control <?= $validation?->hasError('email') ? 'is-invalid' : '' ?>"
                                    id="email" name="email" value="<?= old('email', $pelanggan['email']) ?>" required>

                                <?php if ($validation?->hasError('email')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password"
                                    class="form-control <?= $validation?->hasError('password') ? 'is-invalid' : '' ?>"
                                    id="password" name="password">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>

                                <?php if ($validation?->hasError('password')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('password') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP</label>
                                <input type="tel"
                                    class="form-control <?= $validation?->hasError('no_hp') ? 'is-invalid' : '' ?>"
                                    id="no_hp" name="no_hp" value="<?= old('no_hp', $pelanggan['no_hp']) ?>">

                                <?php if ($validation?->hasError('no_hp')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('no_hp') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea
                                    class="form-control <?= $validation?->hasError('alamat') ? 'is-invalid' : '' ?>"
                                    id="alamat" name="alamat"
                                    rows="3"><?= old('alamat', $pelanggan['alamat']) ?></textarea>

                                <?php if ($validation?->hasError('alamat')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('alamat') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <a href="<?= base_url('pelanggan') ?>" class="btn btn-secondary me-2">Batal</a>
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