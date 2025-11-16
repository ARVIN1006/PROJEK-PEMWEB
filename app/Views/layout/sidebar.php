<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="#index.html" class="brand-link">
            <img
                src="<?= base_url('adminlte/dist/assets/') ?>img/Logo.png"
                alt="Dapoyumya Logo"
                class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">DAPOYUMYA</span>
        </a>
    </div>
    <div class="sidebar-wrapper sidebar-scroll">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                aria-label="Main navigation"
                data-accordion="false"
                id="navigation">

                <li class="nav-header">MASTER DATA</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>
                            Data
                            <i class="bi bi-chevron-right nav-arrow"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('produk') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pemasok') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Pemasok</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('bahanbaku') ?>" class="nav-link">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>Bahan Baku</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('aset') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Aset</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pelanggan') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Pelanggan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pegawai') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Pegawai</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">FORM INPUT</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Transaksi
                            <i class="bi bi-chevron-right nav-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('penjualan/tambah') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pembelian') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Pembelian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('pengeluaran') ?>" class="nav-link">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Pengeluaran</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php
                // Hanya tampilkan blok ini jika peran di session adalah 'owner'
                if (session()->get('role') === 'owner'):
                ?>

                    <li class="nav-header">LAPORAN</li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-clipboard-fill"></i>
                            <p>
                                Laporan
                                <i class="bi bi-chevron-right nav-arrow"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/labaRugi') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Laba/Rugi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/posisiKeuangan') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Posisi Keuangan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('piutang') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Piutang</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/utang') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Utang</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/penjualan') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Penjualan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/pembelian') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Pembelian</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/jurnal') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Jurnal Umum</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/bukuBesar') ?>" class="nav-link">
                                    <i class="bi bi-circle nav-icon"></i>
                                    <p>Buku Besar</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php
                endif; // Akhir dari pengecekan 'owner'
                ?>
                <li class="nav-item logout-item">
                    <a href="<?= base_url('logout') ?>" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-in-right"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>