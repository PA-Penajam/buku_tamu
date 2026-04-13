<?= $this->extend('layouts/metronic') ?>

<?= $this->section('header') ?>
<?= view('partials/header_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<div class="app-toolbar py-3 py-lg-6" id="kt_app_toolbar">
    <div class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center me-10">Daftar Pengunjung</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="/" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-5px"></span></li>
                <li class="breadcrumb-item text-gray-500">Admin</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-5px"></span></li>
                <li class="breadcrumb-item text-gray-500">Daftar Pengunjung</li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl mt-5" id="kt_content_container"
     data-url-dt="<?= base_url('admin/pengunjung/dt') ?>"
     data-url-bulk-delete="<?= base_url('admin/api/bulk-delete') ?>"
     data-url-update="<?= base_url('admin/pengunjung/update/') ?>"
     data-url-store="<?= base_url('admin/pengunjung/store') ?>"
     data-url-delete="<?= base_url('admin/pengunjung/delete/') ?>"
     data-url-uploads="<?= base_url('uploads/tamu/') ?>"
     data-url-blank="<?= base_url('assets/media/avatars/blank.png') ?>">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h2 class="mb-0">Daftar Pengunjung</h2>
            </div>
            <div class="card-toolbar gap-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light-danger btn-active-light-danger dropdown-toggle" data-bs-toggle="dropdown" id="btnBulkActions" disabled>
                        <i class="ki-duotone ki-trash fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Hapus yang Dipilih
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="dropdown-item text-danger" id="btnBulkDelete">
                                <i class="ki-duotone ki-trash fs-4 me-2"></i>Hapus Terpilih
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="<?= base_url('admin/laporan') ?>" class="btn btn-light-success">
                    <i class="ki-duotone ki-chart fs-3"><span class="path1"></span><span class="path2"></span></i>
                    Lihat Laporan
                </a>
                <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                    <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span class="path2"></span></i>
                    Tambah Pengunjung
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_pengunjung">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-50px"><input type="checkbox" class="form-check-input" id="selectAll" /></th>
                        <th>Foto</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Tujuan</th>
                        <th class="text-end min-w-100px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold"></tbody>
                <tfoot>
                    <tr class="text-start text-muted fw-bold fs-7">
                        <th></th>
                        <th>Foto</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Tujuan</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Drawer Detail -->
<div id="kt_drawer_detail" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'350px', 'md': '425px'}" data-kt-drawer-direction="end">
    <div class="card rounded-0 w-100">
        <div class="card-header pe-5" id="kt_drawer_header">
            <h3 class="card-title fw-bold">Detail Pengunjung</h3>
            <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_close" data-kt-drawer-close="kt_drawer_detail">
                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
            </div>
        </div>
        <div class="card-body pt-5" id="kt_drawer_body"></div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="kt_modal_form">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal_title">Tambah Pengunjung</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form id="kt_form" onsubmit="submitForm(event)">
                <div class="modal-body">
                    <input type="hidden" id="input_id" name="id">
                    <input type="hidden" name="jenis_tamu" value="pengunjung">
                    <div class="mb-5">
                        <label class="required form-label">Nama</label>
                        <input type="text" class="form-control form-control-solid" name="nama" id="input_nama" required placeholder="Nama Lengkap"/>
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Alamat</label>
                        <input type="text" class="form-control form-control-solid" name="alamat" id="input_alamat" required placeholder="Alamat Lengkap"/>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">No. HP</label>
                        <input type="text" class="form-control form-control-solid" name="hp" id="input_hp" placeholder="08xxxxxxxxxx"/>
                    </div>
                    <div class="mb-0">
                        <label class="required form-label">Tujuan</label>
                        <textarea class="form-control form-control-solid" name="tujuan" id="input_tujuan" rows="3" required placeholder="Tujuan Kunjungan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress d-none">Mohon tunggu...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Scripts moved to admin_pengunjung.js -->
