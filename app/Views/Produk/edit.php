<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <style>
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
        <h2>Form Edit Produk (ID: <?= $produk['produk_id'] ?>)</h2>
        <p>Gunakan form ini untuk memperbarui data produk.</p>

        <?php if (session()->getFlashdata('validation_errors')): ?>
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                <ul>
                    <?php foreach (session()->getFlashdata('validation_errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/produk/update/<?= $produk['produk_id'] ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <label for="nama_produk">Nama Produk:</label>
            <input type="text" id="nama_produk" name="nama_produk" required
                value="<?= old('nama_produk', $produk['nama_produk']) ?>">

            <label for="kategori_id">Kategori:</label>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php $selected = old('kategori_id', $produk['kategori_id']); ?>
                <option value="1" <?= $selected == '1' ? 'selected' : '' ?>>1. Dimsum Ayam (Test)</option>
                <option value="2" <?= $selected == '2' ? 'selected' : '' ?>>2. Dimsum Udang (Test)</option>
            </select>

            <label for="harga">Harga (Rp):</label>
            <input type="text" id="harga" name="harga" required
                value="<?= old('harga', $produk['harga']) ?>" placeholder="Contoh: 120000">

            <label for="stok">Stok Awal:</label>
            <input type="number" id="stok" name="stok" required
                value="<?= old('stok', $produk['stok']) ?>">

            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi"><?= old('deskripsi', $produk['deskripsi']) ?></textarea>

            <button type="submit">UPDATE DATA PRODUK</button>
            <a href="/produk" style="margin-left: 10px; color: #333; text-decoration: none;">Batal</a>
        </form>
    </div>
</body>

</html>