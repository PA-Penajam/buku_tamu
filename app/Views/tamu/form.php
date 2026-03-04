<?= $this->extend('layouts/metronic') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10">
        <div class="card card-flush w-lg-650px py-5 shadow-sm">
            <div class="card-header bg-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?> py-5 rounded-top">
                <h3 class="card-title text-white fw-bolder mb-0">
                    <i class="ki-duotone <?= $jenis_tamu === 'pengunjung' ? 'ki-people' : 'ki-badge' ?> text-white fs-2x me-3">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    Form Pendaftaran <?= ucfirst($jenis_tamu) ?>
                </h3>
            </div>
            
            <div class="card-body py-10">
                <!-- Error Messages -->
                <?php if (isset($errors)): ?>
                    <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                        <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-danger">Terjadi Kesalahan</h4>
                            <ul class="mb-0 text-danger ps-5">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="/tamu/store" method="post" class="form" id="kt_form_pendaftaran">
                    <!-- Hidden field untuk jenis tamu -->
                    <input type="hidden" name="jenis_tamu" value="<?= esc($jenis_tamu) ?>">

                    <div class="fv-row mb-8">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Nama Lengkap</span>
                        </label>
                        <input type="text" class="form-control form-control-lg form-control-solid" name="nama" value="<?= old('nama') ?>" placeholder="Masukkan nama lengkap Anda" required />
                    </div>

                    <?php if ($jenis_tamu === 'pengunjung'): ?>
                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Alamat</span>
                            </label>
                            <textarea class="form-control form-control-lg form-control-solid" name="alamat" rows="3" placeholder="Masukkan alamat lengkap Anda" required><?= old('alamat') ?></textarea>
                        </div>
                    <?php else: ?>
                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Instansi/Perusahaan</span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="instansi" value="<?= old('instansi') ?>" placeholder="Masukkan nama instansi atau perusahaan" required />
                        </div>
                    <?php endif; ?>

                    <div class="fv-row mb-8">
                        <label class="fs-5 fw-semibold mb-2">Nomor HP</label>
                        <input type="tel" class="form-control form-control-lg form-control-solid" name="hp" value="<?= old('hp') ?>" placeholder="Contoh: 081234567890" />
                    </div>

                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                            <span class="required">Tujuan Kunjungan</span>
                        </label>
                        <textarea class="form-control form-control-lg form-control-solid" name="tujuan" rows="4" placeholder="Jelaskan tujuan kunjungan Anda" required><?= old('tujuan') ?></textarea>
                    </div>

                    <div class="d-flex flex-stack mt-8">
                        <a href="/" class="btn btn-light btn-active-light-primary fw-bold px-6 py-3">
                            <i class="ki-duotone ki-arrow-left fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?> fw-bold px-6 py-3">
                            <i class="ki-duotone ki-check fs-2 me-2"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
