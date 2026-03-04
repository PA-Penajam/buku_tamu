<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-primary mb-3">
                <i class="bi bi-journal-bookmark"></i> Buku Tamu
            </h1>
            <p class="lead text-muted">
                Silakan pilih jenis kunjungan Anda
            </p>
        </div>

        <!-- Cards Pilihan -->
        <div class="row g-4">
            <!-- Card Pengunjung -->
            <div class="col-md-6">
                <a href="/pengunjung" class="text-decoration-none">
                    <div class="card card-hover h-100 border-0 shadow">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-people-fill icon-large text-primary"></i>
                            </div>
                            <h2 class="card-title h3 mb-3">Pengunjung</h2>
                            <p class="card-text text-muted">
                                Untuk masyarakat umum yang berkunjung ke kantor
                            </p>
                            <span class="btn btn-primary mt-3">
                                <i class="bi bi-pencil-square me-2"></i>Isi Data
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Tamu -->
            <div class="col-md-6">
                <a href="/tamu" class="text-decoration-none">
                    <div class="card card-hover h-100 border-0 shadow">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-person-badge icon-large text-success"></i>
                            </div>
                            <h2 class="card-title h3 mb-3">Tamu</h2>
                            <p class="card-text text-muted">
                                Untuk tamu dari instansi/perusahaan yang berkunjung
                            </p>
                            <span class="btn btn-success mt-3">
                                <i class="bi bi-pencil-square me-2"></i>Isi Data
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-5 text-muted">
            <small>
                <i class="bi bi-shield-check me-1"></i>
                Data Anda aman dan terlindungi
            </small>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
