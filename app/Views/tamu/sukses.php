<?= $this->extend('layouts/metronic') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10">
        <div class="card card-flush w-lg-650px py-5 shadow-sm">
            <div class="card-body py-10 px-10 text-center">
                <!-- Ikon sukses -->
                <div class="symbol symbol-100px symbol-circle bg-light-success mx-auto mb-8">
                    <span class="symbol-label">
                        <i class="ki-duotone ki-shield-check text-success fs-5x">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </span>
                </div>

                <h2 class="fw-bolder text-gray-900 mb-3">Pendaftaran Berhasil!</h2>
                <p class="text-gray-500 fw-semibold fs-6 mb-8">
                    Terima kasih telah mengisi data kunjungan Anda.
                </p>

                <!-- Info kunjungan -->
                <div class="border border-dashed border-success rounded p-6 mb-8 bg-light-success">
                    <div class="row g-5">
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">Nomor Kunjungan</div>
                            <div class="fw-bolder text-success fs-2x"><?= str_pad($antrian, 3, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted fw-semibold fs-7 mb-1">Jenis Kunjungan</div>
                            <div class="fw-bolder text-gray-800 fs-4"><?= ucfirst($tamu['jenis_tamu']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan data -->
                <div class="text-start mb-8">
                    <h5 class="fw-bold text-gray-800 mb-4">Ringkasan Data</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0" style="width: 140px;">Nama</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['nama']) ?></td>
                                </tr>
                                <?php if ($tamu['jenis_tamu'] === 'tamu' && !empty($tamu['instansi'])): ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Instansi</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['instansi']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($tamu['jenis_tamu'] === 'pengunjung' && !empty($tamu['alamat'])): ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Alamat</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['alamat']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($tamu['hp'])): ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">No. HP</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['hp']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Tujuan</td>
                                    <td class="fw-bold text-gray-800"><?= esc($tamu['tujuan']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold ps-0">Waktu</td>
                                    <td class="fw-bold text-gray-800"><?= date('d/m/Y H:i', strtotime($tamu['tanggal'])) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tombol aksi -->
                <a href="/" class="btn btn-primary fw-bold px-8 py-3">
                    <i class="ki-duotone ki-home-2 fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
