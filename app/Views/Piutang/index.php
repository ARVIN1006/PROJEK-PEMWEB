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
                        <h3 class="card-title">Tabel <?= $title ?></h3>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table id="tabel-piutang" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Total Harga</th>
                                        <th>Status Pesanan</th>
                                        <th>Status Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($penjualan as $p) : ?>
                                        <tr class="<?= ($p['status_pembayaran'] == 'Belum Lunas') ? 'table-warning' : '' ?>">
                                            <td><?= $no++ ?></td>
                                            <td><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                                            <td><?= esc($p['nama_pelanggan'] ?? 'Pelanggan Dihapus') ?></td>
                                            <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= esc(ucfirst($p['status'])) ?></span>
                                            </td>
                                            <td>
                                                <?php if ($p['status_pembayaran'] == 'Lunas') : ?>
                                                    <span class="badge bg-success">Lunas</span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($p['status_pembayaran'] == 'Lunas') : ?>
                                                    <a href="<?= base_url('piutang/update-status/' . $p['penjualan_id']) ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Ubah status menjadi Belum Lunas?')" title="Tandai Belum Lunas">
                                                        <i class="fas fa-times-circle"></i> Batal Lunas
                                                    </a>
                                                <?php else : ?>
                                                    <a href="<?= base_url('piutang/update-status/' . $p['penjualan_id']) ?>" class="btn btn-success btn-sm" onclick="return confirm('Ubah status menjadi Lunas?')" title="Tandai Lunas">
                                                        <i class="fas fa-check-circle"></i> Tandai Lunas
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<?= $this->endSection() ?>