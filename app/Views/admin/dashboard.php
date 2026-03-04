<?= $this->extend('layouts/main') ?>
<?= $this->section('navbar') ?>
<?= view('partials/navbar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h2 class="mb-0">Dashboard</h2>
        <p class="text-muted">Ringkasan data kunjungan</p>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-day text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Hari Ini</h6>
                        <h3 class="mb-0"><?= number_format($stats['total_hari_ini']) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-month text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Bulan Ini</h6>
                        <h3 class="mb-0"><?= number_format($stats['total_bulan_ini']) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar3 text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Tahun Ini</h6>
                        <h3 class="mb-0"><?= number_format($stats['total_tahun_ini']) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-people text-primary me-2"></i>Pengunjung
                </h5>
            </div>
            <div class="card-body text-center py-4">
                <h2 class="display-4 text-primary mb-0"><?= number_format($stats['total_pengunjung']) ?></h2>
                <p class="text-muted mb-0">Total Pengunjung</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="/admin/pengunjung" class="btn btn-outline-primary btn-sm w-100">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge text-success me-2"></i>Tamu
                </h5>
            </div>
            <div class="card-body text-center py-4">
                <h2 class="display-4 text-success mb-0"><?= number_format($stats['total_tamu']) ?></h2>
                <p class="text-muted mb-0">Total Tamu</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="/admin/tamu" class="btn btn-outline-success btn-sm w-100">
                    Lihat Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Total Keseluruhan -->
<div class="row mt-4">
    <div class="col">
        <div class="card border-0 bg-gradient-primary text-white">
            <div class="card-body text-center py-4">
                <h6 class="text-white-50 mb-2">TOTAL SEMUA KUNJUNGAN</h6>
                <h1 class="display-3 mb-0"><?= number_format($stats['total_semua']) ?></h1>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
