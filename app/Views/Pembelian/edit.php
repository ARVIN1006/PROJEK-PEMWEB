<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Edit Pembelian</h3>
    <form action="<?= base_url('pembelian/update/' . $pembelian['pembelian_id']) ?>" method="post">
        <div class="row g-3">
            <div class="col-md-6">
                <label>Kode Pembelian</label>
                <input type="text" name="kode_pembelian" class="form-control"
                    value="<?= esc($pembelian['kode_pembelian']) ?>" required>
            </div>
            <div class="col-md-6">
                <label>Tanggal</label>
                <input type="date" name="tanggal_pembelian" class="form-control"
                    value="<?= esc($pembelian['tanggal_pembelian']) ?>" required>
            </div>

            <div class="col-md-12">
                <label>Pemasok</label>
                <select name="pemasok_id" class="form-select" required>
                    <?php foreach ($pemasok as $p): ?>
                        <option value="<?= $p['pemasok_id'] ?>"
                            <?= $p['pemasok_id'] == $pembelian['pemasok_id'] ? 'selected' : '' ?>>
                            <?= esc($p['nama_pemasok']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-6">
                <label>Total Barang</label>
                <input type="number" name="total_barang" class="form-control"
                    value="<?= esc($pembelian['total_barang']) ?>" required>
            </div>

            <div class="col-md-6">
                <label>Total Harga</label>
                <input type="number" name="total_harga" class="form-control"
                    value="<?= esc($pembelian['total_harga']) ?>" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Status Pembayaran</label>
                <select name="status_pembayaran" class="form-select" required>
                    <option value="Belum Lunas" <?= ($pembelian['status_pembayaran'] == 'Belum Lunas') ? 'selected' : '' ?>>Belum Lunas</option>
                    <option value="Lunas" <?= ($pembelian['status_pembayaran'] == 'Lunas') ? 'selected' : '' ?>>Lunas</option>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Status Pembelian</label>
                <select name="status_pembelian" class="form-select" required>
                    <option value="Pending" <?= ($pembelian['status_pembelian'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="Selesai" <?= ($pembelian['status_pembelian'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>

            <div class="col-md-12">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"><?= esc($pembelian['catatan']) ?></textarea>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>