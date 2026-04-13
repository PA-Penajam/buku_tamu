<?= $this->extend('layouts/metronic') ?>

<?= $this->section('styles') ?>
<style>
    /* Card choice hover animation */
    .card-choice {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-choice:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    /* Info strip pulse animation */
    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }
    .pulse-dot.buka { background-color: #50CD89; }
    .pulse-dot.tutup { background-color: #F1416C; }

    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.5); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="d-flex flex-column flex-center text-center p-10 w-100">
            <div class="card card-flush w-lg-750px py-5 mx-auto">
                <div class="card-body py-10 px-10">

                    <!-- Header -->
                    <div class="mb-4">
                        <a href="/">
                            <i class="ki-duotone ki-book text-primary fs-3x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        </a>
                    </div>
                    <h1 class="fw-bolder text-gray-900 mb-3">Selamat Datang di Buku Tamu</h1>
                    <div class="fw-semibold fs-6 text-gray-500 mb-8">Silakan pilih jenis kunjungan Anda untuk melanjutkan proses pengisian data</div>

                    <!-- Info Strip -->
                    <div class="row g-4 mb-10">
                        <!-- Status Kantor -->
                        <div class="col-md-4">
                            <div class="bg-light rounded p-5 h-100">
                                <div class="text-muted fw-semibold fs-7 mb-2">Status Kantor</div>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="pulse-dot tutup" id="statusDot"></span>
                                    <span class="fw-bolder text-gray-800 fs-6" id="statusText">Memuat...</span>
                                </div>
                            </div>
                        </div>
                        <!-- Jam Operasional -->
                        <div class="col-md-4">
                            <div class="bg-light rounded p-5 h-100">
                                <div class="text-muted fw-semibold fs-7 mb-2">Jam Operasional</div>
                                <div class="fw-bolder text-gray-800 fs-6" id="jamOperasional">
                                    <i class="ki-duotone ki-clock fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                    <span id="jamText">08:00 - 16:00</span>
                                </div>
                            </div>
                        </div>
                        <!-- Kunjungan Hari Ini -->
                        <div class="col-md-4">
                            <div class="bg-light rounded p-5 h-100">
                                <div class="text-muted fw-semibold fs-7 mb-2">Kunjungan Hari Ini</div>
                                <div class="fw-bolder text-primary fs-3x lh-1" id="counterKunjungan">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Pilihan -->
                    <div class="row g-5 justify-content-center">
                        <div class="col-md-5">
                            <a href="/pengunjung" class="card card-choice border border-primary border-dashed bg-light-primary text-center p-8 d-block text-decoration-none">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mx-auto mb-4">
                                    <span class="symbol-label">
                                        <i class="ki-duotone ki-people text-primary fs-3x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </span>
                                </div>
                                <h3 class="text-primary fw-bolder mb-2">Pengunjung</h3>
                                <div class="text-gray-600 fs-7 mb-4">Untuk masyarakat umum yang berkunjung ke kantor</div>
                                <div class="btn btn-sm btn-primary fw-bold w-100">Isi Data Pengunjung</div>
                            </a>
                        </div>
                        <div class="col-md-5">
                            <a href="/tamu" class="card card-choice border border-success border-dashed bg-light-success text-center p-8 d-block text-decoration-none">
                                <div class="symbol symbol-70px symbol-circle bg-light-success mx-auto mb-4">
                                    <span class="symbol-label">
                                        <i class="ki-duotone ki-badge text-success fs-3x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </span>
                                </div>
                                <h3 class="text-success fw-bolder mb-2">Tamu</h3>
                                <div class="text-gray-600 fs-7 mb-4">Untuk tamu dari instansi/perusahaan yang berkunjung</div>
                                <div class="btn btn-sm btn-success fw-bold w-100">Isi Data Tamu</div>
                            </a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-10 text-muted fw-semibold fs-7">
                        <i class="ki-duotone ki-shield-tick text-success fs-4"><span class="path1"></span><span class="path2"></span></i>
                        Data Anda aman dan terlindungi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fetch data statistik hari ini
    fetch('<?= base_url('api/stats/today') ?>')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            // Update status kantor
            var statusDot = document.getElementById('statusDot');
            var statusText = document.getElementById('statusText');
            if (data.status_kantor === 'buka') {
                statusDot.classList.remove('tutup');
                statusDot.classList.add('buka');
                statusText.textContent = 'Buka';
                statusText.classList.add('text-success');
            } else {
                statusDot.classList.remove('buka');
                statusDot.classList.add('tutup');
                statusText.textContent = 'Tutup';
                statusText.classList.add('text-danger');
            }

            // Update jam operasional
            document.getElementById('jamText').textContent = data.jam_operasional;

            // Update counter kunjungan
            document.getElementById('counterKunjungan').textContent = data.total_hari_ini;
        })
        .catch(function () {
            document.getElementById('statusText').textContent = 'Gagal memuat';
        });
});
</script>
<?= $this->endSection() ?>
