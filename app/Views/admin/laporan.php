<?= $this->extend('layouts/metronic') ?>

<?= $this->section('header') ?>
<?= view('partials/header_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<div class="app-toolbar py-3 py-lg-6" id="kt_app_toolbar">
    <div class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center me-10">Laporan Bulanan</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="/" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-5px"></span></li>
                <li class="breadcrumb-item text-gray-500">Admin</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-5px"></span></li>
                <li class="breadcrumb-item text-gray-500">Laporan</li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?php
$bulanList = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<?= $this->section('content') ?>
<div class="container-xxl mt-5" id="kt_content_container" data-url-chart="<?= base_url('admin/chart?tahun=' . $tahun . '&bulan=' . $bulan) ?>">

    <!-- Filter -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h2 class="mb-0">Filter Laporan</h2>
                    </div>
                    <div class="card-toolbar gap-2">
                        <a href="<?= base_url('admin/laporan/export/excel?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-success">
                            <i class="ki-duotone ki-tablet-text-up fs-3"><span class="path1"></span><span class="path2"></span></i>
                            Export Excel
                        </a>
                        <a href="<?= base_url('admin/laporan/export/pdf?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-danger">
                            <i class="ki-duotone ki-document fs-3"><span class="path1"></span><span class="path2"></span></i>
                            Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="get" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="bulan" class="form-label fw-semibold">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select form-select-solid">
                                <?php foreach ($bulanList as $key => $nama): ?>
                                <option value="<?= $key ?>" <?= $bulan == $key ? 'selected' : '' ?>><?= $nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tahun" class="form-label fw-semibold">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select form-select-solid">
                                <?php for ($t = date('Y'); $t >= 2021; $t--): ?>
                                <option value="<?= $t ?>" <?= $tahun == $t ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ki-duotone ki-filter fs-3"><span class="path1"></span><span class="path2"></span></i>
                                Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-4">
            <div class="card card-flush">
                <div class="card-body text-center py-8">
                    <div class="text-muted fw-semibold fs-7 mb-2">Total Kunjungan Periode</div>
                    <div class="fw-bolder text-primary fs-2x lh-1" id="summaryTotal">-</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card-flush">
                <div class="card-body text-center py-8">
                    <div class="text-muted fw-semibold fs-7 mb-2">Rata-rata Per Hari</div>
                    <div class="fw-bolder text-success fs-2x lh-1" id="summaryRata">-</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card-flush">
                <div class="card-body text-center py-8">
                    <div class="text-muted fw-semibold fs-7 mb-2">Puncak Kunjungan</div>
                    <div class="fw-bolder text-warning fs-2x lh-1" id="summaryPuncak">-</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-8">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5">
                    <div class="card-title">
                        <h2 class="mb-0">Grafik Kunjungan Tahun <?= esc($tahun) ?></h2>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chartKunjungan" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5">
                    <div class="card-title">
                        <h2 class="mb-0">Distribusi Tahun <?= esc($tahun) ?></h2>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chartDistribusi" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="row g-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h2 class="mb-0">Data <?= $bulanList[$bulan] ?> <?= $tahun ?></h2>
                    </div>
                    <div class="card-toolbar">
                        <span class="badge py-3 px-4 fs-7 badge-light-primary"><?= count($dataLaporan) ?> data</span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_laporan">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px">#</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Nama</th>
                                <th>Instansi / Alamat</th>
                                <th>Tujuan</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php if (empty($dataLaporan)): ?>
                            <tr><td colspan="6" class="text-center py-10 text-muted">Tidak ada data untuk periode ini</td></tr>
                            <?php else: ?>
                            <?php $no = 1; foreach ($dataLaporan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <span class="text-gray-800 fw-semibold"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span>
                                    <span class="text-muted d-block fs-7"><?= date('H:i', strtotime($row['tanggal'])) ?></span>
                                </td>
                                <td>
                                    <?php if ($row['jenis_tamu'] === 'pengunjung'): ?>
                                    <span class="badge badge-light-primary">Pengunjung</span>
                                    <?php else: ?>
                                    <span class="badge badge-light-success">Tamu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-gray-800 fw-semibold"><?= esc($row['nama']) ?></td>
                                <td><?= esc($row['jenis_tamu'] === 'tamu' ? ($row['instansi'] ?? '-') : ($row['alamat'] ?? '-')) ?></td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="<?= esc($row['tujuan']) ?>">
                                        <?= esc($row['tujuan'] ?? '-') ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Scripts moved to admin_laporan.js -->
