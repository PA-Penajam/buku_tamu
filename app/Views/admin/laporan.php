<?= $this->extend('layouts/metronic') ?>

<?= $this->section('header') ?>
<?= view('partials/header_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .chart-container {
        position: relative;
        height: 350px;
    }
</style>
<?= $this->endSection() ?>

<?php
$bulanList = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<?= $this->section('content') ?>
<div class="container-xxl mt-5" id="kt_content_container">
    <!--begin::Row - Filter & Header-->
    <div class="row g-5 g-xl-8 mb-5">
        <!--begin::Col - Filter-->
        <div class="col-xl-12">
            <!--begin::Card - Filter-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="mb-0">Filter Laporan</h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar gap-2">
                        <a href="<?= base_url('admin/laporan/export/excel?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-success">
                            <i class="ki-duotone ki-tablet-text-up fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export Excel
                        </a>
                        <a href="<?= base_url('admin/laporan/export/pdf?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-danger">
                            <i class="ki-duotone ki-document fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export PDF
                        </a>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <form method="get" class="row g-3 align-items-end">
                        <!--begin::Col - Bulan-->
                        <div class="col-md-4">
                            <label for="bulan" class="form-label fw-semibold">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select form-select-solid">
                                <?php foreach ($bulanList as $key => $nama): ?>
                                    <option value="<?= $key ?>" <?= $bulan == $key ? 'selected' : '' ?>>
                                        <?= $nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!--end::Col - Bulan-->

                        <!--begin::Col - Tahun-->
                        <div class="col-md-4">
                            <label for="tahun" class="form-label fw-semibold">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select form-select-solid">
                                <?php for ($t = date('Y'); $t >= 2021; $t--): ?>
                                    <option value="<?= $t ?>" <?= $tahun == $t ? 'selected' : '' ?>>
                                        <?= $t ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <!--end::Col - Tahun-->

                        <!--begin::Col - Tombol Filter-->
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ki-duotone ki-filter fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Tampilkan
                            </button>
                        </div>
                        <!--end::Col - Tombol Filter-->
                    </form>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card - Filter-->
        </div>
        <!--end::Col - Filter-->
    </div>
    <!--end::Row - Filter & Header-->

    <!--begin::Row - Chart-->
    <div class="row g-5 g-xl-8 mb-5">
        <!--begin::Col - Chart-->
        <div class="col-xl-12">
            <!--begin::Card - Chart-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="mb-0">Grafik Kunjungan Tahun <?= esc($tahun) ?></h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="kunjunganChart"></canvas>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card - Chart-->
        </div>
        <!--end::Col - Chart-->
    </div>
    <!--end::Row - Chart-->

    <!--begin::Row - Tabel-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col - Tabel-->
        <div class="col-xl-12">
            <!--begin::Card - Tabel Data-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="mb-0">Data <?= $bulanList[$bulan] ?> <?= $tahun ?></h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <span class="badge py-3 px-4 fs-7 badge-light-primary"><?= count($dataLaporan) ?> data</span>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_laporan">
                        <!--begin::Table head-->
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
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="text-gray-600 fw-semibold">
                            <?php if (empty($dataLaporan)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-muted">
                                        Tidak ada data untuk periode ini
                                    </td>
                                </tr>
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
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card - Tabel Data-->
        </div>
        <!--end::Col - Tabel-->
    </div>
    <!--end::Row - Tabel-->
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch chart data
        fetch("<?= base_url('admin/chart?tahun=' . $tahun) ?>")
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('kunjunganChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Pengunjung',
                                data: data.pengunjung,
                                backgroundColor: 'rgba(37, 99, 235, 0.8)',
                                borderColor: 'rgba(37, 99, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Tamu',
                                data: data.tamu,
                                backgroundColor: 'rgba(22, 163, 74, 0.8)',
                                borderColor: 'rgba(22, 163, 74, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            });
    });
</script>
<?= $this->endSection() ?>
