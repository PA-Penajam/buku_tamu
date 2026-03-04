<?= $this->extend('layouts/metronic') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="d-flex flex-column flex-center text-center p-10">
            <div class="card card-flush w-lg-650px py-5">
                <div class="card-body py-15 py-lg-20">
                    <div class="mb-14">
                        <a href="/" class="">
                            <i class="ki-duotone ki-book text-primary fs-3x"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        </a>
                    </div>
                    <h1 class="fw-bolder text-gray-900 mb-5">Selamat Datang di Buku Tamu</h1>
                    <div class="fw-semibold fs-6 text-gray-500 mb-10">Silakan pilih jenis kunjungan Anda untuk melanjutkan proses pengisian data</div>

                    <div class="row g-5">
                        <div class="col-md-6">
                            <a href="/pengunjung" class="card border border-primary border-hover border-dashed bg-light-primary text-center p-10 h-100 card-hover">
                                <i class="ki-duotone ki-people text-primary fs-3x mb-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                <h3 class="text-primary fw-bolder mb-2">Pengunjung</h3>
                                <div class="text-gray-600 fs-7">Untuk masyarakat umum yang berkunjung ke kantor</div>
                                <div class="btn btn-sm btn-primary fw-bold mt-5 w-100">Isi Data</div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="/tamu" class="card border border-success border-hover border-dashed bg-light-success text-center p-10 h-100 card-hover">
                                <i class="ki-duotone ki-badge text-success fs-3x mb-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                <h3 class="text-success fw-bolder mb-2">Tamu</h3>
                                <div class="text-gray-600 fs-7">Untuk tamu dari instansi/perusahaan yang berkunjung</div>
                                <div class="btn btn-sm btn-success fw-bold mt-5 w-100">Isi Data</div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="text-center mt-15 text-muted fw-semibold fs-7">
                        <i class="ki-duotone ki-shield-tick text-success fs-4"><span class="path1"></span><span class="path2"></span></i>
                        Data Anda aman dan terlindungi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
