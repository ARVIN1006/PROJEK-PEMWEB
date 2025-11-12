<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        /* CSS DARI VIEW ASLI ANDA */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        /* UBAH WARNA JUDUL AGAR NETRAL (BUKAN HIJAU SPESIFIK) */
        h2 {
            color: #333;
            /* Warna abu-abu gelap netral */
        }

        .pesan-sukses {
            background-color: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        /* Tambahkan CSS untuk pesan gagal (jika muncul) */
        .pesan-sukses:contains("GAGAL!") {
            background-color: #F8D7DA;
            /* Merah muda untuk gagal */
            color: #721C24;
            border-color: #F5C6CB;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* CSS untuk tombol aksi */
        .aksi-btn {
            padding: 4px 8px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 0.9em;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #FFC107;
            color: #333;
        }

        .delete-btn {
            background-color: #DC3545;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Produk Dimsum UMKM</h2>
        <?php if (session()->getFlashdata('pesan')): ?>
            <div class="pesan-sukses">
                <?= session()->getFlashdata('pesan') ?>
            </div>
        <?php endif; ?>

        <a href="/produk/tambah" style="padding: 10px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;">+ Tambah Produk Baru</a>

        <h3>Data Stok dan Harga</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kategori ID</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($produk as $p): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $p['nama_produk'] ?></td>
                        <td>Rp<?= number_format($p['harga'], 0, ',', '.') ?></td>
                        <td><?= $p['stok'] ?></td>
                        <td><?= $p['kategori_id'] ?></td>
                        <td>
                            <a href="/produk/edit/<?= $p['produk_id'] ?>" class="aksi-btn edit-btn">Edit</a>

                            <form action="/produk/<?= $p['produk_id'] ?>" method="post" style="display: inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" onclick="return confirm('Apakah yakin menghapus produk <?= $p['nama_produk'] ?>?')" class="aksi-btn delete-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>