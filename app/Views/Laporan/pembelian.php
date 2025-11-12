<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pembelian dan Pemasok</title>
    <style>
        /* CSS untuk tampilan di layar (CSS SAMA DENGAN SEBELUMNYA) */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #777;
        }

        h2 {
            font-size: 20px;
            color: #34495e;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }

        table th {
            background-color: #f9f9f9;
            font-weight: 600;
        }

        table tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .text-right {
            text-align: right;
        }

        /* Style untuk penempatan tombol */
        .button-group {
            display: flex;
            justify-content: flex-end;
            /* Posisikan tombol ke kanan */
            gap: 10px;
            /* Jarak antar tombol */
            margin-bottom: 20px;
        }

        .action-button {
            display: inline-block;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        .print-button {
            background-color: #3498db;
            color: #fff;
        }

        .print-button:hover {
            background-color: #2980b9;
        }

        .back-button {
            background-color: #95a5a6;
            color: #fff;
        }

        .back-button:hover {
            background-color: #7f8c8d;
        }

        /* CSS untuk mode cetak (print) */
        @media print {
            body {
                background-color: #fff;
                margin: 0;
                padding: 0;
            }

            .container {
                box-shadow: none;
                margin: 0;
                width: 100%;
                max-width: 100%;
                padding: 10px;
                border-radius: 0;
            }

            .button-group {
                display: none;
                /* Sembunyikan tombol saat dicetak */
            }

            h2 {
                margin-top: 20px;
            }

            table {
                font-size: 12px;
            }

            table th,
            table td {
                padding: 6px;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Grup Tombol Kembalil dan Cetak -->
        <div class="button-group">
            <!-- TOMBOL KEMBALI -->
            <button class="action-button back-button" onclick="history.back()">
                &larr; Kembali
            </button>

            <button class="action-button print-button" onclick="window.print()">
                Cetak
            </button>
        </div>

        <div class="header">
            <h1>Laporan Perusahaan "DAPOYUMYA"</h1>
            <p>Dicetak pada: <?php echo date('d F Y H:i:s'); ?></p>
        </div>

        <!-- Bagian Data Pemasok -->
        <h2>Laporan Data Pemasok</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Pemasok</th>
                    <th>Kontak Person</th>
                    <th>No. Telepon</th>
                    <th>Total Pembelian</th>
                    <th>Status Utang</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (!empty($pemasok)):
                    foreach ($pemasok as $row):
                ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['kode_pemasok'] ?? 'N/A'; ?></td>
                            <td><?php echo $row['nama_pemasok'] ?? 'N/A'; ?></td>
                            <td><?php echo $row['kontak_person'] ?? 'N/A'; ?></td>
                            <td><?php echo $row['no_telepon'] ?? 'N/A'; ?></td>
                            <td class="text-right">Rp <?php echo number_format($row['total_pembelian'] ?? 0, 0, ',', '.'); ?></td>
                            <td><?php echo $row['status_utang'] ?? 'N/A'; ?></td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data pemasok yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Bagian Data Pembelian -->
        <h2>Laporan Data Pembelian</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode Pembelian</th>
                    <th>Pemasok</th>
                    <th>Total Harga</th>
                    <th>Status Pembayaran</th>
                    <th>Status Pembelian</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_beli = 1;
                if (!empty($pembelian)):
                    foreach ($pembelian as $beli):
                ?>
                        <tr>
                            <td><?php echo $no_beli++; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($beli['tanggal'] ?? 'now')); ?></td>
                            <td><?php echo $beli['kode_pembelian'] ?? 'N/A'; ?></td>
                            <td><?php echo $beli['nama_pemasok'] ?? 'ID Pemasok: ' . ($beli['pemasok_id'] ?? 'N/A'); ?></td>
                            <td class="text-right">Rp <?php echo number_format($beli['total_harga'] ?? 0, 0, ',', '.'); ?></td>
                            <td><?php echo $beli['status_pembayaran'] ?? 'N/A'; ?></td>
                            <td><?php echo $beli['status_pembelian'] ?? 'N/A'; ?></td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data pembelian yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- BAGIAN LAPORAN DATA BAHAN BAKU -->
        <h2>Laporan Data Bahan Baku</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bahan Baku</th>
                    <th>Kategori</th>
                    <th>Satuan Umum</th>
                    <th class="text-right">Harga Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_bb = 1;
                if (!empty($bahan_baku)):
                    foreach ($bahan_baku as $bb):
                ?>
                        <tr>
                            <td><?php echo $no_bb++; ?></td>
                            <!-- PERHATIKAN BARIS INI: Gunakan nama field yang benar di database Anda -->
                            <td><?php echo $bb['nama_bahan_baku'] ?? $bb['nama'] ?? 'N/A'; ?></td>
                            <td><?php echo $bb['kategori'] ?? 'N/A'; ?></td>
                            <td><?php echo $bb['satuan_umum'] ?? $bb['satuan'] ?? 'N/A'; ?></td>
                            <!-- PERHATIKAN BARIS INI: Nama field harga mungkin berbeda -->
                            <td class="text-right">
                                Rp <?php echo number_format($bb['harga'] ?? $bb['harga_satuan'] ?? 0, 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data bahan baku yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- AKHIR BAGIAN BAHAN BAKU -->

    </div>

</body>

</html>