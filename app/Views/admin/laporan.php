<?= $this->extend('layouts/main') ?>
<?= $this->section('navbar') ?>
<?= view('partials/navbar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .chart-container {
        position: relative;
        height: 350px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Laporan Kunjungan</h2>
        <p class="text-muted mb-0">Statistik dan data kunjungan</p>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    <?php
                    $bulanList = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    foreach ($bulanList as $key => $nama):
                    ?>
                        <option value="<?= $key ?>" <?= $bulan == $key ? 'selected' : '' ?>>
                            <?= $nama ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <?php for ($t = date('Y'); $t >= 2021; $t--): ?>
                        <option value="<?= $t ?>" <?= $tahun == $t ? 'selected' : '' ?>>
                            <?= $t ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-filter me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Chart -->
<div class="card border-0 shadow mb-4">
    <div class="card-header bg-transparent">
        <h5 class="mb-0">
            <i class="bi bi-bar-chart me-2"></i>Grafik Kunjungan Tahun <?= esc($tahun) ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="kunjunganChart"></canvas>
        </div>
    </div>
</div>

<!-- Tabel Data -->
<div class="card border-0 shadow">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>Data <?= $bulanList[$bulan] ?> <?= $tahun ?>
        </h5>
        <span class="badge bg-primary"><?= count($dataLaporan) ?> data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Nama</th>
                        <th>Instansi/Alamat</th>
                        <th>Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dataLaporan)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data untuk periode ini
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($dataLaporan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                                    </small>
                                    <div><?= date('H:i', strtotime($row['tanggal'])) ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $row['jenis_tamu'] === 'pengunjung' ? 'primary' : 'success' ?>">
                                        <?= ucfirst($row['jenis_tamu']) ?>
                                    </span>
                                </td>
                                <td><strong><?= esc($row['nama']) ?></strong></td>
                                <td>
                                    <?= esc($row['jenis_tamu'] === 'tamu' ? ($row['instansi'] ?? '-') : ($row['alamat'] ?? '-')) ?>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="<?= esc($row['tujuan']) ?>">
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch chart data
        fetch('/admin/chart?tahun=<?= $tahun ?>')
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
