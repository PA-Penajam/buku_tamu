<?= $this->extend('layouts/metronic') ?>

<?= $this->section('header') ?>
<div id="kt_header" class="header align-items-stretch">
    <!-- Header Content -->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="/admin" class="d-lg-none">
                <span class="fw-bolder fs-3 text-dark">Buku Tamu</span>
            </a>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
                        <a href="/admin" class="menu-item here show menu-lg-down-accordion me-lg-1">
                            <span class="menu-link py-3">
                                <span class="menu-title">Dashboard</span>
                            </span>
                        </a>
                        <a href="/admin/tamu" class="menu-item menu-lg-down-accordion me-lg-1">
                            <span class="menu-link py-3">
                                <span class="menu-title">Tamu</span>
                            </span>
                        </a>
                        <a href="/admin/pengunjung" class="menu-item menu-lg-down-accordion me-lg-1">
                            <span class="menu-link py-3">
                                <span class="menu-title">Pengunjung</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl mt-5" id="kt_content_container">
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-xl-4">
            <!-- Widget Hari Ini -->
            <div class="card card-xl-stretch mb-xl-8 bg-light-primary">
                <div class="card-body d-flex align-items-center pt-3 pb-0">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <span class="fw-bold text-dark fs-4 mb-2">Hari Ini</span>
                        <span class="fw-bolder text-dark fs-1 mb-2"><?= number_format($stats['total_hari_ini']) ?></span>
                        <span class="text-muted fw-semibold fs-7">Pengunjung & Tamu</span>
                    </div>
                    <div class="symbol symbol-70px symbol-circle">
                        <span class="symbol-label bg-primary">
                            <i class="ki-duotone ki-calendar-tick fs-2x text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Widget Bulan Ini -->
            <div class="card card-xl-stretch mb-xl-8 bg-light-success">
                <div class="card-body d-flex align-items-center pt-3 pb-0">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <span class="fw-bold text-dark fs-4 mb-2">Bulan Ini</span>
                        <span class="fw-bolder text-dark fs-1 mb-2"><?= number_format($stats['total_bulan_ini']) ?></span>
                        <span class="text-muted fw-semibold fs-7">Pengunjung & Tamu</span>
                    </div>
                    <div class="symbol symbol-70px symbol-circle">
                        <span class="symbol-label bg-success">
                            <i class="ki-duotone ki-calendar text-white fs-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Widget Tahun Ini -->
            <div class="card card-xl-stretch mb-5 mb-xl-8 bg-light-info">
                <div class="card-body d-flex align-items-center pt-3 pb-0">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-13 me-2">
                        <span class="fw-bold text-dark fs-4 mb-2">Tahun Ini</span>
                        <span class="fw-bolder text-dark fs-1 mb-2"><?= number_format($stats['total_tahun_ini']) ?></span>
                        <span class="text-muted fw-semibold fs-7">Pengunjung & Tamu</span>
                    </div>
                    <div class="symbol symbol-70px symbol-circle">
                        <span class="symbol-label bg-info">
                            <i class="ki-duotone ki-time text-white fs-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-8">
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Total Pengunjung</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Rangkuman pengunjung keseluruhan</span>
                    </h3>
                </div>
                <div class="card-body text-center py-15">
                    <div class="fs-4x fw-bolder text-primary mb-5"><?= number_format($stats['total_pengunjung']) ?></div>
                    <a href="/admin/pengunjung" class="btn btn-primary fw-bold px-8 py-3">Lihat Pengunjung</a>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Total Tamu</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Rangkuman tamu keseluruhan</span>
                    </h3>
                </div>
                <div class="card-body text-center py-15">
                    <div class="fs-4x fw-bolder text-success mb-5"><?= number_format($stats['total_tamu']) ?></div>
                    <a href="/admin/tamu" class="btn btn-success fw-bold px-8 py-3">Lihat Tamu</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card border-0 bg-dark text-white">
                <div class="card-body text-center py-10">
                    <h6 class="text-white-50 fs-4 mb-5">TOTAL KESELURUHAN DATA</h6>
                    <div class="fs-4x fw-bolder mb-0"><?= number_format($stats['total_semua']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
