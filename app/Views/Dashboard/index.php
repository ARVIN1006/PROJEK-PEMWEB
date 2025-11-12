<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Welcome to Dapoyumya</h3>
            </div>

        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<!--begin::App Content-->
<!-- WRAPPER BOX LUAR -->
<div class="dashboard-container card shadow-sm p-4">

    <h5 class="mb-3 fw-bold">DASHBOARD</h5>

    <div class="row g-3">
        <!-- Penjualan -->
        <div class="col-lg-4 col-md-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>150</h3>
                    <p>Penjualan</p>
                </div>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="col-lg-4 col-md-6">
            <div class="small-box text-bg-light">
                <div class="inner">
                    <h3>53%</h3>
                    <p>Pengeluaran</p>
                </div>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="col-lg-4 col-md-6">
            <div class="small-box text-bg-light">
                <div class="inner">
                    <h3>44</h3>
                    <p>Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Stok -->
        <div class="col-lg-6 col-md-6">
            <div class="small-box text-bg-light">
                <div class="inner">
                    <h3>65</h3>
                    <p>Stok</p>
                </div>
            </div>
        </div>

        <!-- Laba -->
        <div class="col-lg-6 col-md-6">
            <div class="small-box text-bg-light">
                <div class="inner">
                    <h3>65</h3>
                    <p>Laba</p>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <!-- grafik -->
        </div>
    </div>
</div>

<?= $this->endSection() ?>