<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        /* CSS Sederhana untuk Kejelasan */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Form Test: Tambah Produk Dimsum</h2>
        <p>Pengujian fungsi CREATE ke tabel `produk`.</p>

        <?php if (session()->getFlashdata('validation_errors')): ?>
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                <ul>
                    <?php foreach (session()->getFlashdata('validation_errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/produk/save" method="post">
            <?= csrf_field() ?> <label for="nama_produk">Nama Produk:</label>
            <input type="text" id="nama_produk" name="nama_produk" required
                value="<?= old('nama_produk') ?>">

            <label for="kategori_id">Kategori:</label>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="1" <?= old('kategori_id') == '1' ? 'selected' : '' ?>>1. Dimsum Ayam (Test)</option>
                <option value="2" <?= old('kategori_id') == '2' ? 'selected' : '' ?>>2. Dimsum Udang (Test)</option>
            </select>

            <label for="harga">Harga (Rp):</label>
            <input type="text" id="harga" name="harga" step="0.01" required
                value="<?= old('harga') ?>"> placeholder="Contoh: 120000 ">

            <label for="stok">Stok Awal:</label>
            <input type="number" id="stok" name="stok" required
                value="<?= old('stok') ?>">

            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi"><?= old('deskripsi') ?></textarea>

            <button type="submit">SIMPAN DATA PRODUK (Test Database)</button>
        </form>
    </div>
</body>

</html>