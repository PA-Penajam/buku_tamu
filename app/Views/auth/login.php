<?= $this->extend('layouts/metronic') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10">
        <div class="card card-flush w-md-450px py-5 shadow-sm">
            <div class="card-body py-10 px-10">
                <form class="form w-100" action="/login" method="post">
                    <!--begin::Heading-->
                    <div class="text-center mb-11">
                        <h1 class="text-gray-900 fw-bolder mb-3">Login Admin</h1>
                        <div class="text-gray-500 fw-semibold fs-6">Masukkan password untuk akses panel admin</div>
                    </div>
                    <!--begin::Heading-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-8">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Password</span>
                        </label>
                        <div class="position-relative">
                            <input type="password" placeholder="Masukkan password" name="password" autocomplete="off" class="form-control form-control-solid form-control-lg bg-transparent" id="password" required autofocus/>
                            <button type="button" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="togglePassword">
                                <i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </button>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Actions-->
                    <div class="d-grid mb-10">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="indicator-label">Sign In</span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>

                <div class="text-center">
                    <a href="/" class="text-gray-600 text-hover-primary fw-semibold fs-6">
                        <i class="ki-duotone ki-home-2 fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Scripts moved to auth_login.js -->
